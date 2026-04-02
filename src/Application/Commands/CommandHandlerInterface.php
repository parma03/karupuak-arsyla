<?php
// src/Application/Commands/CommandHandlerInterface.php
namespace Application\Commands;

interface CommandHandlerInterface
{
    public function handle(object $command): mixed;
}
