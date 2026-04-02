<?php
// src/Application/Commands/Order/UpdateOrderStatusHandler.php
namespace Application\Commands\Order;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLOrderRepository;

class UpdateOrderStatusHandler implements CommandHandlerInterface
{
    private MySQLOrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderRepo = new MySQLOrderRepository();
    }

    public function handle(object $command): bool
    {
        /** @var UpdateOrderStatusCommand $command */
        $allowed = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

        if (!in_array($command->status, $allowed)) {
            throw new \RuntimeException('Status tidak valid.');
        }

        $order = $this->orderRepo->findById($command->orderId);
        if (!$order) {
            throw new \RuntimeException('Order tidak ditemukan.');
        }

        $this->orderRepo->updateStatus($command->orderId, $command->status);

        // Jika shipped, update/insert shipment tracking
        if ($command->status === 'shipped' && $command->trackingNumber) {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                INSERT INTO shipments (order_id, courier, tracking_number, status, shipped_at)
                VALUES (:order_id, :courier, :tracking, 'shipped', NOW())
                ON DUPLICATE KEY UPDATE
                    tracking_number = VALUES(tracking_number),
                    status = 'shipped',
                    shipped_at = NOW()
            ");
            $stmt->execute([
                ':order_id' => $command->orderId,
                ':courier'  => $order->courier,
                ':tracking' => $command->trackingNumber,
            ]);
        }

        if ($command->status === 'delivered') {
            $pdo = getDB();
            $pdo->prepare("UPDATE shipments SET status='delivered', delivered_at=NOW() WHERE order_id=?")
                ->execute([$command->orderId]);
        }

        return true;
    }
}
