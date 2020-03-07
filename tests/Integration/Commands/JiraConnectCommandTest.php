<?php

namespace Tests\Integration\Commands;

use App\Commands\ConnectCommand;
use App\Services\Connect\JiraConnect;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConnectCommandTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
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

        $this->app->add(new ConnectCommand(new JiraConnect));
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
