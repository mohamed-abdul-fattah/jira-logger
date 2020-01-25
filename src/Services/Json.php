<?php

namespace App\Services;

use App\Exceptions\JsonException;

/**
 * Class Json
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class Json
{
    /**
     * Recursive decoding depth
     */
    const JSON_DEPTH = 512;

    /**
     * Decode JSON string into an object
     *
     * @param  string $json
     * @return mixed
     * @throws JsonException
     */
    public static function decode(string $json)
    {
        $res = json_decode($json, false, self::JSON_DEPTH);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(
                "Message doesn't contain valid JSON: " . json_last_error_msg()
            );
        }

        return $res;
    }
}
