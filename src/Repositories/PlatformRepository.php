<?php

namespace App\Repositories;

/**
 * Class PlatformRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class PlatformRepository extends Repository
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
}
