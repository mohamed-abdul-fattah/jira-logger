<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Entities\Task;
use App\Services\LogTimer;
use App\Exceptions\RunTimeException;
use App\Repositories\Contracts\ITaskRepository;

class LogTimerTest extends TestCase
{
    /**
     * @test
     */
    public function cannotStartWithRunningTask()
    {
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(new Task);
        $repo->expects($this->never())
             ->method('startLog');

        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage('There is a running log already! Run `log:abort` or `log:stop`, then try again');

        $timer = new LogTimer($repo);
        $timer->start('TASK-1234');
    }

    /**
     * @test
     */
    public function canStartWithoutRunningTask()
    {
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(null);
        $repo->expects($this->once())
             ->method('startLog');

        $timer = new LogTimer($repo);
        $timer->start('TASK-1234');
    }

    /**
     * @test
     */
    public function cannotStopWithNoRunningTask()
    {
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(null);
        $repo->expects($this->never())
             ->method('stopLog');

        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage('There is no running log! Run `log:start` to start logging timer');

        $timer = new LogTimer($repo);
        $timer->stop();
    }

    /**
     * @test
     */
    public function canStopWithRunningTask()
    {
        $task = $this->createMock(Task::class);
        $task->expects($this->once())
             ->method('addLog');

        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn($task);
        $repo->expects($this->once())
             ->method('stopLog');

        $timer = new LogTimer($repo);
        $timer->stop();
    }

    /**
     * @test
     */
    public function cannotAbortWithNoRunningTask()
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage('No running task log to abort!');

        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(null);
        $repo->expects($this->never())
             ->method('abortLog');

        $timer = new LogTimer($repo);
        $timer->abort();
    }

    /**
     * @test
     */
    public function canAbortARunningTask()
    {
        $task = $this->createMock(Task::class);
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn($task);
        $repo->expects($this->once())
             ->method('abortLog');

        $timer = new LogTimer($repo);
        $timer->abort();
    }

    /**
     * @test
     */
    public function returnZeroWhenNoRunningTask()
    {
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(null);
        $repo->expects($this->once())
             ->method('getUnSyncedLogs')
             ->willReturn([]);

        $timer = new LogTimer($repo);
        list($logs, $loggedTime, $task) = $timer->getStatus();

        $this->assertEquals(0, $logs);
        $this->assertEquals('0h 0m', $loggedTime);
        $this->assertTrue(is_null($task));
    }

    /**
     * @test
     */
    public function returnOneWhenThereIsStoppedUnSyncedTask()
    {
        $task = $this->createMock(Task::class);
        $task->expects($this->once())
             ->method('logInSeconds')
             ->willReturn(600); // 10 minutes
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn(null);
        $repo->expects($this->once())
             ->method('getUnSyncedLogs')
             ->willReturn([$task]);

        $timer = new LogTimer($repo);
        list($logs, $loggedTime, $task) = $timer->getStatus();

        $this->assertEquals(1, $logs);
        $this->assertEquals('0h 10m', $loggedTime);
        $this->assertTrue(is_null($task));
    }

    /**
     * @test
     */
    public function returnTaskWhenThereIsRunningTask()
    {
        $task = $this->createMock(Task::class);
        $task->expects($this->once())
             ->method('logInSeconds')
             ->willReturn(60); // One minute
        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
             ->method('getRunningTask')
             ->willReturn($task);
        $repo->expects($this->once())
             ->method('getUnSyncedLogs')
             ->willReturn([$task]);

        $timer = new LogTimer($repo);
        /** @noinspection PhpUnusedLocalVariableInspection */
        list($_, $_, $task) = $timer->getStatus();

        $this->assertTrue($task instanceof Task);
    }

    public function convertForHumanProvider()
    {
        return [
            [1, '0h 0m'],
            [60, '0h 1m'],
            [121, '0h 2m'],
            [3600, '1h 0m'],
            [3610, '1h 0m'],
            [3660, '1h 1m'],
            [7260, '2h 1m'],
        ];
    }

    /**
     * @param int $seconds
     * @param string $human
     * @test
     * @dataProvider convertForHumanProvider
     */
    public function itConvertsSecondsForHuman($seconds, $human)
    {
        $expected = LogTimer::timeForHuman($seconds);
        $this->assertSame($human, $expected);
    }
}
