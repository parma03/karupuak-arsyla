<?php
// app/Controllers/CartController.php
namespace App\Controllers;

use Core\{Request, Response, Session, Middleware};
use Application\Commands\Cart\{AddToCartCommand, AddToCartHandler, RemoveFromCartCommand, RemoveFromCartHandler, UpdateCartQtyCommand, UpdateCartQtyHandler};
use Application\Queries\Cart\{GetCartQuery, GetCartHandler};
use Infrastructure\Persistence\MySQLCartRepository;

class CartController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function index(): void
    {
        Middleware::auth();

        $cart      = (new GetCartHandler())->handle(new GetCartQuery(Session::userId()));
        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        $this->res->render('cart.index', [
            'title'     => 'Keranjang Belanja',
            'cart'      => $cart,
            'cartCount' => $cartCount,
        ]);
    }

    public function add(): void
    {
        // Jika belum login, redirect ke login
        if (!Session::isLoggedIn()) {
            if ($this->req->isAjax()) {
                $this->res->json(['success' => false, 'redirect' => APP_URL . '/login']);
            }
            $this->res->setFlash('warning', 'Silakan login untuk menambah ke keranjang.');
            $this->res->redirect('login');
        }

        $productId = (int)$this->req->post('product_id');
        $qty       = max(1, (int)$this->req->post('qty', 1));

        try {
            (new AddToCartHandler())->handle(new AddToCartCommand(Session::userId(), $productId, $qty));
            $count = (new MySQLCartRepository())->itemCount(Session::userId());

            if ($this->req->isAjax()) {
                $this->res->json(['success' => true, 'cart_count' => $count, 'message' => 'Produk ditambahkan ke keranjang!']);
            }
            $this->res->setFlash('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\RuntimeException $e) {
            if ($this->req->isAjax()) {
                $this->res->json(['success' => false, 'message' => $e->getMessage()]);
            }
            $this->res->setFlash('danger', $e->getMessage());
        }

        $this->res->redirect('cart');
    }

    public function update(): void
    {
        Middleware::auth();

        $productId = (int)$this->req->post('product_id');
        $qty       = (int)$this->req->post('qty');

        try {
            (new UpdateCartQtyHandler())->handle(new UpdateCartQtyCommand(Session::userId(), $productId, $qty));
            $cart  = (new GetCartHandler())->handle(new GetCartQuery(Session::userId()));
            $count = (new MySQLCartRepository())->itemCount(Session::userId());

            if ($this->req->isAjax()) {
                $this->res->json([
                    'success'    => true,
                    'cart_count' => $count,
                    'subtotal'   => 'Rp ' . number_format($cart->subtotal(), 0, ',', '.'),
                ]);
            }
        } catch (\RuntimeException $e) {
            if ($this->req->isAjax()) {
                $this->res->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        $this->res->redirect('cart');
    }

    public function remove(): void
    {
        Middleware::auth();

        $productId = (int)$this->req->post('product_id');

        try {
            (new RemoveFromCartHandler())->handle(new RemoveFromCartCommand(Session::userId(), $productId));
            $count = (new MySQLCartRepository())->itemCount(Session::userId());

            if ($this->req->isAjax()) {
                $this->res->json(['success' => true, 'cart_count' => $count]);
            }
        } catch (\RuntimeException $e) {
            if ($this->req->isAjax()) {
                $this->res->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        $this->res->setFlash('success', 'Produk dihapus dari keranjang.');
        $this->res->redirect('cart');
    }
}
