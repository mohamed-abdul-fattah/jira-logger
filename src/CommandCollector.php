<?php

namespace App;

use App\Config\Config;

/**
 * Class CommandCollector
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class CommandCollector
{
    /**
     * @var array
     */
    private $collection;

    /**
     * CommandCollector constructor.
     */
    public function __construct()
    {
        $this->collection = Config::get('commands');
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
