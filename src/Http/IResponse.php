<?php

namespace App\Http;

/**
 * Interface IResponse
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
interface IResponse
{
    /**
     * HTTP status codes
     */
    const HTTP_UNAUTHENTICATED = 401;
    const HTTP_UNAUTHORIZED    = 403;
    const HTTP_NOT_FOUND       = 404;

    /**
     * Get JSON decoded body
     *
     * @return array|null
     */
    public function body();

    /**
     * Get response HTTP status code
     *
     * @return int
     */
    public function getHttpStatus(): int;
}
