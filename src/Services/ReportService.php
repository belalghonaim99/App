<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;
use App\Validators\ReportValidator;

class ReportService
{

    public function __construct(private InvoiceRepository $invoiceRepository, private ReportValidator $reportValidator) {}
    
    public function getSalesReport(array $filters): array
    {
        $this->reportValidator->validateAll($filters);

        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        $customerId = isset($filters['customer_id']) ? (int) $filters['customer_id'] : null;
        $transactions = $this->invoiceRepository->getSalesTransaction($dateFrom, $dateTo, $customerId);
        $totalInvoices = count($transactions);
        $totalSales = round(array_sum(array_column($transactions, 'total')), 2);

        return [
            'transactions' => $transactions,
            'total_invoices' => $totalInvoices,
            'total_sales' => $totalSales
        ];
    }
}