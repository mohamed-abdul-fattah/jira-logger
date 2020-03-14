<?php

namespace App\Repositories;

/**
 * Class PlatformRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
abstract class PlatformRepository extends Repository
{
    /**
     * Get Jira platform URI
     *
     * @return string|null
     */
    public function getPlatformUri()
    {
        return $this->db->getSetting('platform_uri');
    }

    /**
     * Save authentication session into database
     *
     * @param string $sessionId
     */
    abstract public function saveSession(string $sessionId): void;

    /**
     * Get saved sessionId from database
     *
     * @return string|null
     */
    abstract public function getSession();
}
