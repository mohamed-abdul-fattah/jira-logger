<?php

namespace Tests\Unit\Utils;

use App\Utils\Str;
use Tests\TestCase;

/**
 * Class StrTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class StrTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function snakeCaseToCamelCase()
    {
        $str = Str::toCamelCase('snake_case');

        $this->assertSame('snakeCase', $str);
    }

    public function snakeCaseToPascalCase()
    {
        $str = Str::toPascalCase('snake_case');

        $this->assertSame('SnakeCase', $str);
    }
}
