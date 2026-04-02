<?php
// src/Application/Queries/Cart/GetCartHandler.php
namespace Application\Queries\Cart;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLCartRepository;
use Domain\Cart\Entities\Cart;

class GetCartHandler implements QueryHandlerInterface
{
    private MySQLCartRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLCartRepository();
    }

    public function handle(object $query): Cart
    {
        /** @var GetCartQuery $query */
        $cart  = $this->repo->getOrCreate($query->userId);
        $items = $this->repo->getItems($cart->id);
        $cart->items = $items;
        return $cart;
    }
}
