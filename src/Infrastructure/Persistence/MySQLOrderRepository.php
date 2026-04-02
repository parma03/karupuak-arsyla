<?php
// src/Infrastructure/Persistence/MySQLOrderRepository.php
namespace Infrastructure\Persistence;

use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;
use Domain\Order\Repositories\OrderRepositoryInterface;

class MySQLOrderRepository implements OrderRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findById(int $id): ?Order
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Order::fromArray($row) : null;
    }

    public function findByCode(string $code): ?Order
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE order_code = ? LIMIT 1');
        $stmt->execute([$code]);
        $row = $stmt->fetch();
        return $row ? Order::fromArray($row) : null;
    }

    public function findByUserId(int $userId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt   = $this->pdo->prepare(
            'SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC LIMIT ? OFFSET ?'
        );
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Order::fromArray($r), $stmt->fetchAll());
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        [$where, $params] = $this->buildWhere($filters);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT o.*, u.name AS customer_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             $where ORDER BY o.created_at DESC LIMIT :lim OFFSET :off"
        );
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Order::fromArray($r), $stmt->fetchAll());
    }

    public function countAll(array $filters = []): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders o $where");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function save(Order $order): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO orders
                (order_code,user_id,name,phone,address,city_id,city_name,province,postal_code,
                 courier,courier_service,shipping_cost,subtotal,total,notes,status,payment_status)
            VALUES
                (:order_code,:user_id,:name,:phone,:address,:city_id,:city_name,:province,:postal_code,
                 :courier,:courier_service,:shipping_cost,:subtotal,:total,:notes,:status,:payment_status)
        ");
        $stmt->execute([
            ':order_code'      => $order->orderCode,
            ':user_id'         => $order->userId,
            ':name'            => $order->name,
            ':phone'           => $order->phone,
            ':address'         => $order->address,
            ':city_id'         => $order->cityId,
            ':city_name'       => $order->cityName,
            ':province'        => $order->province,
            ':postal_code'     => $order->postalCode,
            ':courier'         => $order->courier,
            ':courier_service' => $order->courierService,
            ':shipping_cost'   => $order->shippingCost,
            ':subtotal'        => $order->subtotal,
            ':total'           => $order->total,
            ':notes'           => $order->notes,
            ':status'          => $order->status,
            ':payment_status'  => $order->paymentStatus,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function saveItems(int $orderId, array $items): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO order_items (order_id,product_id,product_name,product_image,qty,price,subtotal)
            VALUES (:order_id,:product_id,:product_name,:product_image,:qty,:price,:subtotal)
        ");
        foreach ($items as $item) {
            $stmt->execute([
                ':order_id'      => $orderId,
                ':product_id'    => $item['product_id'],
                ':product_name'  => $item['product_name'],
                ':product_image' => $item['product_image'] ?? null,
                ':qty'           => $item['qty'],
                ':price'         => $item['price'],
                ':subtotal'      => $item['subtotal'],
            ]);
        }
        return true;
    }

    public function getItems(int $orderId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC'
        );
        $stmt->execute([$orderId]);
        return array_map(fn($r) => OrderItem::fromArray($r), $stmt->fetchAll());
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->pdo->prepare('UPDATE orders SET status=? WHERE id=?')
            ->execute([$status, $id]);
    }

    public function updatePaymentStatus(int $id, string $status): bool
    {
        return $this->pdo->prepare('UPDATE orders SET payment_status=? WHERE id=?')
            ->execute([$status, $id]);
    }

    public function getSalesSummary(string $from, string $to): array
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS orders, SUM(total) AS revenue
            FROM orders
            WHERE payment_status='paid' AND created_at BETWEEN ? AND ?
        ");
        $stmt->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
        return $stmt->fetch();
    }

    public function getSalesTrend(string $period = 'monthly'): array
    {
        $fmt = $period === 'monthly' ? '%Y-%m' : '%Y-%m-%d';
        $stmt = $this->pdo->prepare("
            SELECT DATE_FORMAT(created_at,'$fmt') AS period, COUNT(*) AS orders, SUM(total) AS revenue
            FROM orders WHERE payment_status='paid'
            GROUP BY period ORDER BY period ASC LIMIT 12
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function buildWhere(array $filters): array
    {
        $conditions = [];
        $params     = [];

        if (!empty($filters['user_id'])) {
            $conditions[] = 'o.user_id = :user_id';
            $params[':user_id'] = $filters['user_id'];
        }
        if (!empty($filters['status'])) {
            $conditions[] = 'o.status = :status';
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $conditions[] = '(o.order_code LIKE :s OR o.name LIKE :s2)';
            $params[':s']  = '%' . $filters['search'] . '%';
            $params[':s2'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['from'])) {
            $conditions[] = 'o.created_at >= :from';
            $params[':from'] = $filters['from'] . ' 00:00:00';
        }
        if (!empty($filters['to'])) {
            $conditions[] = 'o.created_at <= :to';
            $params[':to'] = $filters['to'] . ' 23:59:59';
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        return [$where, $params];
    }
}
