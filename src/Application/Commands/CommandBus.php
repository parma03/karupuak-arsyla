<?php
// src/Application/Commands/CommandBus.php
namespace Application\Commands;

class CommandBus
{
    private array $handlers = [];

    public function register(string $command, string $handler): void
    {
        $this->handlers[$command] = $handler;
    }

    public function dispatch(object $command): mixed
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new \RuntimeException("No handler registered for command: $commandClass");
        }

        $handler = new $this->handlers[$commandClass]();
        return $handler->handle($command);
    }
}
