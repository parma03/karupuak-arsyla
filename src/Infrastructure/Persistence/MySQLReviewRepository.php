<?php
// src/Infrastructure/Persistence/MySQLReviewRepository.php
namespace Infrastructure\Persistence;

use Domain\Review\Entities\Review;
use Domain\Review\Repositories\ReviewRepositoryInterface;

class MySQLReviewRepository implements ReviewRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findByProductId(int $productId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt   = $this->pdo->prepare("
            SELECT r.*, u.name AS user_name
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            WHERE r.product_id=? AND r.is_visible=1
            ORDER BY r.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $productId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $perPage,   \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset,    \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Review::fromArray($r), $stmt->fetchAll());
    }

    public function findByOrderId(int $orderId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE order_id=?');
        $stmt->execute([$orderId]);
        return array_map(fn($r) => Review::fromArray($r), $stmt->fetchAll());
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $conditions = [];
        $params     = [];

        if (!empty($filters['product_id'])) {
            $conditions[] = 'r.product_id = :product_id';
            $params[':product_id'] = $filters['product_id'];
        }

        $where  = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name AS user_name, p.name AS product_name
            FROM reviews r
            JOIN users u    ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            $where
            ORDER BY r.created_at DESC
            LIMIT :lim OFFSET :off
        ");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => Review::fromArray($r), $stmt->fetchAll());
    }

    public function countByProduct(int $productId): int
    {
        if (!$productId) {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM reviews');
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM reviews WHERE product_id=?');
            $stmt->execute([$productId]);
        }
        return (int)$stmt->fetchColumn();
    }

    public function save(Review $review): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reviews (user_id,product_id,order_id,rating,comment,is_visible)
            VALUES (:user_id,:product_id,:order_id,:rating,:comment,:is_visible)
        ");
        $stmt->execute([
            ':user_id'    => $review->userId,
            ':product_id' => $review->productId,
            ':order_id'   => $review->orderId,
            ':rating'     => $review->rating,
            ':comment'    => $review->comment,
            ':is_visible' => (int)$review->isVisible,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM reviews WHERE id=?')->execute([$id]);
    }

    public function toggleVisibility(int $id, bool $visible): bool
    {
        return $this->pdo->prepare('UPDATE reviews SET is_visible=? WHERE id=?')
            ->execute([(int)$visible, $id]);
    }

    public function hasReviewed(int $userId, int $productId, int $orderId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM reviews WHERE user_id=? AND product_id=? AND order_id=?'
        );
        $stmt->execute([$userId, $productId, $orderId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getAverageRating(int $productId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT AVG(rating) AS avg, COUNT(*) AS count FROM reviews WHERE product_id=? AND is_visible=1'
        );
        $stmt->execute([$productId]);
        $row = $stmt->fetch();
        return ['avg' => round((float)$row['avg'], 2), 'count' => (int)$row['count']];
    }
}
