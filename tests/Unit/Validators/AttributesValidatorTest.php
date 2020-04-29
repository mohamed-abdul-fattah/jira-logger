<?php

namespace Tests\Unit\Validators;

use App\Services\Validators\Tempo\AttributesValidator;
use Tests\TestCase;
use App\Exceptions\RunTimeException;

/**
 * Class AttributesValidatorTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class AttributesValidatorTest extends TestCase
{
    public function provider()
    {
        return [
            ['{"key": "value"}', true],
            ['[{}]', true],
            ['{"key": 1}', true],
            ['{"nested": {"another": {"key": "value"}}}', true],
            ['string', false],
            ['{js_key: js_value}', false],
            ['', false],
            [' ', false],
            ['{"key": "missing"', false],
        ];
    }

    /**
     * @param string $json
     * @param bool   $isValid
     * @test
     * @dataProvider provider
     */
    public function itValidatesAttributes($json, $isValid)
    {
        if (false === $isValid) {
            $this->expectException(RunTimeException::class);
            $this->expectExceptionMessage('Invalid JSON attributes!');
        }

        $validator = new AttributesValidator;
        $validator->isJson($json);

        $this->assertTrue(true);
    }
}
