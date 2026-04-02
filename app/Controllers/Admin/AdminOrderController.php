<?php
// app/Controllers/Admin/AdminOrderController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};
use Application\Commands\Order\{UpdateOrderStatusCommand, UpdateOrderStatusHandler};
use Application\Queries\Order\{GetOrderListQuery, GetOrderListHandler, GetOrderDetailQuery, GetOrderDetailHandler};

class AdminOrderController
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
        $result = (new GetOrderListHandler())->handle(new GetOrderListQuery(
            page: (int)$this->req->get('page', 1),
            perPage: 20,
            status: $this->req->get('status'),
            search: $this->req->get('q'),
            from: $this->req->get('from'),
            to: $this->req->get('to'),
        ));
        $this->res->render('admin.orders.index', [
            'title'      => 'Kelola Pesanan',
            'orders'     => $result['data'],
            'pagination' => $result,
        ], 'admin');
    }

    public function detail(string $id): void
    {
        $data = (new GetOrderDetailHandler())->handle(new GetOrderDetailQuery((int)$id));
        $this->res->render('admin.orders.detail', [
            'title'    => 'Detail Pesanan #' . $data['order']->orderCode,
            'order'    => $data['order'],
            'items'    => $data['items'],
            'payment'  => $data['payment'],
            'shipment' => $data['shipment'],
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        try {
            (new UpdateOrderStatusHandler())->handle(new UpdateOrderStatusCommand(
                orderId: (int)$id,
                status: $this->req->post('status'),
                trackingNumber: $this->req->post('tracking_number'),
                notes: $this->req->post('notes'),
            ));
            $this->res->setFlash('success', 'Status pesanan diupdate.');
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
        }
        $this->res->redirect('admin/orders/' . $id);
    }
}
