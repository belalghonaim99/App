<?php

namespace App\Models;

use InvalidArgumentException;
class Customer
{
    private int $id;
    private string $name;
    private ?string $email;
    private ?string $phone;
    private ?string $createdAt;

    public function __construct(int $id, string $name, ?string $email, ?string $phone, ?string $createdAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->createdAt = $createdAt;
    }
    public function getId() : int
    {
        return $this->id;
    }
    public function getName() :string
    {
        return $this->name;
    }
    public function getEmail() :?string
    {
        return $this->email;
    }
    public function getPhone() :?string
    {
        return $this->phone;
    }
        public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    public static function fromArray(array $row) : self
    {
        return new self(
            (int) $row['id'],
            $row['name'],
            $row['email'] ?? null,
            $row['phone']?? null,
            $row['created_at']?? null,
        );
    }
    
}