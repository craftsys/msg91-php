<?php

namespace Craftsys\MSG91\Exceptions;

use Exception;

class TokenRequired extends Exception
{

    /**
     * No token exception
     */
    public function __construct(
        ?string $message = "Token is required."
    ) {
        return new Exception($message);
    }
}
