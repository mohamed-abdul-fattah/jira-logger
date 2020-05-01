<?php

namespace App\Services;

use App\Exceptions\JsonException;

/**
 * Class Json
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
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
     * @param  bool   $toArray
     * @return mixed
     */
    public static function decode(string $json, $toArray = false)
    {
        $res = json_decode($json, $toArray, self::JSON_DEPTH);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(
                "Message doesn't contain valid JSON: " . json_last_error_msg()
            );
        }

        return $res;
    }

    /**
     * Encode an array into JSON
     *
     * @param  array $body
     * @return false|string
     */
    public static function encode(array $body)
    {
        $json = json_encode($body, 0, self::JSON_DEPTH);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(
                "Body doesn't contain valid JSON: " . json_last_error_msg()
            );
        }

        return $json;
    }
}
