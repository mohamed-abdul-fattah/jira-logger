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
 * @since  v1.0.0
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
}
