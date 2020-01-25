<?php

namespace App\Commands;

use App\Services\Http\Request;
use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Class Command
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
abstract class Command extends BaseCommand
{
    /**
     * Exit success code
     */
    const EXIT_SUCCESS = 0;

    /**
     * Http request object
     *
     * @var Request
     */
    protected $request;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        parent::__construct(null);

        $this->request = new Request;
    }
}