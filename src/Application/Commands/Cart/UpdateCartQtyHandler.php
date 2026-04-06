<?php
// src/Application/Commands/Cart/UpdateCartQtyHandler.php
namespace Application\Commands\Cart;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLCartRepository;
use Infrastructure\Persistence\MySQLProductRepository;

class UpdateCartQtyHandler implements CommandHandlerInterface
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
        /** @var UpdateCartQtyCommand $command */
        $cart = $this->cartRepo->findByUserId($command->userId);
        if (!$cart) {
            throw new \RuntimeException('Keranjang tidak ditemukan.');
        }

        if ($command->qty <= 0) {
            return $this->cartRepo->removeItem($cart->id, $command->productId);
        }

        $product = $this->productRepo->findById($command->productId);
        if (!$product || !$product->isActive) {
            throw new \RuntimeException('Produk tidak tersedia.');
        }

        if ($command->qty > $product->stock) {
            throw new \RuntimeException(
                "Stok '{$product->name}' hanya tersisa {$product->stock}."
            );
        }

        return $this->cartRepo->updateItemQty($cart->id, $command->productId, $command->qty);
    }
}
