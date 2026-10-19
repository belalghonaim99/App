<?php

namespace App\Models;

class Invoice
{
    private ?int $id;
    private ?string $invoiceNumber;
    private int $customerId;
    private string $invoiceDate;
    private float $total = 0;
    private array $items = [];

    public function __construct(int $customerId, string $invoiceDate, ?int $id = null, ?string $invoiceNumber = null) {

    $this->customerId = $customerId;
    $this->invoiceDate = $invoiceDate;
    $this->id = $id;
    $this->invoiceNumber = $invoiceNumber;
    }
    
    public function addItem(InvoiceItem $item) : void
    {
        $this->items[] = $item;
        $this->total += round($item->getLineTotal(), 2);
    }
    public function getId() : ?int {
        return $this->id;
    }
    public function getInvoiceNumber() : ?string {
        return $this->invoiceNumber;
    }
    public function getCustomerId() : int {
        return $this->customerId;
    }
    public function getInvoiceDate() : string {
        return $this->invoiceDate;
    }
    public function getTotal() : float {
        return $this->total;
    }
    public function getItems() : array {
        return $this->items;
    }
    
    public static function fromArray(array $row) :self {
        return new self(
            customerId: (int)$row['customer_id'],
            invoiceDate: (string)$row['invoice_date'],
            id: (int)$row['id'],
            invoiceNumber: (string)$row['invoice_number'],
        );
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoiceNumber,
            'customer_id' => $this->customerId,
            'invoice_date' => $this->invoiceDate,
            'total' => $this->total,
            'items' => array_map(fn($item) => $item->toArray(), $this->items)
        ];
    }
}