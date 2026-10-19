<?php

namespace App\Repositories;

use App\Models\Product;
use PDO;

class ProductRepository
{
    public function __construct(private PDO $db) {}
    
    public function findById(int $id) :?Product {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        
        if($row === false) {
            return null;
        }
        return Product::fromArray($row);
    }
}