<?php

namespace App\Repositories;

use App\Exceptions\RunTimeException;

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
            throw new RunTimeException('There is a running log already! Run `log:abort` or `log:stop`, then try again');
        }

        $this->db->insert('logs', [
            'task_id'     => $taskId,
            'started_at'  => date("Y-m-d {$time}"),
            'description' => $desc,
        ]);
    }
}
