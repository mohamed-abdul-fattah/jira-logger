<?php

namespace App;

use Exception;
use App\Config\Config;
use App\Exceptions\RunTimeException;
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

    /**
     * @throws RunTimeException
     * @throws Exception
     */
    public function run()
    {
        $this->init();

        foreach ($this->collector->getCollection() as $class) {
            $this->app->add(IoC::resolve($class));
        }

        $this->app->run();
    }

    /**
     * Init dependencies container
     */
    private function init()
    {
        foreach (Config::get('manifesto') as $abstract => $concrete) {
            IoC::inject($abstract, $concrete);
        }
    }
}
