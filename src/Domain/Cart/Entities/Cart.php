<?php
// src/Domain/Cart/Entities/Cart.php
namespace Domain\Cart\Entities;

class Cart
{
    /** @var CartItem[] */
    public array $items = [];

    public function __construct(
        public readonly ?int $id,
        public readonly int  $userId,
    ) {}

    public function totalItems(): int
    {
        return array_sum(array_map(fn($i) => $i->qty, $this->items));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(fn($i) => $i->subtotal(), $this->items));
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: (int)$data['user_id'],
        );
    }
}
