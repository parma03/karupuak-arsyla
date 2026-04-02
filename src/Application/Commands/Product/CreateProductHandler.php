<?php
// src/Application/Commands/Product/CreateProductHandler.php
namespace Application\Commands\Product;

use Application\Commands\CommandHandlerInterface;
use Domain\Product\Entities\Product;
use Infrastructure\Persistence\MySQLProductRepository;

class CreateProductHandler implements CommandHandlerInterface
{
    private MySQLProductRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLProductRepository();
    }

    public function handle(object $command): int
    {
        /** @var CreateProductCommand $command */
        $slug = $this->makeSlug($command->name);

        // Pastikan slug unik
        $base = $slug;
        $i    = 1;
        while ($this->repo->findBySlug($slug)) {
            $slug = $base . '-' . $i++;
        }

        $product = new Product(
            id: null,
            categoryId: $command->categoryId,
            name: $command->name,
            slug: $slug,
            description: $command->description,
            price: $command->price,
            priceSale: $command->priceSale,
            stock: $command->stock,
            weight: $command->weight,
            image: $command->imagePath,
            isActive: $command->isActive,
            isFeatured: $command->isFeatured,
            soldCount: 0,
            ratingAvg: 0,
            ratingCount: 0,
        );

        return $this->repo->save($product);
    }

    private function makeSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
