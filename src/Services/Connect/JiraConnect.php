<?php

namespace App\Services\Connect;

use Exception;
use App\Entities\Task;
use App\Entities\Jira;
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
     * Sync completed tasks to Jira as worklog
     *
     * @return array
     */
    public function sync()
    {
        $this->validateDispatcherExistence();
        $tasks = $this->tasksRepository->getUnSyncedLogs();

        $response = [];
        foreach ($tasks as $task) {
            $response[] = $this->syncLog($task);
        }

        return $response;
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
     * @param  Task $task
     * @return array
     */
    private function syncLog(Task $task)
    {
        return [];
    }
}
