<?php

namespace App\Repositories\Contracts;

use App\Entities\Task;

/**
 * Interface ITaskRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
interface ITaskRepository
{
    /**
     * Insert a running log with start time
     *
     * @param  string   $taskId
     * @param  string   $time
     * @param  string   $desc
     * @param  int|null $groupId
     * @return void
     */
    public function startLog($taskId, $time, $desc, $groupId = null): void;

    /**
     * Update running task with end time
     *
     * @param  string      $end
     * @param  string      $log
     * @param  string|null $desc
     * @param  int|null    $groupId
     * @return void
     */
    public function stopLog($end, $log, $desc = null, $groupId = null): void;

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
     * Get all un synced completed logs
     *
     * @return array
     */
    public function getUnSyncedLogs(): array;

    /**
     * Update task with the given arguments
     *
     * @param  string $taskId
     * @param  array $args
     * @return void
     */
    public function updateTask(string $taskId, array $args);
}
