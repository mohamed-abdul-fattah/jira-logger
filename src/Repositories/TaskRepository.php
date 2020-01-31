<?php

namespace App\Repositories;

use stdClass;
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
     * @param null $desc
     */
    public function stop($desc = null)
    {
        // Check whether is there a running log or not
        /** @var Task $task */
        $task = $this->getTask(['ended_at' => null]);
        if (empty($task)) {
            throw new DbException('There is no running log! Run `log:start` to start logging timer');
        }

        $this->db->beginTransaction();

        $end  = date('Y-m-d H:i');
        $args = (! empty($desc)) ? ['description' => $desc] : [];
        $args = array_merge($args, ['ended_at' => $end, 'log' => $task->addLog($end)]);

        $this->db->update('logs', $args, ['ended_at' => null]);

        $this->db->commit();
    }

    /**
     * Get one task from DB based on the given conditions
     *
     * @param  array $args
     * @return stdClass
     */
    public function getTask(array $args)
    {
        return $this->db->getOne('logs', $args, Task::class);
    }
}
