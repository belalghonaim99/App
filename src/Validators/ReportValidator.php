<?php

namespace App\Validators;

use App\Exceptions\ValidationException;
use DateTime;
class ReportValidator
{
    public function validateAll(array $filters) :void
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateDate($filters,'date_from'));
        $errors = array_merge($errors, $this->validateDate($filters,'date_to'));
        $errors = array_merge($errors, $this->validateCustomerId($filters));
        if (!isset($errors['date_from']) && !isset($errors['date_to'])) {
            $errors = array_merge($errors, $this->validateDateRange($filters));
            }

        if(!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
    private function validateDate(array $filters, string $key): array 
    {
        $errors = [];

        if(!isset($filters[$key])){
            return $errors;
        }
        $value = $filters[$key];
        $result = DateTime::createFromFormat('Y-m-d', $value);
        if($result === false || $result->format('Y-m-d') !== $value) {
            $errors [$key] = "$key must be a valid date in Y-m-d format";
        }
        return $errors;
    }

    private function validateCustomerId(array $filters) :array
    {
        $errors = [];
        if(!isset($filters['customer_id'])){
            return $errors;
        }

        $value = $filters['customer_id'] ?? null;
        
        if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            $errors['customer_id'] = 'Customer Id Must Be Valid And in positive Number';
        }
        return $errors;
    }

    private function validateDateRange(array $filters): array
    {       
        $errors = [];

        if(!isset($filters['date_from']) || !isset($filters ['date_to'])){
            return $errors;
        }
        if($filters['date_from'] > $filters['date_to']) {
            $errors['date_to'] = 'date_to must be after or equal to date_from';
        }
        return $errors;
    }
}