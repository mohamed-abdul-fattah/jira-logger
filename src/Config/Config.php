<?php

namespace App\Config;

use PDO;
use App\Http\Request;
use App\Http\TestRequest;
use App\Persistence\TestDb;

/**
 * Class Config
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class Config
{
    /**
     * Return application database based on the running env
     *
     * @return TestDb|PDO
     */
    public static function getDb()
    {
        if (isTestingEnv()) {
            return TestDb::init();
        }

        $dbFile = __DIR__ . '/../Persistence/database.db';
        $db     = new PDO("sqlite:{$dbFile}");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    /**
     * Get command dispatcher
     *
     * @return Request|TestRequest
     */
    public static function getDispatcher()
    {
        if (isTestingEnv()) {
            return new TestRequest;
        }

        return new Request;
    }

    /**
     * Get configuration by key
     *
     * @param  string $key
     * @return mixed|null
     */
    public static function get(string $key)
    {
        $configurations = require __DIR__ . '/app.php';

        return $configurations[$key] ?? null;
    }
}
