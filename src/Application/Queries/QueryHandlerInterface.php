<?php
// src/Application/Queries/QueryHandlerInterface.php
namespace Application\Queries;

interface QueryHandlerInterface
{
    public function handle(object $query): mixed;
}
