<?php

namespace App\Repositories;

/**
 * Class JiraRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class JiraRepository extends PlatformRepository
{
    /**
     * Save authentication session into database
     *
     * @param string $sessionId
     */
    public function saveSession(string $sessionId): void
    {
        $this->db->saveSetting('session_id', $sessionId);
    }

    /**
     * Get saved sessionId from database
     *
     * @return string|null
     */
    public function getSession()
    {
        return $this->db->getSetting('session_id');
    }
}
