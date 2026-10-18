<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;
class ReportService
{

    public function __construct(private InvoiceRepository $invoiceRepository) {}
    
    public function getSalesReport(?string $dateFrom, ?string $dateTo, ?int $customerId): array
    {
        $transactions = $this->invoiceRepository->getSalesTransaction($dateFrom, $dateTo, $customerId);
        $totalInovices = count($transactions);
        $totalSales = round(array_sum(array_column($transactions, 'total')), 2);

        return [
            'transactions' => $transactions,
            'total_invoices' => $totalInovices,
            'total_sales' => $totalSales
        ];
    }
}