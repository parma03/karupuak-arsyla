<?php
// src/Domain/User/Entities/User.php
namespace Domain\User\Entities;

class User
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $name,
        public readonly string  $email,
        public readonly string  $password,
        public readonly ?string $phone,
        public readonly ?string $address,
        public readonly ?int    $cityId,
        public readonly ?string $cityName,
        public readonly ?string $province,
        public readonly ?string $postalCode,
        public readonly string  $role,
        public readonly bool    $isActive,
    ) {}

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, $this->password);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            cityId: isset($data['city_id']) ? (int)$data['city_id'] : null,
            cityName: $data['city_name'] ?? null,
            province: $data['province'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            role: $data['role'] ?? 'customer',
            isActive: (bool)($data['is_active'] ?? true),
        );
    }
}
