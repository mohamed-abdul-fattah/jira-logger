<?php

namespace App\Entities\Tempo;

use App\Services\Json;
use App\Entities\Entity;

/**
 * Class Attribute
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class Attribute extends Entity
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $attributes;

    /**
     * Set attribute key
     * Used by database mapper
     *
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * Get attribute group name
     *
     * @return string
     */
    public function getGroup()
    {
        preg_match('/^tempo:attributes:(.+)$/', $this->key, $matches);

        return $matches[1];
    }

    /**
     * Set JSON attributes
     *
     * @param string $attributes
     */
    public function setValue(string $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get attributes decoded
     *
     * @return array
     */
    public function getAttributes()
    {
        return Json::decode($this->attributes, true);
    }
}
