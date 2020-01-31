<?php

namespace App\Entities;

/**
 * Class Entity
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
abstract class Entity
{
    /**
     * Entity identifier
     *
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $prop = camelCase($name);
        if (property_exists(static::class, $prop)) {
            $this->{$prop} = $value;
        }
    }
}
