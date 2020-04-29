<?php

namespace App\Services\Validators\Tempo;

use App\Services\Json;
use App\Exceptions\JsonException;
use App\Exceptions\RunTimeException;

/**
 * Class AttributesValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class AttributesValidator
{
    /**
     * Validates whether the provided attributes is in JSON format or not
     *
     * @param  string $attributesJson
     * @throws RunTimeException
     */
    public function isJson(string $attributesJson)
    {
        try {
            Json::decode($attributesJson);
        } catch (JsonException $e) {
            throw new RunTimeException('Invalid JSON attributes!');
        }
    }
}
