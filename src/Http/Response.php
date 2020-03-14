<?php

namespace App\Http;

use App\Services\Json;
use App\Exceptions\JsonException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class Response implements IResponse
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Response constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Decode response JSON body
     *
     * @return mixed
     * @throws JsonException
     */
    public function body()
    {
        return Json::decode($this->response->getBody());
    }

    /**
     * Get response HTTP status code
     *
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->response->getStatusCode();
    }
}
