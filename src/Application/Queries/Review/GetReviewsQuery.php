<?php
// src/Application/Queries/Review/GetReviewsQuery.php
namespace Application\Queries\Review;

class GetReviewsQuery
{
    public function __construct(
        public readonly ?int $productId = null,
        public readonly int  $page      = 1,
        public readonly int  $perPage   = 10,
    ) {}
}
