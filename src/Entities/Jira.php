<?php

namespace App\Entities;

/**
 * Class Jira
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Jira extends Platform
{
    /**
     * Get Jira server base URI
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        // TODO: Get Jira server URI from env or setup command
        return '';
    }

    /**
     * Get Jira authentication URI
     *
     * @return string
     */
    public function getAuthUri(): string
    {
        return '/rest/auth/latest/session';
    }
}
