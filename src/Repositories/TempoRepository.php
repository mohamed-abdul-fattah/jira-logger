<?php

namespace App\Repositories;

use App\Entities\Tempo\Attribute;
use App\Exceptions\DbException;

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

    /**
     * Lists all saved tempo attributes
     *
     * @return Attribute[]
     */
    public function listAttributes()
    {
        return $this->db->all('settings', [
            'key' => ['LIKE', 'tempo:attributes:%']
        ], Attribute::class);
    }

    /**
     * Get given tempo group ID
     *
     * @param  string $group
     * @return int
     */
    public function getGroup(string $group)
    {
        /** @var Attribute $attribute */
        $attribute = $this->db->first('settings', [
            'key' => 'tempo:attributes:' . $group
        ], Attribute::class);

        if (empty($attribute)) {
            throw new DbException('Group is not found!');
        }

        return $attribute->getId();
    }
}
