<?php

namespace App\Services\Connect;

use Exception;
use App\Entities\Task;
use App\Entities\Jira;
use App\Http\IResponse;
use App\Commands\Command;
use App\Http\IRequestDispatcher;
use App\Repositories\TaskRepository;
use App\Repositories\JiraRepository;
use App\Exceptions\ConnectionException;

/**
 * Class JiraConnect
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class JiraConnect implements IConnect
{
    /**
     * @var Jira
     */
    protected $platform;

    /**
     * @var JiraRepository
     */
    private $jiraRepository;

    /**
     * @var TaskRepository
     */
    private $tasksRepository;

    /**
     * JiraConnect constructor.
     */
    public function __construct()
    {
        $this->jiraRepository  = new JiraRepository;
        $this->tasksRepository = new TaskRepository;
        $this->platform        = new Jira;
        $this->platform->setPlatformUri($this->jiraRepository->getPlatformUri());
    }

    /**
     * @var IRequestDispatcher
     */
    protected $dispatcher;

    /**
     * @param  IRequestDispatcher $requestDispatcher
     * @return $this
     */
    public function setDispatcher(IRequestDispatcher $requestDispatcher)
    {
        $this->dispatcher = $requestDispatcher;
        $this->dispatcher->setBaseUri($this->platform->getPlatformUri());

        return $this;
    }

    /**
     * Connect to Jira
     *
     * @param  string $username
     * @param  string $password
     */
    public function connect(string $username, string $password)
    {
        $this->validateDispatcherExistence();

        try {
            $res = $this->dispatcher->postJson(
                $this->platform->getAuthUri(),
                [
                    'username' => $username,
                    'password' => $password,
                ]
            );
            $info = $res->body();
            $this->jiraRepository->saveSession($info->session->value);
        } catch (Exception $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    /**
     * @throws ConnectionException
     */
    private function validateDispatcherExistence(): void
    {
        if (! $this->dispatcher) {
            throw new ConnectionException('Dispatcher not found!', Command::EXIT_FAILURE);
        }
    }

    /**
     * Sync single task with Jira worklog
     *
     * @param  Task $task
     * @return array
     */
    public function syncLog(Task $task): array
    {
        $this->validateDispatcherExistence();
        try {
            $this->dispatcher->postJson(
                $this->platform->getWorkLogUri($task->getTaskId()),
                [
                    'comment'          => $task->getDescription(),
                    'timeSpentSeconds' => $task->logInSeconds(),
                ]
            );
        } catch (Exception $e) {
            $this->tasksRepository->updateTask(
                $task->getTaskId(),
                ['synced' => Task::SYNC_FAILED]
            );

            return [
                'taskId' => $task->getTaskId(),
                'sync'   => Task::SYNC_FAILED,
                'reason' => $this->getSyncLogMsg($e->getCode()),
            ];
        }

        $this->tasksRepository->updateTask(
            $task->getTaskId(),
            ['synced' => Task::SYNC_SUCCEED]
        );
        return [
            'taskId' => $task->getTaskId(),
            'sync'   => Task::SYNC_SUCCEED,
        ];
    }

    /**
     * @param  int $errorCode
     * @return string
     */
    private function getSyncLogMsg(int $errorCode): string
    {
        if ($errorCode === IResponse::HTTP_NOT_FOUND) {
            return 'Issue Does Not Exist';
        } elseif ($errorCode === IResponse::HTTP_UNAUTHORIZED) {
            return 'You do not have the permission to see the specified issue';
        } else {
            return 'Cannot add worklog to this issue';
        }
    }
}
