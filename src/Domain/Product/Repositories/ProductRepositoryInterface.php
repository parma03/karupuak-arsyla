<?php
// src/Domain/Product/Repositories/ProductRepositoryInterface.php
namespace Domain\Product\Repositories;

use Domain\Product\Entities\Product;

interface ProductRepositoryInterface
{
    public function findAll(array $filters = [], int $page = 1, int $perPage = 12): array;
    public function findById(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function findFeatured(int $limit = 8): array;
    public function countAll(array $filters = []): int;
    public function save(Product $product): int;
    public function update(Product $product): bool;
    public function delete(int $id): bool;
    public function updateRating(int $id, float $avg, int $count): bool;
    public function decrementStock(int $id, int $qty): bool;
    public function incrementSold(int $id, int $qty): bool;
}
