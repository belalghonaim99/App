<?php

namespace App\Repositories;

use App\Models\Customer;
use PDO;

class CustomerRepository
{
    public function __construct(private PDO $db) {}
    
    public function findById(int $id): ?Customer {
        $stmt = $this->db->prepare('SELECT * FROM customers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if($row === false) {
            return null;
        }
        return Customer::fromArray($row);
    }
}