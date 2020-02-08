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
    protected static $dbFile = __DIR__ . '/../Persistence/test.db';

    /**
     * TestDb constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * TODO: change to use in-memory array instead of SQLite DB
     *
     * @return PDO
     */
    public static function init()
    {
        $file = self::$dbFile;
        $db   = new PDO("sqlite:{$file}");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}
