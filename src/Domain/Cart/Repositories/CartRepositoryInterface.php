<?php
// src/Domain/Cart/Repositories/CartRepositoryInterface.php
namespace Domain\Cart\Repositories;

use Domain\Cart\Entities\Cart;
use Domain\Cart\Entities\CartItem;

interface CartRepositoryInterface
{
    public function findByUserId(int $userId): ?Cart;
    public function getOrCreate(int $userId): Cart;
    public function addItem(int $cartId, int $productId, int $qty, float $price): bool;
    public function updateItemQty(int $cartId, int $productId, int $qty): bool;
    public function removeItem(int $cartId, int $productId): bool;
    public function getItems(int $cartId): array; // CartItem[]
    public function clearCart(int $cartId): bool;
    public function itemCount(int $userId): int;
}
