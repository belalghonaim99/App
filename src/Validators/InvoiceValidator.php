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

            $errors['invoice_date'] = 'invoice_date must be a Valid Date must be in (Y-m-d) Format';
        }
        return $errors;
    }

    private function validateItems(array $data): array
    {
        $errors = [];

        if(empty($data['items']) || !is_array($data['items'])) {
            $errors['items'] = 'Items is Required and must contain atleast one item';
            return $errors;
        }
        foreach($data['items'] as $index => $item)
            {
                $value = $item['product_id'] ?? null;
                
                if(!is_int($value) || $value <= 0) {
                    $errors["items.$index.product_id"] = 'product_id is required and must be Positive Number';
                }

                $quantity = $item['quantity'] ?? null;

                if(!is_int($quantity) || $quantity <= 0) {
                    $errors["items.$index.quantity"] = 'quantity is required and must be Positive number';
                }
            }
            return $errors;
    }
}