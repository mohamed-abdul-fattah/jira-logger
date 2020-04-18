<?php

namespace Tests\Integration\Commands;

use App\Commands\StatusCommand;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class StatusCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class StatusCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->add(new StatusCommand);
        $command       = $this->app->find('log:status');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function noRunningTasks()
    {
        $this->command->execute([]);

        $this->assertStringContainsString(
            'No items',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function runningTaskInfo()
    {
        $this->startLog();

        $this->command->execute([]);

        $output = $this->command->getDisplay();
        $this->assertStringContainsString(
            'TASK-123',
            $output
        );
        $this->assertStringContainsString(
            'Working on TASK-123 issue',
            $output
        );
        $this->assertStringContainsString(
            date('Y-m-d'),
            $output
        );
    }

    /**
     * @test
     */
    public function zeroUnSyncedTasks()
    {
        $this->command->execute([]);

        $this->assertStringContainsString(
            '0 task(s)',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function zeroUnSyncedTasksWhenOneRunningTaskLog()
    {
        $this->startLog();

        $this->command->execute([]);

        $this->assertStringContainsString(
            '0 task(s)',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function oneUnSyncedTaskForOneLoggedTask()
    {
        $this->db->insert('logs', [
            'task_id'    => 'TASK',
            'started_at' => date('Y-m-d H:i', strtotime('-1 hour')),
            'ended_at'   => date('Y-m-d H:i'),
        ]);

        $this->command->execute([]);

        $this->assertStringContainsString(
            '1 task(s)',
            $this->command->getDisplay()
        );
    }
}
