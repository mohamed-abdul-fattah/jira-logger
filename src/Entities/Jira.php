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
     * @var string
     */
    protected $platformUri;

    /**
     * Set Jira base URI
     *
     * @param string $platformUri
     */
    public function setPlatformUri(string $platformUri)
    {
        $this->platformUri = $platformUri;
    }

    /**
     * Get Jira server base URI
     *
     * @return string
     */
    public function getPlatformUri(): string
    {
        return $this->platformUri;
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

    /**
     * Get add worklog Jira URI
     *
     * @param  string $taskId
     * @return string
     */
    public function getWorkLogUri(string $taskId): string
    {
        return "/rest/api/latest/issue/{$taskId}/worklog";
    }

    /**
     * Get my Jira profile URI
     *
     * @return string
     */
    public function getProfileUri(): string
    {
        return '/rest/api/latest/myself';
    }
}
