<?php

namespace App\Services;

use App\Entities\Task;
use App\Exceptions\RunTimeException;
use App\Repositories\TaskRepository;

/**
 * Class LogTimer
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class LogTimer
{
    /**
     * @var TaskRepository
     */
    protected $taskRepo;

    /**
     * LogTimer constructor.
     */
    public function __construct()
    {
        $this->taskRepo = new TaskRepository;
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

        $time = (empty($time)) ? date('H:i') : $time;
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
}
