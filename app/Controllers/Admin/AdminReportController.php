<?php
// app/Controllers/Admin/AdminReportController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};
use Application\Queries\Report\{GetSalesReportQuery, GetSalesReportHandler};

class AdminReportController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
        Middleware::admin();
    }

    public function index(): void
    {
        $from    = $this->req->get('from', date('Y-m-01'));
        $to      = $this->req->get('to',   date('Y-m-d'));
        $groupBy = $this->req->get('group', 'daily');

        $report = (new GetSalesReportHandler())->handle(
            new GetSalesReportQuery($from, $to, $groupBy)
        );

        $this->res->render('admin.reports.index', [
            'title'  => 'Laporan Penjualan',
            'report' => $report,
            'from'   => $from,
            'to'     => $to,
            'groupBy' => $groupBy,
        ], 'admin');
    }

    public function print(): void
    {
        $from   = $this->req->get('from', date('Y-m-01'));
        $to     = $this->req->get('to',   date('Y-m-d'));
        $report = (new GetSalesReportHandler())->handle(
            new GetSalesReportQuery($from, $to, 'daily')
        );

        $this->res->render('admin.reports.print', [
            'title'  => 'Cetak Laporan Penjualan',
            'report' => $report,
            'from'   => $from,
            'to'     => $to,
        ]);   // Tanpa layout admin — layout kosong untuk print
    }

    public function export(): void
    {
        $from   = $this->req->get('from', date('Y-m-01'));
        $to     = $this->req->get('to',   date('Y-m-d'));
        $report = (new GetSalesReportHandler())->handle(
            new GetSalesReportQuery($from, $to, 'daily')
        );

        // Export ke CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan-' . $from . '_' . $to . '.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Periode', 'Jumlah Order', 'Total Penjualan']);
        foreach ($report['trend'] as $row) {
            fputcsv($out, [$row['period'], $row['orders'], $row['revenue']]);
        }
        fclose($out);
        exit;
    }

    public function trends(): void
    {
        // Untuk Owner — analisa tren pasar
        Middleware::owner();

        $from   = $this->req->get('from', date('Y-m-01', strtotime('-3 months')));
        $to     = $this->req->get('to',   date('Y-m-d'));
        $report = (new GetSalesReportHandler())->handle(
            new GetSalesReportQuery($from, $to, 'monthly')
        );

        $this->res->render('admin.reports.index', [
            'title'  => 'Analisa Tren Pasar',
            'report' => $report,
            'from'   => $from,
            'to'     => $to,
            'groupBy' => 'monthly',
        ], 'admin');
    }
}
