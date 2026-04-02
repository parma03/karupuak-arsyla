<?php
// src/Domain/Category/Entities/Category.php
namespace Domain\Category\Entities;

class Category
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $name,
        public readonly string  $slug,
        public readonly ?string $description,
        public readonly ?string $image,
        public readonly bool    $isActive,
        public readonly int     $sortOrder,
        public readonly int     $productCount = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            isActive: (bool)($data['is_active'] ?? true),
            sortOrder: (int)($data['sort_order'] ?? 0),
            productCount: (int)($data['product_count'] ?? 0),
        );
    }
}
