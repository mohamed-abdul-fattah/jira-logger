<?php

namespace App\Entities;

/**
 * Class Platform
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
abstract class Platform extends Entity
{
    /**
     * Set platform base URI
     *
     * @param string $baseUri
     */
    abstract public function setPlatformUri(string $baseUri);

    /**
     * Get platform base server URI
     *
     * @return string
     */
    abstract public function getPlatformUri(): string;

    /**
     * Get platform authentication URI
     *
     * @return string
     */
    abstract public function getAuthUri(): string;

    /**
     * Get add worklog platform URI
     *
     * @param  string $taskId
     * @return string
     */
    abstract public function getWorkLogUri(string $taskId): string;
}
