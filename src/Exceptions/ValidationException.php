<?php

namespace Craftsys\Msg91\Exceptions;

use Exception;
use Throwable;

/**
 * Thrown when there is an error with the request payload
 */
class ValidationException extends Exception
{
    /**
     * Validation errors
     * @var array|null
     */
    protected $errors;

    public function __construct($message = "", $code = 0, Throwable $previous = null, $errors = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors()
    {
        return $this->errors;
    }
}
