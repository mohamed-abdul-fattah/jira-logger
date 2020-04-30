<?php

namespace Tests\Integration\Commands\Tempo;

use App\Exceptions\RunTimeException;
use App\Repositories\TempoRepository;
use App\Commands\Tempo\AttributesCommand;
use Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Services\Validators\Tempo\AttributesValidator;

class AttributesCommandTest extends IntegrationTestCase
{
    /**
     * @var CommandTester
     */
    private $command;

    protected function setUp(): void
    {
        parent::setUp();

        $validator     = new AttributesValidator;
        $repository    = new TempoRepository;
        $this->app->add(new AttributesCommand($validator, $repository));
        $command       = $this->app->find('tempo:attributes');
        $this->command = new CommandTester($command);
    }

    /**
     * @test
     */
    public function itSavesDefaultAttributes()
    {
        $this->command->execute(['attributes' => '{"attributes":{"_Role_":{"name":"Role","value":"Developer"}}}']);

        $this->assertDatabaseHas('settings', [
            'key'   => 'tempo:attributes:default',
            'value' => '{"attributes":{"_Role_":{"name":"Role","value":"Developer"}}}',
        ]);
    }

    /**
     * @test
     */
    public function itSavesAttributesUnderGroupName()
    {
        $this->command->execute([
            'attributes' => '{"attributes":{"_Role_":{"name":"Role","value":"Developer"}}}',
            '-g'         => 'my_group',
        ]);

        $this->assertDatabaseHas('settings', [
            'key'   => 'tempo:attributes:my_group',
            'value' => '{"attributes":{"_Role_":{"name":"Role","value":"Developer"}}}',
        ]);
    }

    /**
     * @test
     */
    public function itValidatesAttributes()
    {
        $this->expectException(RunTimeException::class);
        $this->expectDeprecationMessage('Invalid JSON attributes!');

        $this->command->execute(['attributes' => 'invalid']);
    }
}
