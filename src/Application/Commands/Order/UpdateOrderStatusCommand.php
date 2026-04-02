<?php
// src/Application/Commands/Order/UpdateOrderStatusCommand.php
namespace Application\Commands\Order;

class UpdateOrderStatusCommand
{
    public function __construct(
        public readonly int    $orderId,
        public readonly string $status,
        public readonly ?string $trackingNumber = null,
        public readonly ?string $notes          = null,
    ) {}
}
