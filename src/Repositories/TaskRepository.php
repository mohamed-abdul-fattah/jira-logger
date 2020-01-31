<?php

namespace App\Repositories;

use App\Entities\Task;
use App\Exceptions\DbException;

/**
 * Class TaskRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class TaskRepository extends Repository
{
    /**
     * Start logging time
     *
     * @param string $taskId
     * @param string|null $time
     * @param string|null $desc
     */
    public function start($taskId, $time = null, $desc = null)
    {
        $time = (empty($time)) ? date('H:i') : $time;
        $desc = (empty($desc)) ? "Working on {$taskId} issue" : $desc;

        // Check whether is there a running log or not
        $logs = $this->db->count('logs', ['ended_at' => null]);
        if ($logs > 0) {
            throw new DbException('There is a running log already! Run `log:abort` or `log:stop`, then try again');
        }

        $this->db->beginTransaction();

        $this->db->insert('logs', [
            'task_id'     => $taskId,
            'started_at'  => date("Y-m-d {$time}"),
            'description' => $desc,
        ]);

        $this->db->commit();
    }

    /**
     * @param string $end
     * @param string $log
     * @param null $desc
     */
    public function stopLog($end, $log, $desc = null)
    {
        $this->db->beginTransaction();

        $args = (! empty($desc)) ? ['description' => $desc] : [];
        $args = array_merge($args, ['ended_at' => $end, 'log' => $log]);

        $this->db->update('logs', $args, ['ended_at' => null]);

        $this->db->commit();
    }

    /**
     * @return Task
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
        return $this->db->getOne('logs', $args, Task::class);
    }
}
