<?php

require __DIR__ . '/../vendor/autoload.php';

use App\ConnectDataBase\Database;
use App\Exceptions\ValidationException;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Services\InvoiceService;
use App\Validators\InvoiceValidator;

$db = Database::getConnection();

$service = new InvoiceService(
    $db,
    new CustomerRepository($db),
    new ProductRepository($db),
    new InvoiceRepository($db),
    new InvoiceValidator()
);

try {
    $invoice = $service->createInvoice([
        'customer_id' => 1,
        'items' => [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 3],
        ],
    ]);

    echo 'Invoice ID: ' . $invoice->getId() . '<br>';
    echo 'Invoice Number: ' . $invoice->getInvoiceNumber() . '<br>';
    echo 'Date: ' . $invoice->getInvoiceDate() . '<br>';
    echo 'Items: ' . count($invoice->getItems()) . '<br>';
    echo 'Total: ' . $invoice->getTotal();
} catch (ValidationException $e) {
    echo '<pre>';
    print_r($e->getErrors());
    echo '</pre>';
}