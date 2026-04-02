<?php
// app/Controllers/Admin/AdminCategoryController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware};
use Domain\Category\Entities\Category;
use Infrastructure\Persistence\MySQLCategoryRepository;

class AdminCategoryController
{
    private Request                 $req;
    private Response                $res;
    private MySQLCategoryRepository $repo;

    public function __construct()
    {
        $this->req  = new Request();
        $this->res  = new Response();
        $this->repo = new MySQLCategoryRepository();
        Middleware::admin();
    }

    public function index(): void
    {
        $categories = $this->repo->findAll(false);
        $this->res->render('admin.categories.index', [
            'title'      => 'Kelola Kategori',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void
    {
        $name = trim($this->req->post('name'));
        $slug = $this->makeSlug($name);

        if ($this->repo->slugExists($slug)) {
            $slug .= '-' . time();
        }

        $cat = new Category(
            id: null,
            name: $name,
            slug: $slug,
            description: $this->req->post('description'),
            image: null,
            isActive: true,
            sortOrder: (int)$this->req->post('sort_order', 0),
        );
        $this->repo->save($cat);
        $this->res->setFlash('success', 'Kategori berhasil ditambahkan.');
        $this->res->redirect('admin/categories');
    }

    public function update(string $id): void
    {
        $existing = $this->repo->findById((int)$id);
        if (!$existing) $this->res->render404();

        $updated = new Category(
            id: (int)$id,
            name: $this->req->post('name'),
            slug: $existing->slug,
            description: $this->req->post('description'),
            image: $existing->image,
            isActive: (bool)$this->req->post('is_active', 1),
            sortOrder: (int)$this->req->post('sort_order', 0),
        );
        $this->repo->update($updated);
        $this->res->setFlash('success', 'Kategori diupdate.');
        $this->res->redirect('admin/categories');
    }

    public function delete(string $id): void
    {
        $this->repo->delete((int)$id);
        $this->res->setFlash('success', 'Kategori dihapus.');
        $this->res->redirect('admin/categories');
    }

    private function makeSlug(string $name): string
    {
        $s = strtolower(trim($name));
        $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
        return trim(preg_replace('/[\s-]+/', '-', $s), '-');
    }
}
