<?php

namespace App\Persistence;

use PDO;

/**
 * Class TestDb
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  v1.0.0
 */
class TestDb
{
    /**
     * @var string
     */
    protected $dbFile = __DIR__ . '/../Persistence/test.db';

    /**
     * TestDb constructor.
     *
     * TODO: change to use in-memory array instead of SQLite DB
     */
    public function __construct()
    {
        $db = new PDO("sqlite:{$this->dbFile}");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    public function __destruct()
    {
        @unlink($this->dbFile);
    }
}
