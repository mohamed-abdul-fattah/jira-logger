<?php

namespace App\Http;

use Guzzle\Http\Client;

/**
 * Class Request
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Request
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'timeout'  => 30,
        ]);
    }
}
