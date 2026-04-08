<?php
// app/Controllers/OrderController.php
namespace App\Controllers;

use Core\{Request, Response, Session, Middleware, Validator};
use Application\Commands\Order\{CreateOrderCommand, CreateOrderHandler};
use Application\Queries\Cart\{GetCartQuery, GetCartHandler};
use Application\Queries\Order\{GetOrderListQuery, GetOrderListHandler, GetOrderDetailQuery, GetOrderDetailHandler};
use Infrastructure\Persistence\MySQLCartRepository;

class OrderController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function checkoutForm(): void
    {
        Middleware::auth();

        $cart = (new GetCartHandler())->handle(new GetCartQuery(Session::userId()));
        if ($cart->isEmpty()) {
            $this->res->setFlash('warning', 'Keranjang Anda kosong.');
            $this->res->redirect('cart');
        }

        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        // Data user yang sudah login untuk pre-fill form
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([Session::userId()]);
        $user = $stmt->fetch();

        $this->res->render('checkout.index', [
            'title'     => 'Checkout — Karupuak Arsyla',
            'cart'      => $cart,
            'cartCount' => $cartCount,
            'user'      => $user,
            'couriers'  => ['jne', 'jnt', 'sicepat'],
        ]);
    }

    public function checkout(): void
    {
        Middleware::auth();

        $validator = new Validator();
        $data = [
            'name'            => $this->req->post('name'),
            'phone'           => $this->req->post('phone'),
            'address'         => $this->req->post('address'),
            'city_name'       => $this->req->post('city_name'),
            'province'        => $this->req->post('province') ?: 'Simulasi',
            'postal_code'     => $this->req->post('postal_code'),
            'courier'         => $this->req->post('courier'),
            'courier_service' => $this->req->post('courier_service'),
            'shipping_cost'   => $this->req->post('shipping_cost'),
            'notes'           => $this->req->post('notes'),
        ];

        if (!$validator->validate($data, [
            'name'            => 'required|min:2',
            'phone'           => 'required|min:10',
            'address'         => 'required|min:10',
            'city_name'       => 'required',
            'courier'         => 'required',
            'courier_service' => 'required',
            'shipping_cost'   => 'required|numeric',
        ])) {
            $this->res->setFlash('danger', 'Data pengiriman tidak lengkap.');
            $this->res->redirect('checkout');
        }

        try {
            $orderId = (new CreateOrderHandler())->handle(new CreateOrderCommand(
                userId: Session::userId(),
                name: $data['name'],
                phone: $data['phone'],
                address: $data['address'],
                cityId: 0,
                cityName: $data['city_name'],
                province: $data['province'],
                postalCode: $data['postal_code'] ?: '',
                courier: $data['courier'],
                courierService: $data['courier_service'],
                shippingCost: (float)$data['shipping_cost'],
                notes: $data['notes'] ?: null,
            ));

            $this->res->redirect('payment/' . $orderId);
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
            $this->res->redirect('checkout');
        }
    }

    public function success(): void
    {
        Middleware::auth();
        $orderId = (int)$this->req->get('order_id');
        $this->res->render('checkout.success', [
            'title'   => 'Pesanan Berhasil',
            'orderId' => $orderId,
        ]);
    }

    public function myOrders(): void
    {
        Middleware::auth();

        $result    = (new GetOrderListHandler())->handle(new GetOrderListQuery(
            userId: Session::userId(),
            page: (int)$this->req->get('page', 1),
            perPage: 10,
            status: $this->req->get('status'),
        ));
        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        $this->res->render('order.list', [
            'title'      => 'Pesanan Saya',
            'orders'     => $result['data'],
            'pagination' => $result,
            'cartCount'  => $cartCount,
        ]);
    }

    public function detail(string $id): void
    {
        Middleware::auth();

        try {
            $data = (new GetOrderDetailHandler())->handle(
                new GetOrderDetailQuery((int)$id, Session::userId())
            );
        } catch (\RuntimeException $e) {
            $this->res->render404();
        }

        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        // Cek sudah review belum
        $pdo = getDB();
        $reviewedProducts = [];
        foreach ($data['items'] as $item) {
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM reviews WHERE user_id=? AND product_id=? AND order_id=?'
            );
            $stmt->execute([Session::userId(), $item->productId, $data['order']->id]);
            if ((int)$stmt->fetchColumn() > 0) {
                $reviewedProducts[] = $item->productId;
            }
        }

        $this->res->render('order.detail', [
            'title'            => 'Detail Pesanan #' . $data['order']->orderCode,
            'order'            => $data['order'],
            'items'            => $data['items'],
            'payment'          => $data['payment'],
            'shipment'         => $data['shipment'],
            'cartCount'        => $cartCount,
            'reviewedProducts' => $reviewedProducts,
        ]);
    }

    public function tracking(string $id): void
    {
        Middleware::auth();

        try {
            $data = (new GetOrderDetailHandler())->handle(
                new GetOrderDetailQuery((int)$id, Session::userId())
            );
        } catch (\RuntimeException $e) {
            $this->res->render404();
        }

        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        $this->res->render('order.tracking', [
            'title'    => 'Lacak Pesanan #' . $data['order']->orderCode,
            'order'    => $data['order'],
            'shipment' => $data['shipment'],
            'cartCount' => $cartCount,
        ]);
    }
}
