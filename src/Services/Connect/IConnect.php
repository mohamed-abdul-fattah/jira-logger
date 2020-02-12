<?php

namespace App\Services\Connect;

use App\Entities\Platform;
use App\Http\IRequestDispatcher;

/**
 * Interface IConnect
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
interface IConnect
{
    /**
     * Set request dispatcher to connect over Http
     *
     * @param  IRequestDispatcher $requestDispatcher
     * @return $this
     */
    public function setDispatcher(IRequestDispatcher $requestDispatcher);

    /**
     * Authenticate to platform (Jira, TFS, ...)
     *
     * @param  string $username
     * @param  string $password
     * @return mixed
     */
    public function connect(string $username, string $password);
}
