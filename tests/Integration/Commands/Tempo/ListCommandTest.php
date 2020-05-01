<?php

namespace Tests\Integration\Commands\Tempo;

use App\Commands\Tempo\ListCommand;
use App\Repositories\TempoRepository;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->add(new ListCommand(new TempoRepository));
        $command       = $this->app->find('tempo:list');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function itListsAttributes()
    {
        $this->db->saveSetting('tempo:attributes:default', '{"default":"value"}');
        $this->db->saveSetting('tempo:attributes:group', '{"key":"value"}');

        $this->command->execute([]);

        $this->assertStringContainsString(
            'default',
            $this->command->getDisplay()
        );
        $this->assertStringContainsString(
            '{"default":"value"}',
            $this->command->getDisplay()
        );
        $this->assertStringContainsString(
            'group',
            $this->command->getDisplay()
        );
        $this->assertStringContainsString(
            '{"key":"value"}',
            $this->command->getDisplay()
        );
    }

    /**
     * @test
     */
    public function itHandlesEmptyList()
    {
        $this->command->execute([]);

        $this->assertStringContainsString(
            'No attributes found!',
            $this->command->getDisplay()
        );
    }
}
