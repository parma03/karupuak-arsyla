<?php
// src/Application/Commands/Payment/CreatePaymentCommand.php
namespace Application\Commands\Payment;

class CreatePaymentCommand
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $userId,
    ) {}
}
