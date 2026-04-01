<?php
// src/Domain/Product/Entities/Product.php
namespace Domain\Product\Entities;

class Product
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $categoryId,
        public readonly string  $name,
        public readonly string  $slug,
        public readonly ?string $description,
        public readonly float   $price,
        public readonly ?float  $priceSale,
        public readonly int     $stock,
        public readonly int     $weight,
        public readonly ?string $image,
        public readonly bool    $isActive,
        public readonly bool    $isFeatured,
        public readonly int     $soldCount,
        public readonly float   $ratingAvg,
        public readonly int     $ratingCount,
    ) {}

    public function effectivePrice(): float
    {
        return $this->priceSale ?? $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->priceSale !== null && $this->priceSale < $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function imageUrl(): string
    {
        if ($this->image) {
            return UPLOAD_URL . $this->image;
        }
        return APP_URL . '/public/img/no-image.png';
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            categoryId: $data['category_id'],
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'] ?? null,
            price: (float)$data['price'],
            priceSale: isset($data['price_sale']) ? (float)$data['price_sale'] : null,
            stock: (int)$data['stock'],
            weight: (int)($data['weight'] ?? 100),
            image: $data['image'] ?? null,
            isActive: (bool)($data['is_active'] ?? true),
            isFeatured: (bool)($data['is_featured'] ?? false),
            soldCount: (int)($data['sold_count'] ?? 0),
            ratingAvg: (float)($data['rating_avg'] ?? 0),
            ratingCount: (int)($data['rating_count'] ?? 0),
        );
    }
}
