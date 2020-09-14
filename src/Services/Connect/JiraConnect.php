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
    protected $jiraRepository;

    /**
     * @var TaskRepository
     */
    protected $tasksRepository;

    /**
     * JiraConnect constructor.
     *
     * @param JiraRepository $jiraRepository
     * @param TaskRepository $tasksRepository
     * @param Jira           $platform
     */
    public function __construct(JiraRepository $jiraRepository, TaskRepository $tasksRepository, Jira $platform)
    {
        $this->jiraRepository  = $jiraRepository;
        $this->tasksRepository = $tasksRepository;
        $this->platform        = $platform;
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
            $this->jiraRepository->saveUsername($username);
        } catch (Exception $e) {
            throw new ConnectionException($e->getMessage());
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
                $this->getWorkLogUri($task),
                $this->getPayload($task)
            );

            $this->tasksRepository->updateTask(
                $task->getTaskId(),
                ['synced' => Task::SYNC_SUCCEED]
            );

            $sync   = Task::SYNC_SUCCEED;
            $reason = null;
        } catch (Exception $e) {
            $this->tasksRepository->updateTask(
                $task->getTaskId(),
                ['synced' => Task::SYNC_FAILED]
            );

            $sync   = Task::SYNC_FAILED;
            $reason = $this->getSyncLogMsg($e->getCode());
        }

        return [
            'taskId' => $task->getTaskId(),
            'sync'   => $sync,
            'reason' => $reason,
            'logged' => $task->getLog(),
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
        $response = $this->dispatcher->getJson(
            self::TAGS_URL,
            [],
            ['Accept' => self::GITHUB_VERSION]
        );

        $versions = array_column($response->body(), 'name');
        $release  = array_shift($versions);
        if (version_compare($release, APP_VERSION, '>')) {
            return $release;
        }

        return APP_VERSION;
    }

    /**
     * Save encoded base64 for basic authentication
     *
     * @param string $username
     * @param string $apiToken
     * @see https://developer.atlassian.com/cloud/jira/platform/basic-auth-for-rest-apis/
     */
    public function saveBasicAuth(string $username, string $apiToken): void
    {
        try {
            $this->jiraRepository->saveUsername($username);
            $this->jiraRepository->saveBasicAuth($username, $apiToken);
        } catch (Exception $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    /**
     * @param  int $errorCode
     * @return string
     */
    protected function getSyncLogMsg(int $errorCode): string
    {
        if ($errorCode === IResponse::HTTP_NOT_FOUND) {
            return 'Issue Does Not Exist';
        } elseif ($errorCode === IResponse::HTTP_UNAUTHORIZED) {
            return 'You do not have the permission to see the specified issue';
        } else {
            return 'Cannot add worklog to this issue';
        }
    }

    /**
     * @throws ConnectionException
     */
    protected function validateDispatcherExistence(): void
    {
        if (! $this->dispatcher) {
            throw new ConnectionException('Dispatcher not found!', Command::EXIT_FAILURE);
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
        return [
            'comment'          => $task->getDescription(),
            'timeSpentSeconds' => $task->logInSeconds(),
        ];
    }

    /**
     * @param  Task $task
     * @return string
     */
    protected function getWorkLogUri(Task $task): string
    {
        return $this->platform->getWorkLogUri($task->getTaskId());
    }
}
