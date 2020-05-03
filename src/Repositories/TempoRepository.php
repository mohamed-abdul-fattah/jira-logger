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
     * Tempo worklog URI
     * @link https://www.tempo.io/server-api-documentation/timesheets#operation/createWorklog_1
     */
    const WORKLOG_URI = '/rest/tempo-timesheets/4/worklogs';

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
     * @throws DbException
     */
    public function getGroupId(string $group)
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

    /**
     * Get attributes decoded for the given group ID
     *
     * @param  int $groupId
     * @return array
     */
    public function getAttributesById(int $groupId)
    {
        /** @var Attribute $attribute */
        $attribute = $this->db->first('settings', ['id' => $groupId], Attribute::class);

        return $attribute->getAttributes();
    }

    /**
     * Get group name by ID
     *
     * @param  int $groupId
     * @return string
     */
    public function findGroupById(int $groupId)
    {
        /** @var Attribute $attribute */
        $attribute = $this->db->first('settings', ['id' => $groupId], Attribute::class);

        return $attribute->getGroup();
    }
}
