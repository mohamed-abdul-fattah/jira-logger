<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Exceptions\RunTimeException;
use App\Services\Validators\SetupValidator;

/**
 * Class SetupValidatorTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SetupValidatorTest extends TestCase
{
    public function validationProvider()
    {
        return [
            [null, 'Platform URI cannot be empty.'],
            ['', 'Platform URI cannot be empty.'],
            [[], 'Platform URI cannot be empty.'],
            [[1], 'Platform URI must be a string.'],
            [1, 'Platform URI must be a string.'],
            ['jira.com', 'Platform URI must be in http(s)://example.domain/ format.'],
            ['www.jira.com', 'Platform URI must be in http(s)://example.domain/ format.'],
            ['http://jira.com', 'Platform URI must be in http(s)://example.domain/ format.'],
            ['https://jira.com', 'Platform URI must be in http(s)://example.domain/ format.'],
        ];
    }

    /**
     * @param mixed $uri
     * @param string $exceptionMsg
     * @test
     * @dataProvider validationProvider
     */
    public function invalidPlatformUri($uri, $exceptionMsg)
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage($exceptionMsg);

        $validator = new SetupValidator($uri);
        $validator->validate();
    }

    /**
     * @test
     */
    public function validPlatformUri()
    {
        $validator = new SetupValidator('http://jira.com/');
        $validator->validate();

        $validator = new SetupValidator('https://jira.com/');
        $validator->validate();

        $this->assertTrue(true);
    }
}
