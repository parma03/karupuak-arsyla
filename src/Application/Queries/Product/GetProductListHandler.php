<?php
// src/Application/Queries/Product/GetProductListHandler.php
namespace Application\Queries\Product;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLProductRepository;

class GetProductListHandler implements QueryHandlerInterface
{
    private MySQLProductRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLProductRepository();
    }

    public function handle(object $query): array
    {
        /** @var GetProductListQuery $query */
        $filters = ['is_active' => 1];

        if ($query->categoryId)   $filters['category_id']  = $query->categoryId;
        if ($query->search)       $filters['search']        = $query->search;
        if ($query->featuredOnly) $filters['is_featured']   = 1;

        $filters['sort_by']  = $query->sortBy;
        $filters['sort_dir'] = $query->sortDir;

        $total    = $this->repo->countAll($filters);
        $products = $this->repo->findAll($filters, $query->page, $query->perPage);

        return [
            'data'        => $products,
            'total'       => $total,
            'page'        => $query->page,
            'per_page'    => $query->perPage,
            'total_pages' => (int)ceil($total / $query->perPage),
        ];
    }
}
