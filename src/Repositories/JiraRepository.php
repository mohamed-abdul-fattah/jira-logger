<?php

namespace App\Repositories;

/**
 * Class JiraRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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

    /**
     * Generate base64 combination of email and API token to be used for basic authentication
     *
     * @param string $username
     * @param string $secret
     */
    public function saveBasicAuth(string $username, string $secret): void
    {
        $this->db->saveSetting('basic_auth', base64_encode($username . ':' . $secret));
    }

    /**
     * Get basic authentication combination
     *
     * @return string
     */
    public function getBasicAuth()
    {
        return $this->db->getSetting('basic_auth');
    }
}
