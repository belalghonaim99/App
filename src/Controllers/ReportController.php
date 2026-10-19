<?php 

namespace App\Controllers;

use App\Core\Response;
use App\Services\ReportService;

class ReportController
{
    public function __construct(private ReportService $reportService) {}
    
    public function sales() :void
    {
        $report = $this->reportService->getSalesReport($_GET);

        Response::json(['data' => $report]);
    }
}