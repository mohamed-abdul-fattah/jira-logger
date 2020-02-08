<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Exceptions\RunTimeException;
use App\Services\Validators\StopValidator;

/**
 * Class StopValidatorTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class StopValidatorTest extends TestCase
{
    public function invalidEndTimeData()
    {
        return [
            [1, 'Time must be a string'],
            [[1], 'Time must be a string'],
            ['string', 'Time must be in hh:ii format'],
            ['1:1', 'Time must be in hh:ii format'],
            ['1:01', 'Time must be in hh:ii format'],
            ['01:0', 'Time must be in hh:ii format'],
        ];
    }

    /**
     * @param mixed $time
     * @param string $expectedMsg
     * @test
     * @dataProvider invalidEndTimeData
     */
    public function invalidEndTime($time, $expectedMsg)
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage($expectedMsg);

        $validator = new StopValidator($time);
        $validator->validate();
    }

    public function validEndTime()
    {
        $validator = new StopValidator('00:00');
        $validator->validate();

        $this->assertTrue(true);
    }
}
