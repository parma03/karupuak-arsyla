<?php
// src/Application/Commands/Product/DeleteProductCommand.php
namespace Application\Commands\Product;

class DeleteProductCommand
{
    public function __construct(public readonly int $id) {}
}
