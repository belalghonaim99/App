<?php

use App\Core\Database;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Services\InvoiceService;
use App\Validators\InvoiceValidator;
use App\Services\ReportService;
use App\Validators\ReportValidator;
use App\Controllers\InvoiceController;
use App\Controllers\ReportController;
use App\Core\Response;
use App\Exceptions\ValidationException;

require __DIR__ . '/../vendor/autoload.php';


try{
$db = Database::getConnection();

$service = new InvoiceService(
    $db, 
    new CustomerRepository($db),
    new ProductRepository($db),
    new InvoiceRepository($db),
    new InvoiceValidator()
);

$report = new ReportService(new InvoiceRepository($db), new ReportValidator());

$invoiceController = new InvoiceController($service);

$reportController = new ReportController($report);

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $path === '/reports/sales') {
    $reportController->sales();
}
if ($method === 'POST' && $path === '/invoices') {
    $invoiceController->store();
}
if ($method === 'GET' && preg_match('#^/invoices/(\d+)$#', $path, $matches)) {
    $invoiceController->show((int) $matches[1]);
}
Response::json(['message' => 'Route not found'], 404);
}

catch (ValidationException $e) {
    Response::json([
        'message' => $e->getMessage(),
        'errors'  => $e->getErrors()
    ], 422);
}
catch (Throwable $e) {
    Response::json(['message' => 'Internal Server Error'], 500);
}