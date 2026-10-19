<?php

namespace App\Controllers;

use App\Core\Response;
use App\Services\InvoiceService;

class InvoiceController
{
    public function __construct(private InvoiceService $invoiceService) {}
    
    public function show(int $id): void
    {
        $invoice = $this->invoiceService->getInvoice($id);

        if($invoice === null) {
                Response::json(['message' => 'Invoice not found'], 404);
            }
        Response::json(['data' => $invoice->toArray()]);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if(!is_array($data)){
                Response::json(['message' => 'Invalid JSON body'], 400);
            }
        $result = $this->invoiceService->createInvoice($data);
        Response::json(['data' => $result->toArray()], 201);
    }
}