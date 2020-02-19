<?php

namespace App\Services\Validators;

use App\Exceptions\RunTimeException;

/**
 * Class ConnectValidator
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class ConnectValidator
{
    /**
     * Validate input username
     *
     * @param  string $username
     * @return string
     */
    public function validateUsername($username): string
    {
        if (empty($username)) {
            throw new RuntimeException('Username cannot be empty!');
        }

        if (! is_string($username)) {
            throw new RuntimeException('Username must be a string!');
        }

        return $username;
    }

    /**
     * Validate input password
     *
     * @param  string $password
     * @return string
     */
    public function validatePassword($password): string
    {
        if (empty($password)) {
            throw new RuntimeException('Password cannot be empty!');
        }

        if (! is_string($password)) {
            throw new RuntimeException('Password must be a string!');
        }

        return $password;
    }
}
