<?php
// src/Domain/Review/Repositories/ReviewRepositoryInterface.php
namespace Domain\Review\Repositories;

use Domain\Review\Entities\Review;

interface ReviewRepositoryInterface
{
    public function findByProductId(int $productId, int $page = 1, int $perPage = 10): array;
    public function findByOrderId(int $orderId): array;
    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array;
    public function countByProduct(int $productId): int;
    public function save(Review $review): int;
    public function delete(int $id): bool;
    public function toggleVisibility(int $id, bool $visible): bool;
    public function hasReviewed(int $userId, int $productId, int $orderId): bool;
    public function getAverageRating(int $productId): array; // ['avg' => float, 'count' => int]
}
