<?php

namespace Tests\Integration\Repositories\Tempo;

use App\Entities\Tempo\Attribute;
use App\Repositories\TempoRepository;
use Tests\Integration\IntegrationTestCase;

class TempoRepositoryTest extends IntegrationTestCase
{
    /**
     * @var TempoRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TempoRepository;
        $this->insertAttribute('{"a1": "v1"}');
        $this->insertAttribute('{"a2": "v2"}', 'group');
    }

    /**
     * @test
     */
    public function itSavesNewAttribute()
    {
        $attributes = '{"key": "value"}';
        $this->repository->saveAttributes($attributes);

        $this->assertDatabaseHas('settings', [
            'key'   => 'tempo:attributes:default',
            'value' => $attributes
        ]);
    }

    /**
     * @test
     */
    public function itSavesGroupName()
    {
        $attributes = '{"key": "value"}';
        $this->repository->saveAttributes($attributes, 'group');

        $this->assertDatabaseHas('settings', [
            'key'   => 'tempo:attributes:group',
            'value' => $attributes
        ]);
    }

    /**
     * @test
     */
    public function itListsAttributes()
    {
        $this->assertIsArray($this->repository->listAttributes());
    }

    /**
     * @test
     */
    public function itMapsToAttributeModel()
    {
        $attributes = $this->repository->listAttributes();
        $this->assertInstanceOf(Attribute::class, array_shift($attributes));
    }

    private function insertAttribute($json, $group = 'default')
    {
        $this->db->saveSetting('tempo:attributes:' . $group, $json);
    }
}
