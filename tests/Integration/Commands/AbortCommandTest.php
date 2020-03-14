<?php

namespace Tests\Integration\Commands;

use App\Commands\AbortCommand;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class AbortCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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
}
