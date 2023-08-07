<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Exceptions\RunTimeException;
use App\Services\Validators\StartValidator;

/**
 * Class StartValidatorTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class StartValidatorTest extends TestCase
{
    public function invalidDataProvider()
    {
        return [
            [null, null, null, 'Task id cannot be empty'],
            ['', null, null, 'Task id cannot be empty'],
            [[], null, null, 'Task id cannot be empty'],
            [1, null, null, 'Task id must be string'],
            [[1], null, null, 'Task id must be string'],
            ['Issue-8', 1, null, 'Time must be a string'],
            ['Issue-8', [1], null, 'Time must be a string'],
            ['Issue-8', 'string', null, 'Time must be in hh:ii format'],
            ['Issue-8', '1:01', null, 'Time must be in hh:ii format'],
            ['Issue-8', '01:0', null, 'Time must be in hh:ii format'],
            ['Issue-53', '10:10AM', null, 'Time must be in hh:ii format'],
            ['Issue-8', null, 1, 'Description must be a string'],
            ['Issue-8', null, [1], 'Description must be a string'],
        ];
    }

    /**
     * @param mixed $taskId
     * @param mixed $time
     * @param mixed $description
     * @param string $exceptionMsg
     * @test
     * @dataProvider invalidDataProvider
     */
    public function invalidData($taskId, $time, $description, $exceptionMsg)
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage($exceptionMsg);

        $validator = new StartValidator($taskId, $time, $description);
        $validator->validate();
    }

    public function validDataProvider()
    {
        return [
            ['Issue-8', null, null],
            ['Issue-8', '00:00', null],
            ['Issue-8', null, 'Hello world'],
        ];
    }

    /**
     * @param mixed $taskId
     * @param mixed $time
     * @param mixed $description
     * @test
     * @dataProvider validDataProvider
     */
    public function validStartLogData($taskId, $time, $description)
    {
        $validator = new StartValidator($taskId, $time, $description);
        $validator->validate();

        $this->assertTrue(true);
    }
}
