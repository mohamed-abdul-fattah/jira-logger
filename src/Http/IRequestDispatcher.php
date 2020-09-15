<?php

namespace App\Http;

/**
 * Interface IRequestDispatcher
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
interface IRequestDispatcher
{
    /**
     * Set base URI for dispatcher
     *
     * @param  string|null $baseUri
     * @return $this
     */
    public function setBaseUri($baseUri);

    /**
     * Set saved session ID
     *
     * @param  string $sessionId
     * @return void
     */
    public function setSessionId($sessionId): void;

    /**
     * Set base64 combination for basic authentication authorization header
     *
     * @param string $base64
     */
    public function setBasicAuth(string $base64): void;

    /**
     * Dispatch GET request with Content-Type and Accept JSON
     *
     * @param  string $uri
     * @param  array $params
     * @param  array $headers
     * @return IResponse
     */
    public function getJson(string $uri, array $params = [], array $headers = []);

    /**
     * Dispatch POST request with Content-Type and Accept JSON
     *
     * @param  string $uri
     * @param  array $params
     * @param  array $headers
     * @return mixed
     */
    public function postJson(string $uri, array $params = [], array $headers = []);
}
