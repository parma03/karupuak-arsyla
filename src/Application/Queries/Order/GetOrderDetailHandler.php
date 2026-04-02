<?php
// src/Application/Queries/Order/GetOrderDetailHandler.php
namespace Application\Queries\Order;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLOrderRepository;

class GetOrderDetailHandler implements QueryHandlerInterface
{
    private MySQLOrderRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLOrderRepository();
    }

    public function handle(object $query): array
    {
        /** @var GetOrderDetailQuery $query */
        $order = $this->repo->findById($query->orderId);

        if (!$order) {
            throw new \RuntimeException('Order tidak ditemukan.');
        }

        // Pastikan customer hanya bisa lihat ordernya sendiri
        if ($query->userId !== null && $order->userId !== $query->userId) {
            throw new \RuntimeException('Akses ditolak.');
        }

        $items    = $this->repo->getItems($query->orderId);
        $payment  = $this->getPayment($query->orderId);
        $shipment = $this->getShipment($query->orderId);

        return [
            'order'    => $order,
            'items'    => $items,
            'payment'  => $payment,
            'shipment' => $shipment,
        ];
    }

    private function getPayment(int $orderId): ?array
    {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT * FROM payments WHERE order_id = ? LIMIT 1');
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }

    private function getShipment(int $orderId): ?array
    {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT * FROM shipments WHERE order_id = ? LIMIT 1');
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }
}
