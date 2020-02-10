<?php

namespace Tests\Integration;

use App\Services\Json;
use App\Persistence\DB;

/**
 * Trait DbAssertions
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  v1.0.0
 */
trait DbAssertions
{
    /**
     * @var DB
     */
    protected $db;

    /**
     * @param DB $db
     */
    protected function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Setup database tables
     */
    protected function setupDb()
    {
        $this->db->raw("
            CREATE TABLE IF NOT EXISTS logs (
                id INTEGER PRIMARY KEY,
                task_id VARCHAR(20) NOT NULL,
                description VARCHAR(255) NULL,
                started_at TIMESTAMP CURRENT_TIMESTAMP NOT NULL,
                ended_at TIMESTAMP NULL,
                log VARCHAR(10) NULL
            );
        ");
        $this->db->raw("
            CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY,
                key VARCHAR(255) NOT NULL,
                value VARCHAR(255) NULL
            );
        ");
    }

    /**
     * Clean database
     */
    public function truncateDb()
    {
        $this->db->raw("
            DROP TABLE logs;
            DROP TABLE settings;
        ");
        $this->db = null;
    }

    /**
     * Assert database has a record with the given conditions
     *
     * @param string $table
     * @param array $conditions
     */
    public function assertDatabaseHas(string $table, array $conditions)
    {
        $count = $this->db->count($table, $conditions);

        $msg = "Failed asserting that the {$table} table has a record with the following conditions "
             . Json::encode($conditions);
        $this->assertTrue($count > 0, $msg);
    }

    /**
     * Assert database doesn't have a record with the given conditions
     *
     * @param string $table
     * @param array $conditions
     */
    public function assertDatabaseDoesntHave(string $table, array $conditions)
    {
        $count = $this->db->count($table, $conditions);

        $msg = "Failed asserting that the {$table} table doesn't have a record with the following conditions "
            . Json::encode($conditions);
        $this->assertTrue($count == 0, $msg);
    }
}
