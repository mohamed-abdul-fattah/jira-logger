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
             ->method('addLog')
             ->willReturn('endLog');

        $repo = $this->createMock(ITaskRepository::class);
        $repo->expects($this->once())
            ->method('getRunningTask')
            ->willReturn($task);

        $timer = new LogTimer($repo);
        $timer->stop();
    }
}
