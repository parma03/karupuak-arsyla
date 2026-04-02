<?php
// src/Application/Queries/Review/GetReviewsHandler.php
namespace Application\Queries\Review;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLReviewRepository;

class GetReviewsHandler implements QueryHandlerInterface
{
    private MySQLReviewRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLReviewRepository();
    }

    public function handle(object $query): array
    {
        /** @var GetReviewsQuery $query */
        $filters = [];
        if ($query->productId) $filters['product_id'] = $query->productId;

        $reviews = $this->repo->findAll($filters, $query->page, $query->perPage);
        $total   = $this->repo->countByProduct($query->productId ?? 0);

        return [
            'data'        => $reviews,
            'total'       => $total,
            'page'        => $query->page,
            'total_pages' => (int)ceil($total / $query->perPage),
        ];
    }
}
