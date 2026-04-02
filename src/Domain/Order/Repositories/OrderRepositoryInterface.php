<?php
// src/Domain/Order/Repositories/OrderRepositoryInterface.php
namespace Domain\Order\Repositories;

use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function findByCode(string $code): ?Order;
    public function findByUserId(int $userId, int $page = 1, int $perPage = 10): array;
    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array;
    public function countAll(array $filters = []): int;
    public function save(Order $order): int;
    public function updateStatus(int $id, string $status): bool;
    public function updatePaymentStatus(int $id, string $status): bool;
    public function getItems(int $orderId): array; // returns OrderItem[]
    public function saveItems(int $orderId, array $items): bool;
    public function getSalesSummary(string $from, string $to): array;
    public function getSalesTrend(string $period = 'monthly'): array;
}
