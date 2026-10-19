<?php

namespace App\Repositories;

use App\Models\Invoice;
use PDO;
use App\Models\InvoiceItem;


class InvoiceRepository
{
    public function __construct(private PDO $db) {}
    
    public function create(Invoice $invoice) : int 
    {
        $stmt = $this->db->prepare(
            'INSERT INTO invoices (customer_id, invoice_date, total)
            VALUES (:customer_id, :invoice_date, :total)'
            );
        $stmt->execute([
            'customer_id' => $invoice->getCustomerId(),
            'invoice_date' => $invoice->getInvoiceDate(),
            'total'        => $invoice->getTotal(),
        ]);
        
        $invoiceId = (int) $this->db->lastInsertId();
        $itemStmt = $this->db->prepare(
            'INSERT INTO invoice_items (invoice_id, product_id, quantity, unit_price, line_total)
            VALUES (:invoice_id , :product_id, :quantity, :unit_price, :line_total)'
            );

        foreach($invoice->getItems() as $item) {
            $itemStmt->execute([
                'invoice_id' => $invoiceId,
                'product_id' => $item->getProductId(),
                'quantity'   => $item->getQuantity(),
                'unit_price' => $item->getUnitPrice(),
                'line_total' => $item->getLineTotal(),
            ]);
        }
        return $invoiceId;
    }

    public function updateInvoiceNumber(int $id, string $invoiceNumber) :void
    {
        $stmt = $this->db->prepare('UPDATE invoices SET invoice_number = :invoice_number WHERE id = :id');
        $stmt->execute([
            'invoice_number' => $invoiceNumber,
            'id' => $id,
            ]);
    }

    public function findById(int $id): ?Invoice
    {
        $stmt = $this->db->prepare('SELECT * FROM invoices WHERE id = :id');
        $stmt->execute([
        'id' => $id]);
        $row = $stmt->fetch();

        if($row === false) {

            return null;
        }
        $invoice = Invoice::fromArray($row);

        $itemStmt = $this->db->prepare('SELECT * FROM  invoice_items WHERE invoice_id = :invoice_id');

        $itemStmt->execute([
            'invoice_id' => $id]);

        foreach ($itemStmt->fetchAll() as $itemRow) {
            $invoice->addItem(InvoiceItem::fromArray($itemRow));
            }
        
        return $invoice;
    }

    public function getSalesTransaction(?string $dateFrom, ?string $dateTo, ?int $customerId) : array
    {
        $conditions = [];
        $params = [];
        $sql =
            'SELECT  
                    invoices.invoice_number,
                    invoices.invoice_date,
                    customers.name AS customer_name,
                    invoices.total
                    FROM invoices
                    JOIN customers ON customers.id = invoices.customer_id';

        if($dateFrom !== null){
            $conditions[] = 'invoices.invoice_date >= :date_from';
            $params['date_from'] = $dateFrom;
        }
        if($dateTo !== null) {
            $conditions[] = 'invoices.invoice_date <= :date_to';
            $params['date_to'] = $dateTo;
        }
        if($customerId !== null) {
            $conditions[] = 'invoices.customer_id = :customer_id';
            $params['customer_id'] = $customerId;
        }
        if(!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY invoices.invoice_date DESC ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}