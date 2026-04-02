<?php
// src/Application/Commands/Product/UpdateProductHandler.php
namespace Application\Commands\Product;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLProductRepository;

class UpdateProductHandler implements CommandHandlerInterface
{
    private MySQLProductRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLProductRepository();
    }

    public function handle(object $command): bool
    {
        /** @var UpdateProductCommand $command */
        $existing = $this->repo->findById($command->id);
        if (!$existing) {
            throw new \RuntimeException('Produk tidak ditemukan.');
        }

        // Pertahankan image lama jika tidak ada upload baru
        $imagePath = $command->imagePath ?? $existing->image;

        $updated = new \Domain\Product\Entities\Product(
            id: $command->id,
            categoryId: $command->categoryId,
            name: $command->name,
            slug: $existing->slug,
            description: $command->description,
            price: $command->price,
            priceSale: $command->priceSale,
            stock: $command->stock,
            weight: $command->weight,
            image: $imagePath,
            isActive: $command->isActive,
            isFeatured: $command->isFeatured,
            soldCount: $existing->soldCount,
            ratingAvg: $existing->ratingAvg,
            ratingCount: $existing->ratingCount,
        );

        return $this->repo->update($updated);
    }
}
