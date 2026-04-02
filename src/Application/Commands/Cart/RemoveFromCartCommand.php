<?php
// src/Application/Commands/Cart/RemoveFromCartCommand.php
namespace Application\Commands\Cart;

class RemoveFromCartCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly int $productId,
    ) {}
}
