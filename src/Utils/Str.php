<?php

namespace App\Utils;

/**
 * Class Str
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Str
{
    /**
     * Convert snake_case to camelCase
     *
     * @param string $str
     * @return string
     */
    public static function toCamelCase(string $str): string
    {
        $str = ucwords($str, '_');
        $str = str_replace('_', '', $str);

        return strtolower(substr($str, 0, 1)) . substr($str, 1);
    }
}
