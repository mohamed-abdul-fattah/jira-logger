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

/**
 * Convert a snake_case string to PascalCase
 *
 * @param  string $str
 * @return string
 */
function pascalCase(string $str): string
{
    return Str::toPascalCase($str);
}

/**
 * Get environment variable value
 *
 * @param  string $key
 * @param  mixed|null $default
 * @return mixed|false
 */
function env(string $key, $default = null)
{
    $value = getenv($key);
    if (is_null($value) && ! empty($default)) {
        return $default;
    }

    return $value;
}

/**
 * Checks whether the setup environment is testing or not
 *
 * @return bool
 */
function isTestingEnv(): bool
{
    return env('ENV') === 'testing';
}
