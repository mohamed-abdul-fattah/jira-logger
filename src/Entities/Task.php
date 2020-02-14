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
     * Sync status codes
     */
    const NOT_SYNCED   = 0;
    const SYNC_SUCCEED = 1;
    const SYNC_FAILED  = 2;

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
     * Get task log ID
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set task log ID
     *
     * @param string $taskId
     */
    public function setTaskId(string $taskId)
    {
        $this->taskId = $taskId;
    }

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
     * @return string
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param string|DateTime $date
     */
    public function setEndedAt($date)
    {
        $this->endedAt = $date;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
