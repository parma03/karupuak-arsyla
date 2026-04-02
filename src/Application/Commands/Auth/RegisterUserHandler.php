<?php
// src/Application/Commands/Auth/RegisterUserHandler.php
namespace Application\Commands\Auth;

use Application\Commands\CommandHandlerInterface;
use Infrastructure\Persistence\MySQLUserRepository;
use Domain\User\Entities\User;

class RegisterUserHandler implements CommandHandlerInterface
{
    private MySQLUserRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLUserRepository();
    }

    public function handle(object $command): int
    {
        /** @var RegisterUserCommand $command */
        if ($this->repo->emailExists($command->email)) {
            throw new \RuntimeException('Email sudah terdaftar.');
        }

        $user = new User(
            id: null,
            name: htmlspecialchars(trim($command->name), ENT_QUOTES),
            email: strtolower(trim($command->email)),
            password: password_hash($command->password, PASSWORD_BCRYPT, ['cost' => 12]),
            phone: $command->phone ?: null,
            address: null,
            cityId: null,
            cityName: null,
            province: null,
            postalCode: null,
            role: 'customer',
            isActive: true,
        );

        return $this->repo->save($user);
    }
}
