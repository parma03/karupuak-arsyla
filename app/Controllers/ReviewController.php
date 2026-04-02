<?php
// app/Controllers/ReviewController.php
namespace App\Controllers;

use Core\{Request, Response, Session, Middleware, Validator};
use Application\Commands\Review\{CreateReviewCommand, CreateReviewHandler};
use Application\Queries\Order\{GetOrderDetailQuery, GetOrderDetailHandler};
use Infrastructure\Persistence\MySQLCartRepository;

class ReviewController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function form(string $orderId): void
    {
        Middleware::auth();

        try {
            $data = (new GetOrderDetailHandler())->handle(
                new GetOrderDetailQuery((int)$orderId, Session::userId())
            );
        } catch (\RuntimeException $e) {
            $this->res->render404();
        }

        if ($data['order']->status !== 'delivered') {
            $this->res->setFlash('warning', 'Ulasan hanya bisa diberikan setelah pesanan diterima.');
            $this->res->redirect('orders/' . $orderId);
        }

        $cartCount = (new MySQLCartRepository())->itemCount(Session::userId());

        $this->res->render('review.form', [
            'title'    => 'Beri Ulasan',
            'order'    => $data['order'],
            'items'    => $data['items'],
            'cartCount' => $cartCount,
        ]);
    }

    public function submit(string $orderId): void
    {
        Middleware::auth();

        $productIds = $this->req->post('product_id');
        $ratings    = $this->req->post('rating');
        $comments   = $this->req->post('comment');

        if (!is_array($productIds)) {
            $this->res->setFlash('danger', 'Data tidak valid.');
            $this->res->redirect('review/' . $orderId);
        }

        $errors = 0;
        foreach ($productIds as $i => $productId) {
            $rating  = (int)($ratings[$i] ?? 0);
            $comment = $comments[$i] ?? null;

            if ($rating < 1 || $rating > 5) continue;

            try {
                (new CreateReviewHandler())->handle(new CreateReviewCommand(
                    userId: Session::userId(),
                    productId: (int)$productId,
                    orderId: (int)$orderId,
                    rating: $rating,
                    comment: $comment,
                ));
            } catch (\RuntimeException $e) {
                $errors++;
            }
        }

        if ($errors === 0) {
            $this->res->setFlash('success', 'Ulasan berhasil dikirim. Terima kasih!');
        } else {
            $this->res->setFlash('warning', 'Beberapa ulasan gagal disimpan.');
        }

        $this->res->redirect('orders/' . $orderId);
    }
}
