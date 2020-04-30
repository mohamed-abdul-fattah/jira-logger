<?php

namespace App\Repositories;

/**
 * Class TempoRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class TempoRepository extends Repository
{
    /**
     * Save Tempo attributes into database
     *
     * @param string $attributes
     * @param string $group
     */
    public function saveAttributes(string $attributes, $group = 'default')
    {
        $this->db->saveSetting('tempo:attributes:' . $group, $attributes);
    }
}
