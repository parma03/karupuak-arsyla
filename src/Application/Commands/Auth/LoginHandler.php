<?php
// src/Application/Commands/Auth/LoginHandler.php
namespace Application\Commands\Auth;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLUserRepository;
use Domain\User\Entities\User;

class LoginHandler implements CommandHandlerInterface
{
    private MySQLUserRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLUserRepository();
    }

    /** @return array User data array untuk disimpan ke session */
    public function handle(object $command): array
    {
        /** @var LoginCommand $command */
        $user = $this->repo->findByEmail(strtolower(trim($command->email)));

        if (!$user) {
            throw new \RuntimeException('Email atau password salah.');
        }

        if (!$user->isActive) {
            throw new \RuntimeException('Akun Anda dinonaktifkan. Hubungi admin.');
        }

        if (!$user->verifyPassword($command->password)) {
            throw new \RuntimeException('Email atau password salah.');
        }

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];
    }
}
