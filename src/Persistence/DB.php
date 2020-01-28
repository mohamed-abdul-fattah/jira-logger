<?php

namespace App\Persistence;

use PDO;
use stdClass;
use PDOException;
use PDOStatement;
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
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
     * @return false|PDOStatement
     */
    public function raw(string $query)
    {
        return $this->db->query($query);
    }

    /**
     * Execute a sanitized query statement
     *
     * @param  string $statement
     * @param  array $args
     * @return bool
     */
    public function query(string $statement, array $args = [])
    {
        return $this->db
                    ->prepare($statement)
                    ->execute($args);
    }

    /**
     * Return the 1st fetched row of the given query
     *
     * @param  string $statement
     * @param  array $args
     * @return stdClass
     */
    public function fetch(string $statement, array $args = [])
    {
        $sth = $this->db->prepare($statement);
        $sth->execute($args);

        return $sth->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Insert a new record into a database table
     *
     * @param  string $table
     * @param  array $args
     * @return bool
     */
    public function insert(string $table, array $args)
    {
        $keys = array_keys($args);
        $cols = implode(',', $keys);
        $vals = implode(',:', $keys);
        $sth  = "INSERT INTO {$table} ({$cols})
                 VALUES (:{$vals})";

        return $this->query($sth, $args);
    }

    /**
     * Get columns count based on a given conditions
     *
     * @param  string $table
     * @param  array $conditions
     * @return int
     */
    public function count(string $table, $conditions = [])
    {
        $sth = "SELECT COUNT(*) as count FROM {$table} WHERE 1=1";
        if (! empty($conditions)) {
            foreach ($conditions as $col => $value) {
                if (is_null($value)) {
                    $sth .= " AND {$col} IS NULL";
                    unset($conditions[$col]);
                    continue;
                }
                $sth .= " AND {$col}=:{$col}";
            }
        }

        $col = $this->fetch($sth, $conditions);
        return (int) $col->count;
    }
}
