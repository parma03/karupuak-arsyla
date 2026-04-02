<?php
// src/Infrastructure/Persistence/MySQLCartRepository.php
namespace Infrastructure\Persistence;

use Domain\Cart\Entities\Cart;
use Domain\Cart\Entities\CartItem;
use Domain\Cart\Repositories\CartRepositoryInterface;

class MySQLCartRepository implements CartRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function findByUserId(int $userId): ?Cart
    {
        $stmt = $this->pdo->prepare('SELECT * FROM carts WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? Cart::fromArray($row) : null;
    }

    public function getOrCreate(int $userId): Cart
    {
        $cart = $this->findByUserId($userId);
        if ($cart) return $cart;

        $this->pdo->prepare('INSERT INTO carts (user_id) VALUES (?)')->execute([$userId]);
        return Cart::fromArray(['id' => (int)$this->pdo->lastInsertId(), 'user_id' => $userId]);
    }

    public function getItems(int $cartId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ci.*, p.name AS product_name, p.image AS product_image,
                   p.stock, p.weight
            FROM cart_items ci
            JOIN products p ON p.id = ci.product_id
            WHERE ci.cart_id = ?
            ORDER BY ci.id ASC
        ");
        $stmt->execute([$cartId]);
        return array_map(fn($r) => CartItem::fromArray($r), $stmt->fetchAll());
    }

    public function addItem(int $cartId, int $productId, int $qty, float $price): bool
    {
        // Jika sudah ada, tambah qty
        $stmt = $this->pdo->prepare(
            'SELECT id, qty FROM cart_items WHERE cart_id=? AND product_id=?'
        );
        $stmt->execute([$cartId, $productId]);
        $existing = $stmt->fetch();

        if ($existing) {
            return $this->pdo->prepare(
                'UPDATE cart_items SET qty = qty + ? WHERE id = ?'
            )->execute([$qty, $existing['id']]);
        }

        return $this->pdo->prepare(
            'INSERT INTO cart_items (cart_id,product_id,qty,price) VALUES (?,?,?,?)'
        )->execute([$cartId, $productId, $qty, $price]);
    }

    public function updateItemQty(int $cartId, int $productId, int $qty): bool
    {
        return $this->pdo->prepare(
            'UPDATE cart_items SET qty=? WHERE cart_id=? AND product_id=?'
        )->execute([$qty, $cartId, $productId]);
    }

    public function removeItem(int $cartId, int $productId): bool
    {
        return $this->pdo->prepare(
            'DELETE FROM cart_items WHERE cart_id=? AND product_id=?'
        )->execute([$cartId, $productId]);
    }

    public function clearCart(int $cartId): bool
    {
        return $this->pdo->prepare('DELETE FROM cart_items WHERE cart_id=?')->execute([$cartId]);
    }

    public function itemCount(int $userId): int
    {
        $cart = $this->findByUserId($userId);
        if (!$cart) return 0;
        $stmt = $this->pdo->prepare('SELECT COALESCE(SUM(qty),0) FROM cart_items WHERE cart_id=?');
        $stmt->execute([$cart->id]);
        return (int)$stmt->fetchColumn();
    }
}
