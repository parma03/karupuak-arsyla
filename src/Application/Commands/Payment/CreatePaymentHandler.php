<?php
// src/Application/Commands/Payment/CreatePaymentHandler.php
namespace Application\Commands\Payment;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLOrderRepository;
use Infrastructure\Persistence\MySQLUserRepository;
use Infrastructure\Payment\MidtransService;

class CreatePaymentHandler implements CommandHandlerInterface
{
    private MySQLOrderRepository $orderRepo;
    private MySQLUserRepository  $userRepo;
    private MidtransService      $midtrans;

    public function __construct()
    {
        $this->orderRepo = new MySQLOrderRepository();
        $this->userRepo  = new MySQLUserRepository();
        $this->midtrans  = new MidtransService();
    }

    /** @return string Snap token dari Midtrans */
    public function handle(object $command): string
    {
        /** @var CreatePaymentCommand $command */
        $order = $this->orderRepo->findById($command->orderId);
        if (!$order) {
            throw new \RuntimeException('Order tidak ditemukan.');
        }

        if ($order->userId !== $command->userId) {
            throw new \RuntimeException('Akses ditolak.');
        }

        if ($order->paymentStatus === 'paid') {
            throw new \RuntimeException('Order sudah dibayar.');
        }

        $user  = $this->userRepo->findById($command->userId);
        $items = $this->orderRepo->getItems($command->orderId);

        // Buat atau ambil snap token
        $snapToken = $this->midtrans->createSnapToken($order, $user, $items);

        // Simpan snap token ke tabel payments
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO payments (order_id, midtrans_order_id, snap_token, gross_amount, status)
            VALUES (:order_id, :mid_order_id, :snap_token, :amount, 'pending')
            ON DUPLICATE KEY UPDATE snap_token = VALUES(snap_token), updated_at = NOW()
        ");
        $stmt->execute([
            ':order_id'    => $order->id,
            ':mid_order_id' => $order->orderCode,
            ':snap_token'  => $snapToken,
            ':amount'      => $order->total,
        ]);

        return $snapToken;
    }
}
