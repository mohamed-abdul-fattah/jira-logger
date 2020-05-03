<?php

namespace Tests\Integration\Commands;

use App\Entities\Jira;
use App\Commands\SyncCommand;
use App\Repositories\JiraRepository;
use App\Repositories\TaskRepository;
use App\Services\Connect\JiraConnect;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SyncCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class SyncCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setPlatformUri();
        $connect = new JiraConnect(
            new JiraRepository,
            new TaskRepository,
            new Jira
        );
        $this->app->add(new SyncCommand($connect));
        $command       = $this->app->find('log:sync');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function itDoesNothingWhenTasksAreUpToDate()
    {
        $this->command->execute([]);

        $this->assertStringContainsString(
            'Logs are up to date.',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function itSavesSyncStatusToLogs()
    {
        $this->db->insert('logs', [
            'task_id'    => 'TASK-123',
            'started_at' => date('Y-m-d 00:00'),
            'ended_at'   => date('Y-m-d 01:00'),
            'log'        => '1h 0m',
            'synced'     => 0,
        ]);

        $this->command->execute([]);

        $this->assertDatabaseHas('logs', [
            'task_id'    => 'TASK-123',
            'synced'     => 1,
        ]);
    }
}
