<?php

namespace Tests\Integration\Commands;

use App\Commands\StartCommand;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class StartCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  v1.0.0
 */
class StartCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->add(new StartCommand);
        $command       = $this->app->find('log:start');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function startLogWithTaskIdOnly()
    {
        $this->command->execute(['task id' => 'TASK-1234']);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-1234',
            'started_at'  => date('Y-m-d H:i'),
            'ended_at'    => null,
            'description' => 'Working on TASK-1234 issue'
        ]);
    }

    /**
     * @test
     */
    public function startLogWithStartTime()
    {
        $this->command->execute([
            'task id' => 'TASK-007',
            '--time'  => '00:01'
        ]);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-007',
            'started_at'  => date('Y-m-d 00:01'),
            'ended_at'    => null,
            'description' => 'Working on TASK-007 issue'
        ]);
    }

    /**
     * @test
     */
    public function startLogWithDescription()
    {
        $this->command->execute([
            'task id' => 'TASK-123',
            '-d'      => 'WIP',
        ]);

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-123',
            'started_at'  => date('Y-m-d H:i'),
            'ended_at'    => null,
            'description' => 'WIP'
        ]);
    }
}
