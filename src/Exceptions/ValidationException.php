<?php

namespace App\Exceptions;
use Exception;

class ValidationException extends Exception
{
    public function __construct(private array $errors) {
        parent::__construct('Validation Failed');
    }
    public function getErrors() : array {
        return $this->errors;
    }
}