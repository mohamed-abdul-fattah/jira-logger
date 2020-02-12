<?php

namespace App\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MockResponse
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class MockResponse implements ResponseInterface
{
    public function getStatusCode()
    {
        // Placeholder
    }

    public function getReasonPhrase()
    {
        // Placeholder
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        // Placeholder
    }

    public function withAddedHeader($name, $value)
    {
        // Placeholder
    }

    public function getProtocolVersion()
    {
        // Placeholder
    }

    public function getHeaderLine($name)
    {
        // Placeholder
    }

    public function withProtocolVersion($version)
    {
        // Placeholder
    }

    public function getHeader($name)
    {
        // Placeholder
    }

    public function withoutHeader($name)
    {
        // Placeholder
    }

    public function getHeaders()
    {
        // Placeholder
    }

    public function hasHeader($name)
    {
        // Placeholder
    }

    public function withHeader($name, $value)
    {
        // Placeholder
    }

    public function getBody()
    {
        return '{"session": {"value": "sessionId"}}';
    }

    public function withBody(StreamInterface $body)
    {
        // Placeholder
    }
}
