<?php
// app/Controllers/OwnerController.php
namespace App\Controllers;

use Core\{Request, Response, Middleware};
use Application\Queries\Report\{GetSalesReportQuery, GetSalesReportHandler};

class OwnerController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
        Middleware::owner();
    }

    public function dashboard(): void
    {
        $this->res->redirect('admin');
    }

    public function reports(): void
    {
        $from   = $this->req->get('from', date('Y-m-01'));
        $to     = $this->req->get('to',   date('Y-m-d'));
        $report = (new GetSalesReportHandler())->handle(
            new GetSalesReportQuery($from, $to, 'monthly')
        );

        $this->res->render('admin.reports.index', [
            'title'  => 'Laporan Penjualan',
            'report' => $report,
            'from'   => $from,
            'to'     => $to,
            'groupBy' => 'monthly',
        ], 'admin');
    }

    public function trends(): void
    {
        $this->reports();
    }
}
