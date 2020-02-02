<?php

namespace App\Entities;

use DateTime;
use App\Exceptions\EntityException;

/**
 * Class Task
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Task extends Entity
{
    /**
     * @var string
     */
    protected $taskId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $startedAt;

    /**
     * @var string
     */
    protected $endedAt;

    /**
     * @var string
     */
    protected $log;

    /**
     * Set task started at log time
     *
     * @param string|DateTime $date
     */
    public function setStartedAt($date)
    {
        $this->startedAt = $date;
    }

    /**
     * Log time taken working on an issue
     *
     * @param  string $end
     * @return false|string
     */
    public function addLog(string $end)
    {
        $start = date_create($this->startedAt);
        $end   = date_create($end);

        if ($end <= $start) {
            throw new EntityException('Stop time must be greater than start time');
        }

        $diff = date_diff($start, $end);
        return $this->log = $diff->format('%hh %im');
    }
}
