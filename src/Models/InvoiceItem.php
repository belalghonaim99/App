<?php

namespace App\Models;

class InvoiceItem
{
    private ?int $id;
    private ?int $invoiceId;
    private int $productId;
    private int $quantity;
    private float $unitPrice;
    private float $lineTotal;
    
    public function __construct(int $productId, int $quantity, float $unitPrice, ?int $id = null,  ?int $invoiceId = null) {
        $this->id = $id;
        $this->invoiceId = $invoiceId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->lineTotal = $unitPrice * $quantity;
    }
        public function getId() :?int
    {
        return $this->id;
    }
        public function getInvoiceId() :?int
    {
        return $this->invoiceId;
    }
        public function getProductId() :int
    {
        return $this->productId;
    }
        public function getQuantity() :int
    {
        return $this->quantity;
    }
        public function getUnitPrice() :float
    {
        return $this->unitPrice;
    }
        public function getLineTotal() :float
    {
        return $this->lineTotal;
    }

    public static function fromArray(array $row): self {
        
    // Using Name Arguments to fix the __construct and new __construct conflict
        return new self (
            id: (int) $row['id'],
            invoiceId: (int) $row['invoice_id'],
            productId: (int) $row['product_id'],
            quantity: (int) $row['quantity'],
            unitPrice: (float) $row['unit_price'],
        );
    }
}   