<?php

namespace App\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use App\Exceptions\ConnectionException;

/**
 * Class Request
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Request implements IRequestDispatcher
{
    /**
     * HTTP verbs
     */
    const HTTP_GET  = 'GET';
    const HTTP_POST = 'POST';

    /**
     * @var Client
     */
    protected $client;

    /**
     * HTTP request base URI
     *
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->client   = new Client([
            'timeout'   => 30,
            'cookies'   => true,
        ]);
    }

    /**
     * Set dispatcher base URI
     *
     * @param  string $baseUri
     * @return $this
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Set saved session ID
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Dispatch GET request with Content-Type and Accept JSON
     *
     * @param  string $uri
     * @param  array $params
     * @param  array $headers
     * @return mixed
     */
    public function getJson(string $uri, array $params = [], array $headers = [])
    {
        return $this->json(self::HTTP_GET, $uri, $params, $headers);
    }

    /**
     * Dispatch POST request with Content-Type and Accept JSON
     *
     * @param  string $uri
     * @param  array $params
     * @param  array $headers
     * @return mixed
     */
    public function postJson(string $uri, array $params = [], array $headers = [])
    {
        return $this->json(self::HTTP_POST, $uri, $params, $headers);
    }

    /**
     * Dispatch request with Content-Type and Accept JSON
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $params
     * @param  array $headers
     * @return mixed
     */
    public function json(
        string $method,
        string $uri,
        array $params = [],
        array $headers = []
    ) {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ]);

        if ($this->baseUri) {
            $uri = $this->unSlashUri($this->baseUri) . '/' . $this->unSlashUri($uri);
        }

        $jar = null;
        if (! empty($this->sessionId)) {
            $jar = new CookieJar;
            $jar = $jar->fromArray(['JSESSIONID' => $this->sessionId], 'jira.espace.ws');
        }

        try {
            $res = $this->client->request(
                $method,
                $uri,
                [
                    'headers' => $headers,
                    'json'    => $params,
                    'cookies' => $jar,
                ]
            );
        } catch (Exception $e) {
            if ($e->getCode() === 0) {
                throw new ConnectionException('Could not resolve host!. Please check your internet connection.', $e->getCode());
            } elseif ($e->getCode() === IResponse::HTTP_NOT_FOUND) {
                throw new ConnectionException('404 Not Found!. Please, re-run `setup` command with proper platform URI', $e->getCode());
            } elseif (
                $e->getCode() === IResponse::HTTP_UNAUTHENTICATED ||
                $e->getCode() === IResponse::HTTP_UNAUTHORIZED
            ) {
                throw new ConnectionException('Unauthorized!. Wrong username or password.', $e->getCode());
            } else {
                throw new ConnectionException($e->getMessage(), $e->getCode());
            }
        }

        return new Response($res);
    }

    /**
     * Strip slashes from the beginning and end of a URI
     *
     * @param  string $uri
     * @return string
     */
    protected function unSlashUri(string $uri)
    {
        $uri = ltrim($uri, "\/");
        return rtrim($uri, "\/");
    }
}
