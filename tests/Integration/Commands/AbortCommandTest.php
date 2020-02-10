<?php

namespace Tests\Integration\Commands;

use App\Commands\AbortCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Integration\IntegrationTestCase;

/**
 * Class AbortCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class AbortCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->add(new AbortCommand);
        $command       = $this->app->find('log:abort');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function needsConfirmation()
    {
        $this->startLog();

        $this->command->setInputs(['y']);
        $this->command->execute([]);

        $this->assertStringContainsString(
            'Are you sure you want to abort the current running task log? [y/N]',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function abortWithConfirmationYes()
    {
        $this->startLog();

        $this->command->setInputs(['y']);
        $this->command->execute([]);

        $this->assertDatabaseDoesntHave('logs', [
            'task_id' => 'TASK-123'
        ]);
    }

    /**
     * @test
     */
    public function noActionWithNoConfirmationAnswer()
    {
        $this->startLog();

        $this->command->setInputs(['n']);
        $this->command->execute([]);

        $this->assertDatabaseHas('logs', [
            'task_id' => 'TASK-123'
        ]);
    }

    /**
     * @test
     */
    public function abortWithYesOption()
    {
        $this->startLog();

        $this->command->execute(['--yes' => true]);

        $this->assertDatabaseDoesntHave('logs', [
            'task_id' => 'TASK-123'
        ]);
    }

    /**
     * Starts a task for stopping test
     */
    private function startLog()
    {
        $time = date('Y-m-d H:i', strtotime('-1 hour'));

        $this->db->insert('logs', [
            'task_id'     => 'TASK-123',
            'started_at'  => $time,
            'description' => 'Working on TASK-123 issue',
        ]);
    }

}
