<?php
// src/Application/Queries/Product/GetProductListQuery.php
namespace Application\Queries\Product;

class GetProductListQuery
{
    public function __construct(
        public readonly int     $page        = 1,
        public readonly int     $perPage     = 12,
        public readonly ?int    $categoryId  = null,
        public readonly ?string $search      = null,
        public readonly string  $sortBy      = 'created_at',
        public readonly string  $sortDir     = 'DESC',
        public readonly bool    $featuredOnly = false,
    ) {}
}
