<?php
// src/Application/Queries/Order/GetOrderListHandler.php
namespace Application\Queries\Order;

use Application\Queries\QueryHandlerInterface;
use Infrastructure\Persistence\MySQLOrderRepository;

class GetOrderListHandler implements QueryHandlerInterface
{
    private MySQLOrderRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLOrderRepository();
    }

    public function handle(object $query): array
    {
        /** @var GetOrderListQuery $query */
        $filters = [];
        if ($query->userId)  $filters['user_id'] = $query->userId;
        if ($query->status)  $filters['status']  = $query->status;
        if ($query->search)  $filters['search']  = $query->search;
        if ($query->from)    $filters['from']    = $query->from;
        if ($query->to)      $filters['to']      = $query->to;

        $total  = $this->repo->countAll($filters);
        $orders = $this->repo->findAll($filters, $query->page, $query->perPage);

        return [
            'data'        => $orders,
            'total'       => $total,
            'page'        => $query->page,
            'per_page'    => $query->perPage,
            'total_pages' => (int)ceil($total / $query->perPage),
        ];
    }
}
