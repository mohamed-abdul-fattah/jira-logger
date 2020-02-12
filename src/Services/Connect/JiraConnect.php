<?php

namespace App\Services\Connect;

use Exception;
use App\Entities\Jira;
use App\Commands\Command;
use App\Http\IRequestDispatcher;
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
    private $repo;

    /**
     * JiraConnect constructor.
     */
    public function __construct()
    {
        $this->repo     = new JiraRepository;
        $this->platform = new Jira;
        $this->platform->setPlatformUri($this->repo->getPlatformUri());
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
        if (! $this->dispatcher) {
            throw new ConnectionException('Dispatcher not found!', Command::EXIT_FAILURE);
        }

        try {
            $res = $this->dispatcher->setBaseUri($this->platform->getPlatformUri())
                                    ->postJson(
                $this->platform->getAuthUri(),
                [
                    'username' => $username,
                    'password' => $password,
                ]
            );
            $info = $res->body();
            $this->repo->saveSession($info->session->value);
        } catch (Exception $e) {
            throw new ConnectionException($e->getMessage(), Command::EXIT_FAILURE);
        }
    }
}
