<?php
// src/Application/Commands/Order/CreateOrderCommand.php
namespace Application\Commands\Order;

class CreateOrderCommand
{
    public function __construct(
        public readonly int    $userId,
        public readonly string $name,
        public readonly string $phone,
        public readonly string $address,
        public readonly int    $cityId,
        public readonly string $cityName,
        public readonly string $province,
        public readonly string $postalCode,
        public readonly string $courier,
        public readonly string $courierService,
        public readonly float  $shippingCost,
        public readonly ?string $notes = null,
    ) {}
}
