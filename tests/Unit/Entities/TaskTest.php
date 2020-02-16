<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\Task;
use App\Exceptions\EntityException;

/**
 * Class TaskTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class TaskTest extends TestCase
{
    /**
     * @var Task
     */
    private $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->task = new Task;
    }

    /**
     * @test
     */
    public function cannotLogStartTimeAfterEndTime()
    {
        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Stop time must be greater than start time');

        $this->task->setStartedAt('2020-02-02 00:01');
        $this->task->addLog('2020-02-02 00-00');
    }

    /**
     * @test
     */
    public function cannotLogStartTimeEqualsEndTime()
    {
        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Stop time must be greater than start time');

        $this->task->setStartedAt('2020-02-02 00:00');
        $this->task->addLog('2020-02-02 00-00');
    }

    public function logsProvider()
    {
        return [
            ['2020-02-02 00:00', '2020-02-02 00:01', '0h 1m'],
            ['2020-02-02 00:00', '2020-02-02 01:00', '1h 0m'],
            ['2020-02-02 00:00', '2020-02-02 01:01', '1h 1m'],
            ['2020-02-02 00:00', '2020-02-02 01:20', '1h 20m'],
            /** Logs are compared according to hours, minutes not days */
            ['2020-02-02 00:10', '2020-02-03 00:20', '0h 10m'],
            ['2020-02-02 00:10', '2020-03-03 00:20', '0h 10m'],
        ];
    }

    /**
     * @param string $start
     * @param string $end
     * @param string $expected
     * @test
     * @dataProvider logsProvider
     */
    public function addLog($start, $end, $expected)
    {
        $this->task->setStartedAt($start);
        $log = $this->task->addLog($end);

        $this->assertSame($expected, $log);
    }

    public function conversionProvider()
    {
        return [
            ['0h 1m', 60],
            ['0h 20m', 1200],
            ['1h 0m', 3600],
            ['1h 1m', 3660],
            ['1h 20m', 4800],
            ['20h 0m', 72000],
            ['20h 1m', 72060],
            ['20h 20m', 73200],
        ];
    }

    /**
     * @param string $log
     * @param int $seconds
     * @dataProvider conversionProvider
     * @test
     */
    public function itConvertsLogIntoSeconds($log, $seconds)
    {
        $this->task->setLog($log);
        $conversion = $this->task->logInSeconds();

        $this->assertEquals($seconds, $conversion);
    }
}
