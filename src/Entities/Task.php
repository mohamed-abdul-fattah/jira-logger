<?php

namespace App\Entities;

use DateTime;
use App\Exceptions\EntityException;

/**
 * Class Task
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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
     * @var int
     */
    protected $groupId;

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
     * @var int
     */
    protected $synced;

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
     * Set task tempo group ID
     *
     * @param int|null $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
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
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $log
     */
    public function setLog(string $log)
    {
        $this->log = $log;
    }

    /**
     * Get task work log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @return int
     */
    public function getSynced()
    {
        return $this->synced;
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

    /**
     * Convert log to seconds
     *
     * @return int
     */
    public function logInSeconds()
    {
        if (empty($this->log)) {
            return 0;
        }

        preg_match('/(.+)h.*/', $this->log, $matches);
        $hour = $matches[1];

        preg_match('/^.+h\s(.+)m.*/', $this->log, $matches);
        $minutes = $matches[1];

        return (int) $hour * 60 * 60 + (int) $minutes * 60;
    }
}
