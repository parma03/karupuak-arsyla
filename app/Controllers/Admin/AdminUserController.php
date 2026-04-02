<?php
// app/Controllers/Admin/AdminUserController.php
namespace App\Controllers\Admin;

use Core\{Request, Response, Middleware, Validator};
use Domain\User\Entities\User;
use Infrastructure\Persistence\MySQLUserRepository;

class AdminUserController
{
    private Request              $req;
    private Response             $res;
    private MySQLUserRepository  $repo;

    public function __construct()
    {
        $this->req  = new Request();
        $this->res  = new Response();
        $this->repo = new MySQLUserRepository();
        Middleware::admin();
    }

    public function index(): void
    {
        $users = $this->repo->findAll(
            ['search' => $this->req->get('q'), 'role' => $this->req->get('role')],
            (int)$this->req->get('page', 1),
            20
        );
        $total = $this->repo->countAll();
        $this->res->render('admin.users.index', [
            'title' => 'Kelola Pengguna',
            'users' => $users,
            'total' => $total,
        ], 'admin');
    }

    public function create(): void
    {
        $this->res->render('admin.users.form', ['title' => 'Tambah User', 'user' => null], 'admin');
    }

    public function store(): void
    {
        $data = [
            'name'  => $this->req->post('name'),
            'email' => $this->req->post('email'),
            'role'  => $this->req->post('role', 'customer'),
            'phone' => $this->req->post('phone'),
        ];

        $user = new User(
            id: null,
            name: $data['name'],
            email: $data['email'],
            password: password_hash($this->req->post('password', 'password123'), PASSWORD_BCRYPT),
            phone: $data['phone'],
            address: null,
            cityId: null,
            cityName: null,
            province: null,
            postalCode: null,
            role: $data['role'],
            isActive: true,
        );

        $this->repo->save($user);
        $this->res->setFlash('success', 'User berhasil ditambahkan.');
        $this->res->redirect('admin/users');
    }

    public function edit(string $id): void
    {
        $user = $this->repo->findById((int)$id);
        if (!$user) $this->res->render404();
        $this->res->render('admin.users.form', ['title' => 'Edit User', 'user' => $user], 'admin');
    }

    public function update(string $id): void
    {
        $existing = $this->repo->findById((int)$id);
        if (!$existing) $this->res->render404();

        $updated = new User(
            id: (int)$id,
            name: $this->req->post('name'),
            email: $existing->email,
            password: $existing->password,
            phone: $this->req->post('phone'),
            address: $existing->address,
            cityId: $existing->cityId,
            cityName: $existing->cityName,
            province: $existing->province,
            postalCode: $existing->postalCode,
            role: $existing->role,
            isActive: (bool)$this->req->post('is_active', 1),
        );

        $this->repo->update($updated);
        $this->res->setFlash('success', 'User berhasil diupdate.');
        $this->res->redirect('admin/users');
    }

    public function delete(string $id): void
    {
        $this->repo->delete((int)$id);
        $this->res->setFlash('success', 'User berhasil dihapus.');
        $this->res->redirect('admin/users');
    }
}
