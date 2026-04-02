<?php
// app/Controllers/Admin/AdminShippingController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};
use Application\Commands\Order\{UpdateOrderStatusCommand, UpdateOrderStatusHandler};

class AdminShippingController
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
        $pdo  = getDB();
        $page = max(1, (int)$this->req->get('page', 1));
        $lim  = 20;
        $off  = ($page - 1) * $lim;

        $shipments = $pdo->query("
            SELECT s.*, o.order_code, o.name, o.city_name, o.courier
            FROM shipments s
            JOIN orders o ON o.id = s.order_id
            ORDER BY s.created_at DESC
            LIMIT $lim OFFSET $off
        ")->fetchAll();

        $this->res->render('admin.shipping.index', [
            'title'     => 'Update Pengiriman',
            'shipments' => $shipments,
        ], 'admin');
    }

    public function update(string $id): void
    {
        // $id = order_id
        try {
            (new UpdateOrderStatusHandler())->handle(new UpdateOrderStatusCommand(
                orderId: (int)$id,
                status: $this->req->post('status'),
                trackingNumber: $this->req->post('tracking_number'),
            ));
            $this->res->setFlash('success', 'Status pengiriman diupdate.');
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
        }
        $this->res->redirect('admin/shipping');
    }
}
