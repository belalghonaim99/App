<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Validators\InvoiceValidator;
use PDO;
use Throwable;

class InvoiceService
{

    public function __construct(
        private PDO $db, private CustomerRepository $customerRepository,
        private ProductRepository $productRepository, private InvoiceRepository $invoiceRepository,
        private InvoiceValidator $invoiceValidator) {}

    public function createInvoice(array $data) : Invoice {
        $this->invoiceValidator->validateAll($data);
        $customer = $this->customerRepository->findById($data['customer_id']);
        if($customer === null){
            throw new ValidationException(['customer_id' => 'Customer Not Found']);
        }

        $invoice = new Invoice(
            customerId: $customer->getId(),
            invoiceDate: $data['invoice_date'] ?? date('Y-m-d')
        );
        $errors = [];

        foreach($data['items'] as $index => $item) {
            $product = $this->productRepository->findById($item['product_id']);
            
            if($product === null) {
                $errors["items.$index.product_id"] = 'Product Not Found';
                continue;
            }

            $newItem = new InvoiceItem(
                productId: $product->getId(),
                quantity: $item['quantity'],
                unitPrice: $product->getPrice()
            );
            $invoice->addItem($newItem);
        }

        if(!empty($errors)){
            throw new ValidationException($errors);
        }

        try {
            $this->db->beginTransaction();
            $invoiceId = $this->invoiceRepository->create($invoice);
            $this->invoiceRepository->updateInvoiceNumber($invoiceId, $this->generateInvoiceNumber($invoiceId));
            $this->db->commit();
        }

        catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $this->invoiceRepository->findById($invoiceId);
    }

    private function generateInvoiceNumber(int $invoiceId) : string {
        $year = date('Y');
        $numbers = str_pad((string)$invoiceId, 6, '0', STR_PAD_LEFT);
        
        return "INV-$year-$numbers";
    }

    public function getInvoice(int $id): ?Invoice
    {
        return $this->invoiceRepository->findById($id);    
    }
}