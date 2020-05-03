<?php

namespace App\Services\Validators;

use App\Exceptions\RunTimeException;

/**
 * Class StartValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class StartValidator
{
    /**
     * @var string
     */
    private $taskId;

    /**
     * @var string
     */
    private $time;

    /**
     * @var string
     */
    private $description;

    /**
     * StartValidator constructor.
     *
     * @param string $taskId
     * @param string $time
     * @param string $description
     */
    public function __construct($taskId, $time, $description)
    {
        $this->taskId      = $taskId;
        $this->time        = $time;
        $this->description = $description;
    }

    /**
     * Validate start command args and options
     *
     * @throws RunTimeException
     */
    public function validate()
    {
        $this->validateTaskId();
        $this->validateTime();
        $this->validateDescription();
    }

    /**
     * @return void
     * @throws RunTimeException
     */
    private function validateTaskId()
    {
        if (empty($this->taskId)) {
            throw new RunTimeException('Task id cannot be empty');
        } elseif (! is_string($this->taskId)) {
            throw new RunTimeException('Task id must be string');
        }
    }

    /**
     * @return void
     * @throws RunTimeException
     */
    private function validateTime()
    {
        if (isset($this->time)) {
            if (! is_string($this->time)) {
                throw new RunTimeException('Time must be a string');
            } elseif (! preg_match('/^\d{2}:\d{2}/', $this->time)) {
                throw new RunTimeException('Time must be in hh:ii format');
            }
        }
    }

    /**
     * @return void
     * @throws RunTimeException
     */
    private function validateDescription()
    {
        if (isset($this->description) && ! is_string($this->description)) {
            throw new RunTimeException('Description must be a string');
        }
    }
}
