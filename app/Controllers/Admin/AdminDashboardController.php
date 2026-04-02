<?php
// app/Controllers/Admin/AdminDashboardController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Session, Middleware};
use Application\Commands\Auth\{LoginCommand, LoginHandler};

class AdminDashboardController
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
        Middleware::admin();

        $pdo = getDB();

        // Stats cards
        $stats = [
            'total_orders'    => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'paid_orders'     => $pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status='paid'")->fetchColumn(),
            'total_revenue'   => $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status='paid'")->fetchColumn(),
            'total_customers' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(),
            'total_products'  => $pdo->query("SELECT COUNT(*) FROM products WHERE is_active=1")->fetchColumn(),
            'low_stock'       => $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 5 AND is_active=1")->fetchColumn(),
            'pending_orders'  => $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn(),
            'pending_payment' => $pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status='unpaid' AND status != 'cancelled'")->fetchColumn(),
        ];

        // Grafik 7 hari terakhir
        $trend = $pdo->query("
            SELECT DATE(created_at) AS day, COUNT(*) AS orders, COALESCE(SUM(total),0) AS revenue
            FROM orders WHERE payment_status='paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY day ORDER BY day ASC
        ")->fetchAll();

        // Order terbaru
        $recentOrders = $pdo->query("
            SELECT o.*, u.name AS customer_name
            FROM orders o LEFT JOIN users u ON u.id=o.user_id
            ORDER BY o.created_at DESC LIMIT 8
        ")->fetchAll();

        // Produk terlaris bulan ini
        $topProducts = $pdo->query("
            SELECT p.name, SUM(oi.qty) AS sold, SUM(oi.subtotal) AS revenue
            FROM order_items oi
            JOIN orders o  ON o.id  = oi.order_id
            JOIN products p ON p.id = oi.product_id
            WHERE o.created_at >= DATE_FORMAT(NOW(),'%Y-%m-01') AND o.payment_status='paid'
            GROUP BY p.id ORDER BY sold DESC LIMIT 5
        ")->fetchAll();

        $this->res->render('admin.dashboard', [
            'title'        => 'Dashboard Admin',
            'stats'        => $stats,
            'trend'        => $trend,
            'recentOrders' => $recentOrders,
            'topProducts'  => $topProducts,
        ], 'admin');
    }

    public function loginForm(): void
    {
        if (Session::isLoggedIn() && Session::isAdmin()) {
            $this->res->redirect('admin');
        }
        $this->res->render('admin.login', ['title' => 'Login Admin'], 'auth');
    }

    public function login(): void
    {
        $email    = $this->req->post('email');
        $password = $this->req->post('password');

        try {
            $user = (new LoginHandler())->handle(new LoginCommand($email, $password));
            if (!in_array($user['role'], ['admin', 'owner'])) {
                throw new \RuntimeException('Akses ditolak.');
            }
            Session::login($user);
            $this->res->redirect('admin');
        } catch (\RuntimeException $e) {
            $this->res->render('admin.login', [
                'title' => 'Login Admin',
                'error' => $e->getMessage(),
            ], 'auth');
        }
    }

    public function logout(): void
    {
        Session::destroy();
        $this->res->redirect('admin/login');
    }
}
