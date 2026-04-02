<?php
// src/Application/Queries/Cart/GetCartQuery.php
namespace Application\Queries\Cart;

class GetCartQuery
{
    public function __construct(public readonly int $userId) {}
}
