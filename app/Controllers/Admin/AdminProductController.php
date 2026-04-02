<?php
// app/Controllers/Admin/AdminProductController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Session, Middleware, Validator};
use Application\Commands\Product\{CreateProductCommand, CreateProductHandler, UpdateProductCommand, UpdateProductHandler, DeleteProductCommand, DeleteProductHandler};
use Application\Queries\Product\{GetProductListQuery, GetProductListHandler};
use Infrastructure\Persistence\{MySQLProductRepository, MySQLCategoryRepository};

class AdminProductController
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
        $result = (new GetProductListHandler())->handle(new GetProductListQuery(
            page: (int)$this->req->get('page', 1),
            perPage: 15,
            search: $this->req->get('q'),
            categoryId: $this->req->get('cat') ? (int)$this->req->get('cat') : null,
        ));
        $categories = (new MySQLCategoryRepository())->findAll(false);

        $this->res->render('admin.products.index', [
            'title'      => 'Kelola Produk',
            'products'   => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
        ], 'admin');
    }

    public function create(): void
    {
        $this->res->render('admin.products.form', [
            'title'      => 'Tambah Produk',
            'categories' => (new MySQLCategoryRepository())->findAll(false),
            'product'    => null,
        ], 'admin');
    }

    public function store(): void
    {
        $imagePath = $this->handleUpload();

        try {
            (new CreateProductHandler())->handle(new CreateProductCommand(
                categoryId: (int)$this->req->post('category_id'),
                name: $this->req->post('name'),
                description: $this->req->post('description'),
                price: (float)str_replace(['.', ','], ['', '.'], $this->req->post('price')),
                priceSale: $this->req->post('price_sale') ? (float)str_replace(['.', ','], ['', '.'], $this->req->post('price_sale')) : null,
                stock: (int)$this->req->post('stock'),
                weight: (int)$this->req->post('weight', 100),
                isActive: (bool)$this->req->post('is_active', 1),
                isFeatured: (bool)$this->req->post('is_featured', 0),
                imagePath: $imagePath,
            ));
            $this->res->setFlash('success', 'Produk berhasil ditambahkan.');
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
        }
        $this->res->redirect('admin/products');
    }

    public function edit(string $id): void
    {
        $product = (new MySQLProductRepository())->findById((int)$id);
        if (!$product) $this->res->render404();

        $this->res->render('admin.products.form', [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => (new MySQLCategoryRepository())->findAll(false),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $existing  = (new MySQLProductRepository())->findById((int)$id);
        if (!$existing) $this->res->render404();

        $imagePath = $this->handleUpload() ?? $existing->image;

        try {
            (new UpdateProductHandler())->handle(new UpdateProductCommand(
                id: (int)$id,
                categoryId: (int)$this->req->post('category_id'),
                name: $this->req->post('name'),
                description: $this->req->post('description'),
                price: (float)str_replace(['.', ','], ['', '.'], $this->req->post('price')),
                priceSale: $this->req->post('price_sale') ? (float)str_replace(['.', ','], ['', '.'], $this->req->post('price_sale')) : null,
                stock: (int)$this->req->post('stock'),
                weight: (int)$this->req->post('weight', 100),
                isActive: (bool)$this->req->post('is_active', 0),
                isFeatured: (bool)$this->req->post('is_featured', 0),
                imagePath: $imagePath,
            ));
            $this->res->setFlash('success', 'Produk berhasil diupdate.');
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
        }
        $this->res->redirect('admin/products');
    }

    public function delete(string $id): void
    {
        try {
            (new DeleteProductHandler())->handle(new DeleteProductCommand((int)$id));
            $this->res->setFlash('success', 'Produk berhasil dihapus.');
        } catch (\RuntimeException $e) {
            $this->res->setFlash('danger', $e->getMessage());
        }
        $this->res->redirect('admin/products');
    }

    private function handleUpload(): ?string
    {
        $file = $this->req->file('image');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) return null;

        if (!in_array($file['type'], ALLOWED_TYPES)) {
            throw new \RuntimeException('Format gambar tidak didukung (gunakan JPG/PNG/WebP).');
        }
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new \RuntimeException('Ukuran gambar maksimal 5MB.');
        }

        if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_') . '.' . strtolower($ext);
        move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename);

        return $filename;
    }
}
