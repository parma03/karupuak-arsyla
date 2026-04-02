<?php
// src/Application/Commands/Review/CreateReviewCommand.php
namespace Application\Commands\Review;

class CreateReviewCommand
{
    public function __construct(
        public readonly int     $userId,
        public readonly int     $productId,
        public readonly int     $orderId,
        public readonly int     $rating,
        public readonly ?string $comment = null,
    ) {}
}
