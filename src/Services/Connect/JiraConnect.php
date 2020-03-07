<?php

namespace App\Services\Connect;

use Exception;
use PDOException;
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
     * Github releases/tags URL
     */
    const TAGS_URL       = 'https://api.github.com/repos/mohamed-abdul-fattah/jira-logger/tags';
    const GITHUB_VERSION = 'application/vnd.github.v3+json';

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
        try {
            $this->platform->setPlatformUri($this->jiraRepository->getPlatformUri());
        } catch (PDOException $e) {
            throw new ConnectionException('Cannot get platform URI! Please, run `setup` command');
        }

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
            'reason' => null,
        ];
    }

    /**
     * Check whether the platform URI connects platform server or not
     *
     * @return void
     * @throws ConnectionException
     */
    public function checkPlatformConnection(): void
    {
        try {
            $this->dispatcher->getJson($this->platform->getProfileUri());
        } catch (Exception $e) {
            if ($e->getCode() === IResponse::HTTP_NOT_FOUND) {
                throw new ConnectionException(
                    'Cannot connect to Jira server. Please, re-run `setup` with proper platform URI'
                );
            } elseif ($e->getCode() === IResponse::HTTP_UNAUTHENTICATED
                   || $e->getCode() === IResponse::HTTP_UNAUTHORIZED
            ) {
                throw new ConnectionException(
                    'Invalid credentials. Please, run `connect` command to login to Jira'
                );
            } else {
                throw new ConnectionException($e->getMessage(), $e->getCode());
            }
        }
    }

    /**
     * Check whether there are a new version released or not
     *
     * @return string
     */
    public function checkUpdates(): string
    {
        // Unset base URI to request an external URI
        $this->dispatcher->setBaseUri(null);
        /** @var IResponse $response */
        $response = $this->dispatcher->getJson(
            self::TAGS_URL,
            [],
            ['Accept' => self::GITHUB_VERSION]
        );

        $versions = array_column($response->body(), 'name');
        $release  = end($versions);
        if (version_compare($release, APP_VERSION, '>')) {
            return $release;
        }

        return APP_VERSION;
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
