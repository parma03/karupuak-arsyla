<?php
// src/Application/Commands/Cart/RemoveFromCartHandler.php
namespace Application\Commands\Cart;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLCartRepository;

class RemoveFromCartHandler implements CommandHandlerInterface
{
    private MySQLCartRepository $cartRepo;

    public function __construct()
    {
        $this->cartRepo = new MySQLCartRepository();
    }

    public function handle(object $command): bool
    {
        /** @var RemoveFromCartCommand $command */
        $cart = $this->cartRepo->findByUserId($command->userId);
        if (!$cart) {
            throw new \RuntimeException('Keranjang tidak ditemukan.');
        }
        return $this->cartRepo->removeItem($cart->id, $command->productId);
    }
}
