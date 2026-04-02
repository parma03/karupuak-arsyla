<?php
// src/Application/Queries/Order/GetOrderDetailQuery.php
namespace Application\Queries\Order;

class GetOrderDetailQuery
{
    public function __construct(
        public readonly int  $orderId,
        public readonly ?int $userId = null, // null = admin, int = customer check
    ) {}
}
