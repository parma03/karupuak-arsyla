<?php
// src/Domain/Review/Entities/Review.php
namespace Domain\Review\Entities;

class Review
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $userId,
        public readonly int     $productId,
        public readonly int     $orderId,
        public readonly int     $rating,
        public readonly ?string $comment,
        public readonly bool    $isVisible,
        public readonly string  $createdAt,
        public readonly ?string $userName    = null,
        public readonly ?string $productName = null,
    ) {}

    public function stars(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: (int)$data['user_id'],
            productId: (int)$data['product_id'],
            orderId: (int)$data['order_id'],
            rating: (int)$data['rating'],
            comment: $data['comment'] ?? null,
            isVisible: (bool)($data['is_visible'] ?? true),
            createdAt: $data['created_at'] ?? date('Y-m-d H:i:s'),
            userName: $data['user_name'] ?? null,
            productName: $data['product_name'] ?? null,
        );
    }
}
