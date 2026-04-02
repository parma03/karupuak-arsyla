<?php
// src/Domain/Category/Repositories/CategoryRepositoryInterface.php
namespace Domain\Category\Repositories;

use Domain\Category\Entities\Category;

interface CategoryRepositoryInterface
{
    public function findAll(bool $activeOnly = true): array;
    public function findById(int $id): ?Category;
    public function findBySlug(string $slug): ?Category;
    public function save(Category $category): int;
    public function update(Category $category): bool;
    public function delete(int $id): bool;
    public function slugExists(string $slug, ?int $excludeId = null): bool;
}
