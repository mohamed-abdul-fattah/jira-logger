<?php

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Response
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
}
