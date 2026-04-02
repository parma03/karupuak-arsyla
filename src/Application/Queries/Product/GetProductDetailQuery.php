<?php
// src/Application/Queries/Product/GetProductDetailQuery.php
namespace Application\Queries\Product;

class GetProductDetailQuery
{
    public function __construct(
        public readonly int $id,
    ) {}
}
