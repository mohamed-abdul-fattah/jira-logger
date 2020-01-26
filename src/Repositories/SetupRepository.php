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
                start_time TIMESTAMP CURRENT_TIMESTAMP NOT NULL,
                end_time TIMESTAMP NULL,
                log VARCHAR(10) NULL
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
     * @param string $uri
     * @throws DbException
     */
    private function setupPlatform(string $uri)
    {
        $check    = $this->db->fetch("
                        SELECT COUNT(`id`) as count FROM settings
                        WHERE `key`='platform_uri' AND `value`=:uri
                    ", ['uri' => $uri]);
        if ($check->count > 0) {
            throw new DbException('Cannot insert platform URI. Already inserted!');
        }

        $inserted = $this->db->query("
                        INSERT INTO settings (`key`, `value`)
                        VALUES ('platform_uri', :uri)
                    ", ['uri' => $uri]);

        if ($inserted === false) {
            throw new DbException('Cannot insert platform URI. Please, run `setup` command first.');
        }
    }
}
