<?php

namespace Craftsys\Msg91\Exceptions;

use Exception;
use Throwable;

/**
 * Thrown when we don't receive a success response
 */
class ResponseErrorException extends Exception
{
    /**
     * Response Errors
     * @var array|null
     */
    protected $errors;

    public function __construct($message = "", $code = 0, Throwable $previous = null, $errors = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
