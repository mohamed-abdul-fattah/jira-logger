<?php

namespace App\Persistence;

use PDO;
use PDOException;
use App\Exceptions\DbException;

/**
 * Class DB
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  v1.0.0
 */
class DB
{
    /**
     * @var DB|null
     */
    protected static $instance = null;

    /**
     * @var PDO
     */
    protected $db;

    /**
     * DB constructor.
     *
     * @throws DbException
     */
    private function __construct()
    {
        $dbFile = __DIR__ . '/database.db';
        try {
            $this->db = new PDO("sqlite:{$dbFile}");
        } catch (PDOException $e) {
            throw new DbException('Cannot setup database connection!');
        }
    }

    /**
     * Close database connection
     */
    public function __destruct()
    {
        $this->db = null;
    }

    /**
     * @return DB
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Begin database transaction
     */
    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    /**
     * Commit database transaction
     */
    public function commit()
    {
        $this->db->commit();
    }

    /**
     * Run SQL raw query
     *
     * @param  string $query
     * @return false|\PDOStatement
     */
    public function query(string $query)
    {
        return $this->db->query($query);
    }
}
