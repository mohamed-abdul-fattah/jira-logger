<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

/**
 * Class IntegrationTestCase
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class IntegrationTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new Application;
    }
}
