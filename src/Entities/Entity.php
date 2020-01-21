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
     * @param  int $id
     * @return int
     */
    public function setId(int $id): int
    {
        $this->id = $id;

        return $this->id;
    }
}
