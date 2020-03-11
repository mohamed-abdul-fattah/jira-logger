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

    /**
     * Starts a task for stopping test
     *
     * @param string $taskId
     * @param string $desc
     */
    protected function startLog(
        $taskId = 'TASK-123',
        $desc = 'Working on TASK-123 issue'
    ) {
        $time = date('Y-m-d H:i', strtotime('-1 hour'));

        $this->db->insert('logs', [
            'task_id'     => $taskId,
            'started_at'  => $time,
            'description' => $desc,
        ]);
    }

    /**
     * Set testing platform URI
     */
    protected function setPlatformUri(): void
    {
        $this->db->saveSetting('platform_uri', 'https://example.com');
    }
}
