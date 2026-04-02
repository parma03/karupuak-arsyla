<?php
// src/Domain/Cart/Entities/CartItem.php
namespace Domain\Cart\Entities;

class CartItem
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $cartId,
        public readonly int     $productId,
        public int              $qty,
        public readonly float   $price,
        public readonly ?string $productName  = null,
        public readonly ?string $productImage = null,
        public readonly ?int    $productStock = null,
        public readonly ?int    $productWeight = null,
    ) {}

    public function subtotal(): float
    {
        return $this->price * $this->qty;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            cartId: (int)$data['cart_id'],
            productId: (int)$data['product_id'],
            qty: (int)$data['qty'],
            price: (float)$data['price'],
            productName: $data['product_name'] ?? null,
            productImage: $data['product_image'] ?? null,
            productStock: isset($data['stock']) ? (int)$data['stock'] : null,
            productWeight: isset($data['weight']) ? (int)$data['weight'] : null,
        );
    }
}
