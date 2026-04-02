<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use Core\{Request, Response, Session, Middleware, Validator};
use Application\Commands\Auth\{RegisterUserCommand, RegisterUserHandler, LoginCommand, LoginHandler};

class AuthController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    public function loginForm(): void
    {
        Middleware::guest();
        $this->res->render('auth.login', [
            'title' => 'Login — Karupuak Arsyla',
        ], 'auth');
    }

    public function login(): void
    {
        Middleware::guest();

        $validator = new Validator();
        $data      = ['email' => $this->req->post('email'), 'password' => $this->req->post('password')];

        if (!$validator->validate($data, ['email' => 'required|email', 'password' => 'required|min:6'])) {
            $this->res->render('auth.login', [
                'title'  => 'Login — Karupuak Arsyla',
                'errors' => $validator->errors(),
                'old'    => $data,
            ], 'auth');
            return;
        }

        try {
            $command = new LoginCommand($data['email'], $data['password']);
            $user    = (new LoginHandler())->handle($command);
            Session::login($user);

            // Redirect sesuai role
            $intended = Session::get('intended_url');
            Session::delete('intended_url');

            if ($user['role'] === 'customer') {
                $this->res->redirect($intended ? ltrim(str_replace(APP_URL, '', $intended), '/') : '');
            } else {
                $this->res->redirect('admin');
            }
        } catch (\RuntimeException $e) {
            $this->res->render('auth.login', [
                'title'  => 'Login — Karupuak Arsyla',
                'error'  => $e->getMessage(),
                'old'    => $data,
            ], 'auth');
        }
    }

    public function registerForm(): void
    {
        Middleware::guest();
        $this->res->render('auth.register', [
            'title' => 'Daftar Akun — Karupuak Arsyla',
        ], 'auth');
    }

    public function register(): void
    {
        Middleware::guest();

        $validator = new Validator();
        $data = [
            'name'     => $this->req->post('name'),
            'email'    => $this->req->post('email'),
            'password' => $this->req->post('password'),
            'phone'    => $this->req->post('phone', ''),
        ];

        $rules = [
            'name'     => 'required|min:2|max:100',
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ];

        if (!$validator->validate($data, $rules)) {
            $this->res->render('auth.register', [
                'title'  => 'Daftar Akun — Karupuak Arsyla',
                'errors' => $validator->errors(),
                'old'    => $data,
            ], 'auth');
            return;
        }

        // Konfirmasi password
        if ($data['password'] !== $this->req->post('password_confirm')) {
            $this->res->render('auth.register', [
                'title'  => 'Daftar Akun — Karupuak Arsyla',
                'errors' => ['password' => ['Konfirmasi password tidak cocok.']],
                'old'    => $data,
            ], 'auth');
            return;
        }

        try {
            $command = new RegisterUserCommand($data['name'], $data['email'], $data['password'], $data['phone']);
            (new RegisterUserHandler())->handle($command);

            // Auto-login setelah register
            $loginCmd = new LoginCommand($data['email'], $data['password']);
            $user     = (new LoginHandler())->handle($loginCmd);
            Session::login($user);

            $this->res->setFlash('success', 'Selamat datang, ' . $user['name'] . '! Akun berhasil dibuat.');
            $this->res->redirect('');
        } catch (\RuntimeException $e) {
            $this->res->render('auth.register', [
                'title'  => 'Daftar Akun — Karupuak Arsyla',
                'error'  => $e->getMessage(),
                'old'    => $data,
            ], 'auth');
        }
    }

    public function logout(): void
    {
        Session::destroy();
        $this->res->redirect('login');
    }
}
