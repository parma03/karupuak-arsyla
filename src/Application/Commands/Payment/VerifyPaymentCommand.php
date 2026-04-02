<?php
// src/Application/Commands/Payment/VerifyPaymentCommand.php
namespace Application\Commands\Payment;

class VerifyPaymentCommand
{
    public function __construct(
        public readonly array $payload, // raw POST dari Midtrans notification
    ) {}
}
