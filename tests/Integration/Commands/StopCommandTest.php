<?php

namespace Tests\Integration\Commands;

use App\Services\LogTimer;
use App\Commands\StopCommand;
use App\Exceptions\RunTimeException;
use App\Repositories\TaskRepository;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class StopCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class StopCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $timer         = new LogTimer(new TaskRepository);
        $this->app->add(new StopCommand($timer));
        $command       = $this->app->find('log:stop');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function cannotStopNoRunningTask()
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage('There is no running log! Run `log:start` to start logging timer');

        $this->command->execute([]);
    }

    /**
     * @test
     */
    public function stopRunningTaskNow()
    {
        $this->startLog();
        $this->command->execute([]);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-123',
            'description' => 'Working on TASK-123 issue',
            'ended_at'    => date('Y-m-d H:i'),
            'log'         => '1h 0m',
        ]);
    }

    /**
     * @test
     */
    public function stopRunningTaskWithEndTime()
    {
        $this->startLog();
        $time = date('H:i', strtotime('+1 minute'));
        $this->command->execute(['-t' => $time]);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-123',
            'description' => 'Working on TASK-123 issue',
            'ended_at'    => date("Y-m-d {$time}"),
            'log'         => '1h 1m',
        ]);
    }

    /**
     * @test
     */
    public function updateDescriptionOnStop()
    {
        $this->startLog();
        $this->command->execute(['-d' => 'DONE']);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-123',
            'description' => 'DONE',
            'ended_at'    => date("Y-m-d H:i"),
            'log'         => '1h 0m',
        ]);
    }

    /**
     * @test
     */
    public function updateTempoGroupOnStrop()
    {
        $this->startLog();
        $this->db->insert('settings', [
            'id'    => 3,
            'key'   => 'tempo:attributes:default',
            'value' => '{}'
        ]);
        $this->command->execute(['-g' => 'default']);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-123',
            'ended_at'    => date("Y-m-d H:i"),
            'group_id'    => 3,
        ]);
    }
}
