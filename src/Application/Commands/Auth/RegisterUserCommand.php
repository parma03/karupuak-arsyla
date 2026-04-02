<?php
// src/Application/Commands/Auth/RegisterUserCommand.php
namespace Application\Commands\Auth;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone = '',
    ) {}
}
