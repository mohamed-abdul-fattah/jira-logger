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
     */
    public function saveAttributes(string $attributes)
    {
        $this->db->saveSetting('tempo:attributes', $attributes);
    }
}
