<?php

namespace Craftsys\Msg91\Requests;

class Validator
{
    /**
     * Validation errors
     * @var array
     */
    protected $errors = [];

    /**
     * Add an error
     * @param string $key
     * @param string $error
     * @return $this;
     */
    public function addError($key, $error)
    {
        $this->errors[$key] = $this->errors[$key] ?? [];
        $this->errors[$key][] = $error;
        return $this;
    }

    /**
     * Get the validatation errors
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        return !count($this->errors);
    }
}
