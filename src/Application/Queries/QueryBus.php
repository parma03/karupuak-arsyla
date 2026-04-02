<?php
// src/Application/Queries/QueryBus.php
namespace Application\Queries;

class QueryBus
{
    private array $handlers = [];

    public function register(string $query, string $handler): void
    {
        $this->handlers[$query] = $handler;
    }

    public function dispatch(object $query): mixed
    {
        $queryClass = get_class($query);
        if (!isset($this->handlers[$queryClass])) {
            throw new \RuntimeException("No handler registered for query: $queryClass");
        }
        $handler = new $this->handlers[$queryClass]();
        return $handler->handle($query);
    }
}
