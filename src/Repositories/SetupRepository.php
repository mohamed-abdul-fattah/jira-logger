<?php

namespace App\Repositories;

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
        $this->db->query("
            CREATE TABLE IF NOT EXISTS logs (
                id INTEGER PRIMARY KEY,
                task_id VARCHAR(20) NOT NULL,
                description VARCHAR(255) NULL,
                start_time TIMESTAMP CURRENT_TIMESTAMP NOT NULL,
                end_time TIMESTAMP NULL,
                log VARCHAR(10) NULL
            );
        ");
        $this->db->commit();
    }
}
