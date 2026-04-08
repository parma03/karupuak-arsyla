<?php
// core/Application.php
namespace Core;

class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $r = $this->router;

        // ── PUBLIC ROUTES ──────────────────────────────────────────
        $r->get('',                       'HomeController@index');
        $r->get('home',                   'HomeController@index');
        $r->get('product/{id}',           'HomeController@productDetail');
        $r->get('product/{id}/reviews',   'HomeController@productReviews');

        // ── AUTH ROUTES ────────────────────────────────────────────
        $r->get('login',                  'AuthController@loginForm');
        $r->post('login',                 'AuthController@login');
        $r->get('register',               'AuthController@registerForm');
        $r->post('register',              'AuthController@register');
        $r->get('logout',                 'AuthController@logout');

        // ── CART ROUTES (memerlukan login) ─────────────────────────
        $r->post('cart/add',              'CartController@add');
        $r->get('cart',                   'CartController@index');
        $r->post('cart/update',           'CartController@update');
        $r->post('cart/remove',           'CartController@remove');

        // ── CHECKOUT & ORDER ───────────────────────────────────────
        $r->get('checkout',               'OrderController@checkoutForm');
        $r->post('checkout',              'OrderController@checkout');
        $r->get('checkout/success',       'OrderController@success');
        $r->get('orders',                 'OrderController@myOrders');
        $r->get('orders/{id}',            'OrderController@detail');
        $r->get('orders/{id}/tracking',   'OrderController@tracking');

        // ── PAYMENT ───────────────────────────────────────────────
        // PENTING: route statis harus didaftarkan SEBELUM route dinamis
        $r->get('payment/finish',         'PaymentController@finish');
        $r->post('payment/callback',      'PaymentController@callback');  // Midtrans webhook
        $r->get('payment/{orderId}',      'PaymentController@pay');

        // ── REVIEW ────────────────────────────────────────────────
        $r->get('review/{orderId}',       'ReviewController@form');
        $r->post('review/{orderId}',      'ReviewController@submit');

        // ── SHIPPING (AJAX) ───────────────────────────────────────
        $r->post('shipping/check',        'ShippingController@check');

        // ── ADMIN ROUTES ───────────────────────────────────────────
        $r->get('admin',                  'Admin\AdminDashboardController@index');
        $r->get('admin/login',            'Admin\AdminDashboardController@loginForm');
        $r->post('admin/login',           'Admin\AdminDashboardController@login');
        $r->get('admin/logout',           'Admin\AdminDashboardController@logout');

        // Admin — Users
        $r->get('admin/users',            'Admin\AdminUserController@index');
        $r->get('admin/users/create',     'Admin\AdminUserController@create');
        $r->post('admin/users/create',    'Admin\AdminUserController@store');
        $r->get('admin/users/{id}/edit',  'Admin\AdminUserController@edit');
        $r->post('admin/users/{id}/edit', 'Admin\AdminUserController@update');
        $r->post('admin/users/{id}/delete', 'Admin\AdminUserController@delete');

        // Admin — Categories
        $r->get('admin/categories',                 'Admin\AdminCategoryController@index');
        $r->post('admin/categories/store',          'Admin\AdminCategoryController@store');
        $r->post('admin/categories/{id}/update',    'Admin\AdminCategoryController@update');
        $r->post('admin/categories/{id}/delete',    'Admin\AdminCategoryController@delete');

        // Admin — Products
        $r->get('admin/products',                   'Admin\AdminProductController@index');
        $r->get('admin/products/create',            'Admin\AdminProductController@create');
        $r->post('admin/products/create',           'Admin\AdminProductController@store');
        $r->get('admin/products/{id}/edit',         'Admin\AdminProductController@edit');
        $r->post('admin/products/{id}/edit',        'Admin\AdminProductController@update');
        $r->post('admin/products/{id}/delete',      'Admin\AdminProductController@delete');

        // Admin — Orders
        $r->get('admin/orders',                     'Admin\AdminOrderController@index');
        $r->get('admin/orders/{id}',                'Admin\AdminOrderController@detail');
        $r->post('admin/orders/{id}/status',        'Admin\AdminOrderController@updateStatus');

        // Admin — Payments (Verifikasi)
        $r->get('admin/payments',                   'Admin\AdminPaymentController@index');
        $r->post('admin/payments/{id}/verify',      'Admin\AdminPaymentController@verify');

        // Admin — Shipping
        $r->get('admin/shipping',                   'Admin\AdminShippingController@index');
        $r->post('admin/shipping/{id}/update',      'Admin\AdminShippingController@update');

        // Admin — Reviews
        $r->get('admin/reviews',                    'Admin\AdminReviewController@index');
        $r->post('admin/reviews/{id}/delete',       'Admin\AdminReviewController@delete');

        // Admin — Reports
        $r->get('admin/reports',                    'Admin\AdminReportController@index');
        $r->get('admin/reports/print',              'Admin\AdminReportController@print');
        $r->get('admin/reports/export',             'Admin\AdminReportController@export');
        $r->get('admin/reports/trends',             'Admin\AdminReportController@trends');

        // Owner — (sama dengan admin, hanya read)
        $r->get('owner',                            'OwnerController@dashboard');
        $r->get('owner/reports',                    'OwnerController@reports');
        $r->get('owner/trends',                     'OwnerController@trends');
    }

    public function run(): void
    {
        Session::start();

        $request  = new Request();
        $response = new Response();

        $this->router->dispatch($request, $response);
    }
}