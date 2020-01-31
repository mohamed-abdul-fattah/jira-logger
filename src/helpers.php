<?php

use App\Utils\Str;

/**
 * Convert a snake_case string to camelCase
 *
 * @param  string $str
 * @return string
 */
function camelCase(string $str): string
{
    return Str::toCamelCase($str);
}
