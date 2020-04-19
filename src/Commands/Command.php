<?php

namespace App\Commands;

use Exception;
use App\Http\Request;
use App\Config\Config;
use App\Repositories\SetupRepository;
use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Class Command
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
abstract class Command extends BaseCommand
{
    /**
     * Exit success code
     */
    const EXIT_SUCCESS = 0;

    /**
     * Exit failure code
     */
    const EXIT_FAILURE = 1;

    /**
     * Http request object
     *
     * @var Request
     */
    protected $request;

    /**
     * @var SetupRepository
     */
    protected $setupRepo;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        parent::__construct(null);

        /** @var Request request */
        $this->request   = Config::getDispatcher();
        $this->setupRepo = new SetupRepository;
        $this->bootstrap();
    }

    /**
     * Bootstrap application with configuration
     */
    private function bootstrap()
    {
        /** Configure timezone */
        try {
            date_default_timezone_set($this->setupRepo->getTimezone());
        } catch (Exception $e) {
            // Log error with issue https://github.com/mohamed-abdul-fattah/jira-logger/issues/11
        }
    }
}
