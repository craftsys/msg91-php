<?php

namespace Craftsys\MSG91Client\Exceptions;

use Exception;

class ResponseError extends Exception
{
    /**
     * Something went wrong with the request we send as we have an invalid response
     */
    public function __construct(?string $message = "")
    {
        return new Exception("MSG91 responded with an error. `{$message}`");
    }
}
