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
 * @since  1.0.0
 */
class TaskRepository extends Repository implements ITaskRepository
{
    /**
     * Start logging time
     *
     * @param  string $taskId
     * @param  string $time
     * @param  string $desc
     * @return void
     */
    public function startLog($taskId, $time, $desc): void
    {
        $this->db->beginTransaction();

        try {
            $this->db->insert('logs', [
                'task_id'     => $taskId,
                'started_at'  => $time,
                'description' => $desc,
            ]);
        } catch (PDOException $e) {
            throw new DbException('Cannot save log record. Please, run `setup` command');
        }

        $this->db->commit();
    }

    /**
     * @param  string $end
     * @param  string $log
     * @param  string|null $desc
     * @return void
     */
    public function stopLog($end, $log, $desc = null): void
    {
        $this->db->beginTransaction();

        $args = (! empty($desc)) ? ['description' => $desc] : [];
        $args = array_merge($args, ['ended_at' => $end, 'log' => $log]);

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
            return $this->db->getOne('logs', $args, Task::class);
        } catch (PDOException $e) {
            throw new DbException('Cannot query the database. Please, run `setup` command');
        }
    }
}
