<?php
// src/Application/Commands/Cart/AddToCartHandler.php
namespace Application\Commands\Cart;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLCartRepository;
use Infrastructure\Persistence\MySQLProductRepository;

class AddToCartHandler implements CommandHandlerInterface
{
    private MySQLCartRepository    $cartRepo;
    private MySQLProductRepository $productRepo;

    public function __construct()
    {
        $this->cartRepo    = new MySQLCartRepository();
        $this->productRepo = new MySQLProductRepository();
    }

    public function handle(object $command): bool
    {
        /** @var AddToCartCommand $command */
        $product = $this->productRepo->findById($command->productId);

        if (!$product || !$product->isActive) {
            throw new \RuntimeException('Produk tidak tersedia.');
        }

        if ($command->qty > $product->stock) {
            throw new \RuntimeException('Stok produk tidak mencukupi.');
        }

        $cart = $this->cartRepo->getOrCreate($command->userId);

        return $this->cartRepo->addItem(
            $cart->id,
            $command->productId,
            $command->qty,
            $product->priceSale ?? $product->price
        );
    }
}
