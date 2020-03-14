<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Json;
use App\Exceptions\JsonException;

/**
 * Class JsonTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class JsonTest extends TestCase
{
    public function invalidJsonData()
    {
        $msg = "Message doesn't contain valid JSON: Syntax error";
        return [
            ['hello world', $msg],
            ['{hello world}', $msg],
            ['"username": ', $msg],
            ['{"username": }', $msg],
            [': "username"', $msg],
            ['{: "username"}', $msg],
        ];
    }

    /**
     * @param mixed $json
     * @param string $expectedMsg
     * @test
     * @dataProvider invalidJsonData
     */
    public function cannotDecodeJson($json, $expectedMsg)
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage($expectedMsg);

        Json::decode($json);
    }

    /**
     * @test
     */
    public function validJson()
    {
        $obj = Json::decode('{"username": "john doe"}');

        $this->assertObjectHasAttribute('username', $obj);
    }
}
