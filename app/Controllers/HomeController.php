<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

use Core\{Request, Response, Session};
use Application\Queries\Product\{GetProductListQuery, GetProductListHandler, GetProductDetailQuery, GetProductDetailHandler};
use Application\Queries\Review\{GetReviewsQuery, GetReviewsHandler};
use Infrastructure\Persistence\MySQLCategoryRepository;
use Infrastructure\Persistence\MySQLCartRepository;

class HomeController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function index(): void
    {
        $categoryRepo = new MySQLCategoryRepository();
        $categories   = $categoryRepo->findAll(true);

        $query    = new GetProductListQuery(
            page: (int)($this->req->get('page', 1)),
            perPage: ITEMS_PER_PAGE,
            categoryId: $this->req->get('category') ? (int)$this->req->get('category') : null,
            search: $this->req->get('q'),
            sortBy: $this->req->get('sort', 'created_at'),
            sortDir: $this->req->get('dir', 'DESC'),
            featuredOnly: false,
        );
        $result   = (new GetProductListHandler())->handle($query);

        // Featured produk untuk hero section
        $featuredQuery  = new GetProductListQuery(page: 1, perPage: 6, featuredOnly: true);
        $featuredResult = (new GetProductListHandler())->handle($featuredQuery);

        $cartCount = Session::isLoggedIn()
            ? (new MySQLCartRepository())->itemCount(Session::userId())
            : 0;

        $this->res->render('home.index', [
            'title'       => 'Karupuak Jangek Arsyla — Kerupuk Premium Minang',
            'categories'  => $categories,
            'products'    => $result['data'],
            'pagination'  => $result,
            'featured'    => $featuredResult['data'],
            'cartCount'   => $cartCount,
            'activeCategory' => $this->req->get('category'),
            'search'      => $this->req->get('q', ''),
        ]);
    }

    public function productDetail(string $id): void
    {
        try {
            $data = (new GetProductDetailHandler())->handle(new GetProductDetailQuery((int)$id));
        } catch (\RuntimeException $e) {
            $this->res->render404();
        }

        $cartCount = Session::isLoggedIn()
            ? (new MySQLCartRepository())->itemCount(Session::userId())
            : 0;

        $this->res->render('product.detail', [
            'title'     => $data['product']->name . ' — Arsyla',
            'product'   => $data['product'],
            'category'  => $data['category'],
            'reviews'   => $data['reviews'],
            'related'   => $data['related'],
            'cartCount' => $cartCount,
        ]);
    }

    public function productReviews(string $id): void
    {
        $page    = (int)($this->req->get('page', 1));
        $result  = (new GetReviewsHandler())->handle(new GetReviewsQuery((int)$id, $page, 10));
        $this->res->json($result);
    }
}
