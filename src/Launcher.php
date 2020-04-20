<?php

namespace App;

use Symfony\Component\Console\Application;

/**
 * Class Launcher
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class Launcher
{
    /**
     * @var CommandCollector
     */
    private $collector;

    /**
     * @var Application
     */
    private $app;

    /**
     * Launcher constructor.
     *
     * @param string $appName
     * @param string $appVersion
     */
    public function __construct(string $appName, string $appVersion)
    {
        $this->app       = new Application($appName, $appVersion);
        $this->collector = new CommandCollector;
    }

    public function run()
    {
        // collect commands and resolve their deps
        // init
        $this->app->run();
    }
}
