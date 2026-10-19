<?php

namespace App\Models;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private ?string $createdAt;

    public function __construct(int $id, string $name, float $price, ?string $createdAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
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
    public function getPrice() : float
    {
        return $this->price;
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
            (float)$row['price'],
            $row['created_at'] ?? null
        );
    }
}