<?php
// app/Controllers/PaymentController.php
namespace App\Controllers;

use Core\{Request, Response, Session, Middleware};
use Application\Commands\Payment\{CreatePaymentCommand, CreatePaymentHandler, VerifyPaymentCommand, VerifyPaymentHandler};
use Application\Queries\Order\{GetOrderDetailQuery, GetOrderDetailHandler};
use Infrastructure\Persistence\MySQLCartRepository;

class PaymentController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function pay(string $orderId): void
    {
        Middleware::auth();

        try {
            $detail = (new GetOrderDetailHandler())->handle(
                new GetOrderDetailQuery((int)$orderId, Session::userId())
            );
        } catch (\RuntimeException $e) {
            $this->res->render404();
        }

        $order = $detail['order'];

        if ($order->paymentStatus === 'paid') {
            $this->res->setFlash('info', 'Pesanan ini sudah dibayar.');
            $this->res->redirect('orders/' . $orderId);
        }

        try {
            $snapToken = (new CreatePaymentHandler())->handle(
                new CreatePaymentCommand((int)$orderId, Session::userId())
            );
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
            $this->res->redirect('orders');
        }

        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        $this->res->render('checkout.payment', [
            'title'     => 'Pembayaran — #' . $order->orderCode,
            'order'     => $order,
            'items'     => $detail['items'],
            'snapToken' => $snapToken,
            'cartCount' => $cartCount,
        ]);
    }

    /** Midtrans Webhook — harus PUBLIC (tanpa auth) */
    public function callback(): void
    {
        // Baca raw JSON dari Midtrans
        $raw     = file_get_contents('php://input');
        $payload = json_decode($raw, true) ?? [];

        if (empty($payload)) {
            http_response_code(400);
            echo json_encode(['message' => 'Empty payload']);
            exit;
        }

        try {
            (new VerifyPaymentHandler())->handle(new VerifyPaymentCommand($payload));
            http_response_code(200);
            echo json_encode(['message' => 'OK']);
        } catch (\RuntimeException $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
        exit;
    }

    public function finish(): void
    {
        $orderId = $this->req->get('order_id');
        $status  = $this->req->get('transaction_status');

        if ($status === 'settlement' || $status === 'capture') {
            $this->res->setFlash('success', 'Pembayaran berhasil! Terima kasih.');
            $this->res->redirect('checkout/success?order_id=' . $orderId);
        } else {
            $this->res->setFlash('warning', 'Pembayaran sedang diproses atau dibatalkan.');
            $this->res->redirect('orders');
        }
    }
}
