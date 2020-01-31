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
