<?php
// src/Application/Queries/Order/GetOrderListQuery.php
namespace Application\Queries\Order;

class GetOrderListQuery
{
    public function __construct(
        public readonly ?int    $userId  = null,  // null = admin (semua)
        public readonly int     $page    = 1,
        public readonly int     $perPage = 10,
        public readonly ?string $status  = null,
        public readonly ?string $search  = null,
        public readonly ?string $from    = null,
        public readonly ?string $to      = null,
    ) {}
}
