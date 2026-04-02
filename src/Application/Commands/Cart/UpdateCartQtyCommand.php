<?php
// src/Application/Commands/Cart/UpdateCartQtyCommand.php
namespace Application\Commands\Cart;

class UpdateCartQtyCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly int $productId,
        public readonly int $qty,
    ) {}
}
