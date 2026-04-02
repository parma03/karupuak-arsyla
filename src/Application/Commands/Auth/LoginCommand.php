<?php
// src/Application/Commands/Auth/LoginCommand.php
namespace Application\Commands\Auth;

class LoginCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
