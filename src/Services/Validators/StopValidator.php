<?php

namespace App\Services\Validators;

use App\Exceptions\RunTimeException;

/**
 * Class StopValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class StopValidator
{
    /**
     * Ended at log time
     *
     * @var string
     */
    private $time;

    /**
     * StopValidator constructor.
     *
     * @param string|null $end
     */
    public function __construct($end)
    {
        $this->time = $end;
    }

    /**
     * Validate stop time input
     *
     * @throws RunTimeException
     */
    public function validate()
    {
        if (! empty($this->time)) {
            if (! is_string($this->time)) {
                throw new RunTimeException('Time must be a string');
            } elseif (! preg_match('/^\d{2}:\d{2}/', $this->time)) {
                throw new RunTimeException('Time must be in hh:ii format');
            }
        }
    }
}
