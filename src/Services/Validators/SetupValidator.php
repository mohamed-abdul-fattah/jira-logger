<?php

namespace App\Services\Validators;

use App\Exceptions\RunTimeException;

/**
 * Class SetupValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SetupValidator
{
    /**
     * @var string
     */
    private $platform_uri;

    /**
     * SetupValidator constructor.
     *
     * @param string $platform_uri
     */
    public function __construct($platform_uri)
    {
        $this->platform_uri = $platform_uri;
    }

    /**
     * Validate platform URI
     *
     * @throws RunTimeException
     */
    public function validate()
    {
        if (empty($this->platform_uri)) {
            throw new RunTimeException('Platform URI cannot be empty.');
        } elseif (! is_string($this->platform_uri)) {
            throw new RunTimeException('Platform URI must be a string.');
        } elseif (! preg_match('/^https?:\/\/.+\..+\/$/', $this->platform_uri)) {
            throw new RunTimeException('Platform URI must be in http(s)://example.domain/ format');
        }
    }
}
