<?php

namespace App\Http;

/**
 * Class TestRequest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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

    public function setBaseUri($baseUri)
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
        // Workaround until DI is introduced
        if (empty($this->baseUri)) {
            $response = new MockResponse('[{"name": "0.2.0"}]');
        } else {
            $response = new MockResponse('{"session": {"value": "sessionId"}}');
        }

        return new Response($response);
    }

    public function setBasicAuth(string $base64): void
    {
        //
    }

    public function revokeAuthentication()
    {
        //
    }
}
