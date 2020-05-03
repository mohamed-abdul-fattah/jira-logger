<?php

namespace App\Services\Connect;

use App\Entities\Jira;
use App\Entities\Task;
use App\Exceptions\DbException;
use App\Exceptions\RunTimeException;
use App\Repositories\JiraRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TempoRepository;

/**
 * Class TempoConnect
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class TempoConnect extends JiraConnect
{
    /**
     * @var TempoRepository
     */
    private $tempoRepository;

    /**
     * TempoConnect constructor.
     *
     * @param TempoRepository $tempoRepository
     * @param JiraRepository  $jiraRepository
     * @param TaskRepository  $tasksRepository
     * @param Jira            $platform
     */
    public function __construct(
        TempoRepository $tempoRepository,
        JiraRepository $jiraRepository,
        TaskRepository $tasksRepository,
        Jira $platform
    ) {
        parent::__construct($jiraRepository, $tasksRepository, $platform);

        $this->tempoRepository = $tempoRepository;
    }

    /**
     * Tempo attributes group
     *
     * @var string
     */
    private $group;

    /**
     * Sync single task with Tempo worklog
     * @link https://www.tempo.io/server-api-documentation/timesheets#operation/createWorklog_1
     *
     * @param  Task   $task
     * @param  string $tempoGroup
     * @return array
     */
    public function syncTempoLog(Task $task, string $tempoGroup): array
    {
        $this->group   = $tempoGroup;
        $info          = $this->syncLog($task);
        $info['group'] = $group = ! empty($task->getGroupId())
            ? $this->tempoRepository->findGroupById($task->getGroupId())
            : $tempoGroup;

        return $info;
    }

    /**
     * Check whether the Tempo default group is set or not
     *
     * @throws RunTimeException
     */
    public function validateDefaultGroupExistence()
    {
        try {
            $this->tempoRepository->getGroupId('default');
        } catch (DbException $e) {
            throw new RunTimeException('Default tempo group is not found!. Please, run `tempo:attributes`');
        }
    }

    /**
     * Get worklog request payload
     *
     * @param  Task $task
     * @return array
     */
    protected function getPayload(Task $task): array
    {
        $group = ! empty($task->getGroupId()) ? $task->getGroupId() : $this->tempoRepository->getGroupId($this->group);
        return [
            'worker'           => $this->jiraRepository->getUsername(),
            'comment'          => $task->getDescription(),
            'started'          => date('Y-m-d', strtotime($task->getStartedAt())),
            'endDate'          => date('Y-m-d', strtotime($task->getEndedAt())),
            'timeSpentSeconds' => $task->logInSeconds(),
            'originTaskId'     => $task->getTaskId(),
            'attributes'       => $this->tempoRepository->getAttributesById($group),
        ];
    }

    /**
     * @param  Task $task
     * @return string
     */
    protected function getWorkLogUri(Task $task): string
    {
        return TempoRepository::WORKLOG_URI;
    }
}
