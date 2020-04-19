<?php

namespace App\Services;

use App\Entities\Task;
use App\Exceptions\RunTimeException;
use App\Repositories\Contracts\ITaskRepository;

/**
 * Class LogTimer
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class LogTimer
{
    /**
     * @var ITaskRepository
     */
    protected $taskRepo;

    /**
     * Convert time in seconds into more human readable
     *
     * @param  int $seconds
     * @return string
     */
    public static function timeForHuman(int $seconds): string
    {
        $minutes = $seconds / 60;
        if ($minutes < 60) {
            $minutes = floor($minutes);
            return "0h {$minutes}m";
        }

        $hours   = floor($minutes / 60);
        $minutes = floor($minutes % 60);
        return "{$hours}h {$minutes}m";
    }

    /**
     * LogTimer constructor.
     *
     * @param ITaskRepository $repository
     */
    public function __construct(ITaskRepository $repository)
    {
        $this->taskRepo = $repository;
    }

    /**
     * Start logging task timer
     *
     * @param string$taskId
     * @param string|null $time
     * @param string|null $desc
     */
    public function start($taskId, $time = null, $desc = null)
    {
        // Check whether is there a running log or not
        $task = $this->taskRepo->getRunningTask();
        if (! empty($task)) {
            throw new RunTimeException('There is a running log already! Run `log:abort` or `log:stop`, then try again');
        }

        $time = (empty($time)) ? date('Y-m-d H:i') : date("Y-m-d {$time}");
        $desc = (empty($desc)) ? "Working on {$taskId} issue" : $desc;

        $this->taskRepo->startLog($taskId, $time, $desc);
    }

    /**
     * Stop logging task time
     *
     * @param string|null $end
     * @param string|null $desc
     */
    public function stop($end = null, $desc = null)
    {
        // Check whether is there a running log or not
        /** @var Task $task */
        $task = $this->taskRepo->getRunningTask();
        if (empty($task)) {
            throw new RunTimeException('There is no running log! Run `log:start` to start logging timer');
        }

        $end = (! empty($end)) ? date("Y-m-d {$end}") : date('Y-m-d H:i');
        $log = $task->addLog($end);

        $this->taskRepo->stopLog($end, $log, $desc);
    }

    /**
     * Abort current running log
     */
    public function abort()
    {
        // Check whether is there a running log or not
        $task = $this->taskRepo->getRunningTask();
        if (empty($task)) {
            throw new RunTimeException('No running task log to abort!');
        }

        $this->taskRepo->abortLog();
    }

    /**
     * Get current log status, whether there is a running task or not
     * 1st return result indicates the un-synced tasks
     * 2nd return result is the total logged time
     * 3rd return result is the current running task
     *
     * @return array
     */
    public function getStatus()
    {
        $total = 0;
        $logs  = 0;
        foreach ($this->taskRepo->getUnSyncedLogs() as $log) {
            /** @var Task $log */
            $total += $log->logInSeconds();
            $logs++;
        }

        return [
            $logs,
            self::timeForHuman($total),
            $this->taskRepo->getRunningTask()
        ];
    }
}
