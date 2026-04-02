<?php
// src/Domain/Order/Entities/OrderItem.php
namespace Domain\Order\Entities;

class OrderItem
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $orderId,
        public readonly int     $productId,
        public readonly string  $productName,
        public readonly ?string $productImage,
        public readonly int     $qty,
        public readonly float   $price,
        public readonly float   $subtotal,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            orderId: (int)$data['order_id'],
            productId: (int)$data['product_id'],
            productName: $data['product_name'],
            productImage: $data['product_image'] ?? null,
            qty: (int)$data['qty'],
            price: (float)$data['price'],
            subtotal: (float)$data['subtotal'],
        );
    }
}
