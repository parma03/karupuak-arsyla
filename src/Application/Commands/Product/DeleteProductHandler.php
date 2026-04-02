<?php
// src/Application/Commands/Product/DeleteProductHandler.php
namespace Application\Commands\Product;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLProductRepository;

class DeleteProductHandler implements CommandHandlerInterface
{
    private MySQLProductRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLProductRepository();
    }

    public function handle(object $command): bool
    {
        /** @var DeleteProductCommand $command */
        $product = $this->repo->findById($command->id);
        if (!$product) {
            throw new \RuntimeException('Produk tidak ditemukan.');
        }

        // Hapus file gambar jika ada
        if ($product->image) {
            $filePath = UPLOAD_PATH . $product->image;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        return $this->repo->delete($command->id);
    }
}
