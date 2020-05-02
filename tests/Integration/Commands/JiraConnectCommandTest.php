<?php

namespace Tests\Integration\Commands;

use App\Entities\Jira;
use App\Commands\ConnectCommand;
use App\Repositories\JiraRepository;
use App\Repositories\TaskRepository;
use App\Services\Connect\JiraConnect;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConnectCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class JiraConnectCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $connect = new JiraConnect(
            new JiraRepository,
            new TaskRepository,
            new Jira
        );
        $this->app->add(new ConnectCommand($connect));
        $command       = $this->app->find('connect');
        $this->command = new CommandTester($command);
    }

    /**
     * @
     */
    public function itAsksForUsernameAndPassword()
    {
        $this->command->setInputs(['username', 'password']);
        $this->command->execute([]);

        $this->assertStringContainsString(
            'Username:',
            $this->command->getDisplay()
        );
        $this->assertStringContainsString(
            'Password:',
            $this->command->getDisplay()
        );
    }

    /**
     * @
     */
    public function itGetsUsernameViaCommandOptions()
    {
        $this->command->setInputs(['password']);
        $this->command->execute(['--username' => 'username']);

        $this->assertStringContainsString(
            'Password:',
            $this->command->getDisplay()
        );
        $this->assertStringNotContainsString(
            'Username:',
            $this->command->getDisplay()
        );
    }

    /**
     * @
     */
    public function itSavesJiraSessionIdIntoDb()
    {
        $this->command->setInputs(['password']);
        $this->command->execute(['--username' => 'username']);

        $this->assertDatabaseHas('settings', [
            'key'   => 'session_id',
            'value' => 'sessionId',
        ]);
    }
}
