<?php

namespace App\Repositories\Contracts;

use App\Entities\Task;

/**
 * Interface ITaskRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
interface ITaskRepository
{
    /**
     * Insert a running log with start time
     *
     * @param  string $taskId
     * @param  string $time
     * @param  string $desc
     * @return void
     */
    public function startLog($taskId, $time, $desc): void;

    /**
     * Update running task with end time
     *
     * @param  string $end
     * @param  string $log
     * @param  string|null $desc
     * @return void
     */
    public function stopLog($end, $log, $desc = null): void;

    /**
     * Delete the current running task log
     *
     * @return void
     */
    public function abortLog(): void;

    /**
     * @return Task|null
     */
    public function getRunningTask();

    /**
     * Get the un-synced yet logs count
     *
     * @return int
     */
    public function countUnSyncedLogs(): int;

    /**
     * Update task with the given arguments
     *
     * @param  string $taskId
     * @param  array $args
     * @return void
     */
    public function updateTask(string $taskId, array $args);
}
