<?php

namespace App\Repositories;

use App\Persistence\DB;

/**
 * Class Repository
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
abstract class Repository
{
    /**
     * @var DB
     */
    protected $db;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->db = DB::init();
    }
}
