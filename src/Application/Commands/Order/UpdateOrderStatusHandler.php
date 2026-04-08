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

        $pdo = getDB();

        // Saat status processing → buat entri shipment awal dengan status 'packing'
        if ($command->status === 'processing') {
            $tracking = $command->trackingNumber ?? '';
            $stmt = $pdo->prepare("
                INSERT INTO shipments (order_id, courier, tracking_number, status)
                VALUES (?, ?, ?, 'packing')
                ON DUPLICATE KEY UPDATE
                    status = IF(status = 'packing', 'packing', status)
            ");
            $stmt->execute([$command->orderId, $order->courier, $tracking]);
        }

        // Saat status shipped → selalu insert/update shipment (tracking boleh kosong)
        if ($command->status === 'shipped') {
            $tracking = $command->trackingNumber ?? '';
            $stmt = $pdo->prepare("
                INSERT INTO shipments (order_id, courier, tracking_number, status, shipped_at)
                VALUES (?, ?, ?, 'shipped', NOW())
                ON DUPLICATE KEY UPDATE
                    tracking_number = IF(? <> '', ?, tracking_number),
                    status          = 'shipped',
                    shipped_at      = NOW()
            ");
            $stmt->execute([
                $command->orderId,
                $order->courier,
                $tracking,
                $tracking,
                $tracking,
            ]);
        }

        // Saat status delivered → pastikan shipment ada dan tandai delivered
        if ($command->status === 'delivered') {
            $tracking = $command->trackingNumber ?? '';
            $stmt = $pdo->prepare("
                INSERT INTO shipments (order_id, courier, tracking_number, status, shipped_at, delivered_at)
                VALUES (?, ?, ?, 'delivered', NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    status       = 'delivered',
                    delivered_at = NOW()
            ");
            $stmt->execute([$command->orderId, $order->courier, $tracking]);
        }

        return true;
    }
}
