<?php
// src/Domain/User/Repositories/UserRepositoryInterface.php
namespace Domain\User\Repositories;

use Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findAll(array $filters = [], int $page = 1, int $perPage = 20): array;
    public function countAll(array $filters = []): int;
    public function save(User $user): int;
    public function update(User $user): bool;
    public function delete(int $id): bool;
    public function emailExists(string $email, ?int $excludeId = null): bool;
}
