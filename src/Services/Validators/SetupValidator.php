<?php

namespace App\Services\Validators;

use App\Exceptions\RunTimeException;

/**
 * Class SetupValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class SetupValidator
{
    /**
     * @var string
     */
    private $platformUri;

    /**
     * SetupValidator constructor.
     *
     * @param string $platformUri
     */
    public function __construct($platformUri)
    {
        $this->platformUri = $platformUri;
    }

    /**
     * Validate platform URI
     *
     * @throws RunTimeException
     */
    public function validate()
    {
        if (empty($this->platformUri)) {
            throw new RunTimeException('Platform URI cannot be empty.');
        } elseif (! is_string($this->platformUri)) {
            throw new RunTimeException('Platform URI must be a string.');
        } elseif (! preg_match('/^https:\/\/.+\..+\/?$/', $this->platformUri)) {
            throw new RunTimeException('Platform URI must be in https://example.domain/ format.');
        }
    }
}
