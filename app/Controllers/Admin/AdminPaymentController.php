<?php
// app/Controllers/Admin/AdminPaymentController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};

class AdminPaymentController
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
        $limit = 20;
        $off  = ($page - 1) * $limit;

        $payments = $pdo->query("
            SELECT p.*, o.order_code, o.total, u.name AS customer_name
            FROM payments p
            JOIN orders o ON o.id = p.order_id
            JOIN users u  ON u.id = o.user_id
            ORDER BY p.created_at DESC
            LIMIT $limit OFFSET $off
        ")->fetchAll();

        $total = (int)$pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();

        $this->res->render('admin.payments.index', [
            'title'      => 'Verifikasi Pembayaran',
            'payments'   => $payments,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => ceil($total / $limit),
        ], 'admin');
    }

    public function verify(string $id): void
    {
        $pdo = getDB();
        // Manual verifikasi (pembayaran transfer bank manual)
        $payment = $pdo->prepare('SELECT * FROM payments WHERE id=?');
        $payment->execute([$id]);
        $p = $payment->fetch();

        if (!$p) {
            $this->res->setFlash('danger', 'Pembayaran tidak ditemukan.');
            $this->res->redirect('admin/payments');
        }

        $pdo->prepare("UPDATE payments SET status='settlement', paid_at=NOW() WHERE id=?")
            ->execute([$id]);
        $pdo->prepare("UPDATE orders SET payment_status='paid', status='processing' WHERE id=?")
            ->execute([$p['order_id']]);

        $this->res->setFlash('success', 'Pembayaran berhasil diverifikasi.');
        $this->res->redirect('admin/payments');
    }
}
