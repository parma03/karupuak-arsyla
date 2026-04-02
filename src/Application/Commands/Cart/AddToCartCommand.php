<?php
// src/Application/Commands/Cart/AddToCartCommand.php
namespace Application\Commands\Cart;

class AddToCartCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly int $productId,
        public readonly int $qty = 1,
    ) {}
}
