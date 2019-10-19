<?php

namespace Craftsys\MSG91\Exceptions;

use Exception;

/**
 * Thrown when authentication key is not found
 */
class AuthKeyRequired extends Exception
{

    /**
     * No token exception
     */
    public function __construct(
        ?string $message = "Authentication key is required."
    ) {
        return new Exception($message);
    }
}
