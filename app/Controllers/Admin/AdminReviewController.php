<?php
// app/Controllers/Admin/AdminReviewController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};
use Infrastructure\Persistence\MySQLReviewRepository;

class AdminReviewController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
        Middleware::admin();
    }

    public function index(): void
    {
        $repo    = new MySQLReviewRepository();
        $page    = max(1, (int)$this->req->get('page', 1));
        $reviews = $repo->findAll([], $page, 20);
        $total   = $repo->countByProduct(0);

        $this->res->render('admin.reviews.index', [
            'title'      => 'Kelola Ulasan',
            'reviews'    => $reviews,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => ceil($total / 20),
        ], 'admin');
    }

    public function delete(string $id): void
    {
        (new MySQLReviewRepository())->delete((int)$id);
        $this->res->setFlash('success', 'Ulasan dihapus.');
        $this->res->redirect('admin/reviews');
    }
}
