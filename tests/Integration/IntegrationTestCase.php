<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Persistence\DB;
use Symfony\Component\Console\Application;

/**
 * Class IntegrationTestCase
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class IntegrationTestCase extends TestCase
{
    use DbAssertions;

    /**
     * @var Application
     */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setDb(DB::init());
        $this->setupDb();
        $this->app = new Application;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->truncateDb();
    }
}
