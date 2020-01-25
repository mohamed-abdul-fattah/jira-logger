<?php

namespace App\Entities;

/**
 * Class Platform
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
abstract class Platform extends Entity
{
    /**
     * Get platform base server URI
     *
     * @return string
     */
    public abstract function getBaseUri(): string;

    /**
     * Get platform authentication URI
     *
     * @return string
     */
    public abstract function getAuthUri(): string;
}
