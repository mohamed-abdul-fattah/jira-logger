<?php

namespace App\Http;

/**
 * Interface IRequestDispatcher
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
interface IRequestDispatcher
{
    /**
     * Set base URI for dispatcher
     *
     * @param  string $baseUri
     * @return $this
     */
    public function setBaseUri(string $baseUri);

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
