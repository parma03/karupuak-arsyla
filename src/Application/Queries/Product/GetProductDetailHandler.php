<?php
// src/Application/Queries/Product/GetProductDetailHandler.php
namespace Application\Queries\Product;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLProductRepository;
use Infrastructure\Persistence\MySQLReviewRepository;
use Infrastructure\Persistence\MySQLCategoryRepository;

class GetProductDetailHandler implements QueryHandlerInterface
{
    private MySQLProductRepository  $productRepo;
    private MySQLReviewRepository   $reviewRepo;
    private MySQLCategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->productRepo  = new MySQLProductRepository();
        $this->reviewRepo   = new MySQLReviewRepository();
        $this->categoryRepo = new MySQLCategoryRepository();
    }

    public function handle(object $query): array
    {
        /** @var GetProductDetailQuery $query */
        $product = $this->productRepo->findById($query->id);
        if (!$product) {
            throw new \RuntimeException('Produk tidak ditemukan.');
        }

        $category = $this->categoryRepo->findById($product->categoryId);
        $reviews  = $this->reviewRepo->findByProductId($product->id, 1, 5);
        $related  = $this->productRepo->findAll(
            ['category_id' => $product->categoryId, 'is_active' => 1, 'exclude_id' => $product->id],
            1,
            4
        );

        return [
            'product'  => $product,
            'category' => $category,
            'reviews'  => $reviews,
            'related'  => $related,
        ];
    }
}
