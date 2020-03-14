<?php

namespace App\Entities;

/**
 * Class Entity
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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
        $prop = pascalCase($name);
        if (method_exists(static::class, "set{$prop}")) {
            call_user_func_array([$this, "set{$prop}"], [$value]);
        }
    }
}
