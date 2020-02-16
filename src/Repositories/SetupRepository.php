<?php

namespace App\Repositories;

use App\Exceptions\DbException;

/**
 * Class SetupRepository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SetupRepository extends Repository
{
    /**
     * Setup database
     */
    public function setupDb()
    {
        $this->db->beginTransaction();

        $this->createLogsTable();
        $this->createSettingsTable();

        $this->db->commit();
    }

    /**
     * Seed database with setup data
     *
     * @param string $uri
     * @throws DbException
     */
    public function seedDb(string $uri)
    {
        $this->db->beginTransaction();

        $this->setupPlatform($uri);

        $this->db->commit();
    }

    /**
     * Create logs table for logging tasks
     */
    private function createLogsTable()
    {
        $this->db->raw("
            CREATE TABLE IF NOT EXISTS logs (
                id INTEGER PRIMARY KEY,
                task_id VARCHAR(20) NOT NULL,
                description VARCHAR(255) NULL,
                started_at TIMESTAMP CURRENT_TIMESTAMP NOT NULL,
                ended_at TIMESTAMP NULL,
                log VARCHAR(10) NULL,
                synced TINYINT DEFAULT 0
            );
        ");
    }

    /**
     * Create settings table to hold configurations
     */
    private function createSettingsTable()
    {
        $this->db->raw("
            CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY,
                key VARCHAR(255) NOT NULL,
                value VARCHAR(255) NULL
            );
        ");
    }

    /**
     * Setup platform URI
     *
     * @param  string $uri
     * @throws DbException
     */
    private function setupPlatform(string $uri)
    {
        $this->db->saveSetting('platform_uri', $uri);
    }
}
