<?php
// src/Application/Commands/Product/UpdateProductCommand.php
namespace Application\Commands\Product;

class UpdateProductCommand
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $categoryId,
        public readonly string  $name,
        public readonly string  $description,
        public readonly float   $price,
        public readonly ?float  $priceSale,
        public readonly int     $stock,
        public readonly int     $weight,
        public readonly bool    $isActive,
        public readonly bool    $isFeatured,
        public readonly ?string $imagePath = null,
    ) {}
}
