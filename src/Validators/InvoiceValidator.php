<?php 

namespace App\Validators;

use App\Exceptions\ValidationException;
use DateTime;

class InvoiceValidator
{
    public function validateAll(array $data) : void 
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateCustomer($data));
        $errors = array_merge($errors, $this->validateDate($data));
        $errors = array_merge($errors, $this->validateItems($data));
        if(!empty($errors)){
            throw new ValidationException($errors);
        }
    }

    private function validateCustomer(array $data): array
    {
        $errors = [];
        $value = $data['customer_id'] ?? null;
        
        if(!is_int($value) || $value <= 0){

            $errors['customer_id'] = 'customer_id is required and must be a positive Number';
        }
        return $errors;
    }
    private function validateDate(array $data): array
    {
        $errors = [];

        if(!isset($data['invoice_date'])){
            return $errors;
        }

        $value = $data['invoice_date'];
        $result = DateTime::createFromFormat('Y-m-d', $value);
        // false first to Short-circuit evaluation
        if( $result === false || $result->format('Y-m-d') !== $value){

            $errors['invoice_date'] = 'Invoice Date Not Match Or Formating Date Error, Date Format must be in (Y-m-d)';
        }
        return $errors;
    }

    private function validateItems(array $data): array
    {
        $errors = [];

        if(empty($data['items']) || !is_array($data['items'])) {
            $errors['items'] = 'Items Error';
        }
        foreach($data['items'] as $index => $item)
            {
                $value = $item['product_id'] ?? null;
                
                if($value=== null || $value <= 0) {
                    $errors["items.$index.product_id"] = 'product_id error';
                }

                $quantity = $item['quantity'] ?? null;

                if($quantity === null || $quantity <= 0) {
                    $errors["items.$index.quantity"] = 'quantity erorr';
                }
            }
            return $errors;
    }
}