<?php

namespace App\Services\Connect;

use App\Repositories\JiraRepository;
use Exception;
use App\Entities\Jira;
use App\Commands\Command;
use App\Http\IRequestDispatcher;
use App\Exceptions\RunTimeException;
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
     * JiraConnect constructor.
     */
    public function __construct()
    {
        $repo           = new JiraRepository;
        $this->platform = new Jira;
        $this->platform->setPlatformUri($repo->getPlatformUri());
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
     * @return array|void|null
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
            return $res->body();
        } catch (Exception $e) {
            throw new ConnectionException($e->getMessage(), Command::EXIT_FAILURE);
        }
    }
}
