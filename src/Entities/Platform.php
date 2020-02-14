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
     * Set platform base URI
     *
     * @param string $baseUri
     */
    public abstract function setPlatformUri(string $baseUri);

    /**
     * Get platform base server URI
     *
     * @return string
     */
    public abstract function getPlatformUri(): string;

    /**
     * Get platform authentication URI
     *
     * @return string
     */
    public abstract function getAuthUri(): string;

    /**
     * Get add worklog platform URI
     *
     * @param  string $taskId
     * @return string
     */
    public abstract function getWorkLogUri(string $taskId): string;
}
