<?php
// src/Domain/Order/Entities/Order.php
namespace Domain\Order\Entities;

class Order
{
    public array $items = [];

    public function __construct(
        public readonly ?int    $id,
        public readonly string  $orderCode,
        public readonly int     $userId,
        public readonly string  $name,
        public readonly string  $phone,
        public readonly string  $address,
        public readonly int     $cityId,
        public readonly string  $cityName,
        public readonly string  $province,
        public readonly ?string $postalCode,
        public readonly string  $courier,
        public readonly string  $courierService,
        public readonly float   $shippingCost,
        public readonly float   $subtotal,
        public readonly float   $total,
        public readonly ?string $notes,
        public readonly string  $status,
        public readonly string  $paymentStatus,
        public readonly string  $createdAt,
    ) {}

    public function isPaid(): bool
    {
        return $this->paymentStatus === 'paid';
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'delivered' && $this->isPaid();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'delivered'  => 'Diterima',
            'cancelled'  => 'Dibatalkan',
            'refunded'   => 'Dikembalikan',
            default      => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'paid'       => 'info',
            'processing' => 'primary',
            'shipped'    => 'info',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            'refunded'   => 'secondary',
            default      => 'secondary',
        };
    }

    public static function generateCode(): string
    {
        return 'KA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            orderCode: $data['order_code'],
            userId: (int)$data['user_id'],
            name: $data['name'],
            phone: $data['phone'],
            address: $data['address'],
            cityId: (int)$data['city_id'],
            cityName: $data['city_name'],
            province: $data['province'],
            postalCode: $data['postal_code'] ?? null,
            courier: $data['courier'],
            courierService: $data['courier_service'],
            shippingCost: (float)$data['shipping_cost'],
            subtotal: (float)$data['subtotal'],
            total: (float)$data['total'],
            notes: $data['notes'] ?? null,
            status: $data['status'] ?? 'pending',
            paymentStatus: $data['payment_status'] ?? 'unpaid',
            createdAt: $data['created_at'] ?? date('Y-m-d H:i:s'),
        );
    }
}
