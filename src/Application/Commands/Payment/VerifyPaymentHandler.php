<?php
// src/Application/Commands/Payment/VerifyPaymentHandler.php
namespace Application\Commands\Payment;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLOrderRepository;
use Infrastructure\Payment\MidtransService;

class VerifyPaymentHandler implements CommandHandlerInterface
{
    private MySQLOrderRepository $orderRepo;
    private MidtransService      $midtrans;

    public function __construct()
    {
        $this->orderRepo = new MySQLOrderRepository();
        $this->midtrans  = new MidtransService();
    }

    public function handle(object $command): bool
    {
        /** @var VerifyPaymentCommand $command */
        $payload = $command->payload;

        // 1. Verifikasi signature key Midtrans
        if (!$this->midtrans->verifySignature($payload)) {
            throw new \RuntimeException('Signature tidak valid.');
        }

        $orderCode         = $payload['order_id']          ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status']       ?? '';
        $paymentType       = $payload['payment_type']       ?? '';
        $transactionId     = $payload['transaction_id']     ?? '';
        $grossAmount       = (float)($payload['gross_amount'] ?? 0);

        // 2. Temukan order
        $order = $this->orderRepo->findByCode($orderCode);
        if (!$order) {
            throw new \RuntimeException("Order tidak ditemukan: $orderCode");
        }

        // 3. Tentukan status
        $paymentStatus = 'pending';
        $orderStatus   = $order->status;

        if ($transactionStatus === 'capture') {
            $paymentStatus = ($fraudStatus === 'accept') ? 'paid' : 'pending';
            $orderStatus   = ($fraudStatus === 'accept') ? 'processing' : $orderStatus;
        } elseif ($transactionStatus === 'settlement') {
            $paymentStatus = 'paid';
            $orderStatus   = 'processing';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $paymentStatus = 'failed';
            $orderStatus   = 'cancelled';
        }

        // 4. Update tabel payments
        $pdo = getDB();
        $pdo->prepare("
            UPDATE payments
            SET status=:status, payment_type=:type, transaction_id=:tid,
                response_json=:json,
                paid_at = CASE WHEN :status2='paid' THEN NOW() ELSE paid_at END,
                updated_at=NOW()
            WHERE midtrans_order_id=:order_code
        ")->execute([
            ':status'     => $transactionStatus,
            ':type'       => $paymentType,
            ':tid'        => $transactionId,
            ':json'       => json_encode($payload),
            ':status2'    => $paymentStatus,
            ':order_code' => $orderCode,
        ]);

        // 5. Update status order
        $this->orderRepo->updatePaymentStatus($order->id, $paymentStatus);
        if ($orderStatus !== $order->status) {
            $this->orderRepo->updateStatus($order->id, $orderStatus);
        }

        return true;
    }
}
