<?php

namespace App\Http;

/**
 * Class TestRequest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class TestRequest implements IRequestDispatcher
{
    const HTTP_GET  = 'GET';
    const HTTP_POST = 'POST';

    public function getJson(string $uri, array $params = [], array $headers = [])
    {
        return $this->json(self::HTTP_GET, $uri, $params, $headers);
    }

    public function postJson(string $uri, array $params = [], array $headers = [])
    {
        return $this->json(self::HTTP_POST, $uri, $params, $headers);
    }

    public function setBaseUri(string $baseUri)
    {
        return $this;
    }

    public function setSessionId($sessionId): void
    {
        //
    }

    public function json(
        string $method,
        string $uri,
        array $params = [],
        array $headers = []
    ) {
        $response = new MockResponse();

        return new Response($response);
    }
}
