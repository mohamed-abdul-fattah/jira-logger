<?php

namespace Tests\Unit\Entities\Tempo;

use Tests\TestCase;
use App\Services\Json;
use App\Entities\Tempo\Attribute;

class AttributeTest extends TestCase
{
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var string
     */
    private $attributes;

    protected function setUp(): void
    {
        $this->attributes = '{"_Role_":{"name":"Role","value":"Developer"}}';
        $this->attribute  = new Attribute;
        $this->attribute->setKey('tempo:attributes:group');
        $this->attribute->setValue($this->attributes);
    }

    /**
     * @test
     */
    public function itGetsGroupName()
    {
        $this->assertSame('group', $this->attribute->getGroup());
    }

    /**
     * @test
     */
    public function itGetsAttributes()
    {
        $this->assertEquals(Json::decode($this->attributes, true), $this->attribute->getAttributes());
    }
}
