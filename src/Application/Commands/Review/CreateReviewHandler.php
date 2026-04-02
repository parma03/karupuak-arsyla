<?php
// src/Application/Commands/Review/CreateReviewHandler.php
namespace Application\Commands\Review;

use Application\Commands\CommandHandlerInterface;
use Domain\Review\Entities\Review;
use Infrastructure\Persistence\MySQLReviewRepository;
use Infrastructure\Persistence\MySQLOrderRepository;
use Infrastructure\Persistence\MySQLProductRepository;

class CreateReviewHandler implements CommandHandlerInterface
{
    private MySQLReviewRepository  $reviewRepo;
    private MySQLOrderRepository   $orderRepo;
    private MySQLProductRepository $productRepo;

    public function __construct()
    {
        $this->reviewRepo  = new MySQLReviewRepository();
        $this->orderRepo   = new MySQLOrderRepository();
        $this->productRepo = new MySQLProductRepository();
    }

    public function handle(object $command): int
    {
        /** @var CreateReviewCommand $command */

        // Validasi rating
        if ($command->rating < 1 || $command->rating > 5) {
            throw new \RuntimeException('Rating harus antara 1 dan 5.');
        }

        // Pastikan order milik user & sudah delivered
        $order = $this->orderRepo->findById($command->orderId);
        if (!$order || $order->userId !== $command->userId) {
            throw new \RuntimeException('Order tidak ditemukan.');
        }
        if ($order->status !== 'delivered') {
            throw new \RuntimeException('Ulasan hanya bisa diberikan setelah pesanan diterima.');
        }

        // Cek sudah review?
        if ($this->reviewRepo->hasReviewed($command->userId, $command->productId, $command->orderId)) {
            throw new \RuntimeException('Anda sudah memberikan ulasan untuk produk ini.');
        }

        $review = new Review(
            id: null,
            userId: $command->userId,
            productId: $command->productId,
            orderId: $command->orderId,
            rating: $command->rating,
            comment: $command->comment ? htmlspecialchars(trim($command->comment), ENT_QUOTES) : null,
            isVisible: true,
            createdAt: date('Y-m-d H:i:s'),
        );

        $reviewId = $this->reviewRepo->save($review);

        // Update rating rata-rata produk
        $ratingData = $this->reviewRepo->getAverageRating($command->productId);
        $this->productRepo->updateRating(
            $command->productId,
            $ratingData['avg'],
            $ratingData['count']
        );

        return $reviewId;
    }
}
