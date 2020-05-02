<?php

namespace App\Repositories;

use PDOException;
use App\Entities\Task;
use App\Exceptions\DbException;
use App\Repositories\Contracts\ITaskRepository;

/**
 * Class TaskRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class TaskRepository extends Repository implements ITaskRepository
{
    /**
     * Start logging time
     *
     * @param  string   $taskId
     * @param  string   $time
     * @param  string   $desc
     * @param  int|null $groupId Tempo group ID
     * @return void
     */
    public function startLog($taskId, $time, $desc, $groupId = null): void
    {
        $this->db->beginTransaction();

        try {
            $this->db->insert('logs', [
                'task_id'     => $taskId,
                'group_id'    => $groupId,
                'started_at'  => $time,
                'description' => $desc,
            ]);
        } catch (PDOException $e) {
            throw new DbException('Cannot save log record. Please, run `setup` command');
        }

        $this->db->commit();
    }

    /**
     * @param  string      $end
     * @param  string      $log
     * @param  string|null $desc
     * @param  int|null    $groupId
     * @return void
     */
    public function stopLog($end, $log, $desc = null, $groupId = null): void
    {
        $this->db->beginTransaction();

        $args = (! empty($desc)) ? ['description' => $desc] : [];
        $args = array_merge($args, ['ended_at' => $end, 'log' => $log]);

        if (! empty($groupId)) {
            $args['group_id'] = $groupId;
        }

        try {
            $this->db->update('logs', $args, ['ended_at' => null]);
        } catch (PDOException $e) {
            throw new DbException('Cannot save log record. Please, run `setup` command');
        }

        $this->db->commit();
    }

    /**
     * Abort the current running task log
     */
    public function abortLog(): void
    {
        $this->db->beginTransaction();

        try {
            $this->db->delete('logs', [
                'ended_at' => null,
                'log'      => null,
            ]);
        } catch (PDOException $e) {
            throw new DbException('Cannot abort. Please, run `setup` command');
        }

        $this->db->commit();
    }

    /**
     * Get the current running task
     *
     * @return Task|null
     */
    public function getRunningTask()
    {
        return $this->getTask(['ended_at' => null]);
    }

    /**
     * Get one task from DB based on the given conditions
     *
     * @param  array $args
     * @return Task
     */
    public function getTask(array $args)
    {
        try {
            return $this->db->first('logs', $args, Task::class);
        } catch (PDOException $e) {
            throw new DbException('Cannot query the database. Please, run `setup` command');
        }
    }

    /**
     * Get all un synced completed logs
     *
     * @return array
     */
    public function getUnSyncedLogs(): array
    {
        try {
            return $this->db->all('logs', [
                'synced'   => Task::NOT_SYNCED,
                'ended_at' => 'NOT NULL'
            ], Task::class);
        } catch (PDOException $e) {
            throw new DbException('Cannot query the database. Please, run `setup` command');
        }
    }

    /**
     * Update task with the given arguments
     *
     * @param  string $taskId
     * @param  array $args
     * @return void
     */
    public function updateTask(string $taskId, array $args)
    {
        $this->db->update('logs', $args, ['task_id' => $taskId]);
    }
}
