<?php

namespace LucasAlbuquerque\LoginSystem\Infrastructure;

use LucasAlbuquerque\LoginSystem\Repository\TaskRepository;

class PdoTaskRepository implements TaskRepository
{
    public function __construct()
    {
    }

    public function allTasks(): array
    {
        // TODO: Implement allTasks() method.
    }

    public function createTask(): bool
    {
        // TODO: Implement createTask() method.
    }

    public function removeTask(): bool
    {
        // TODO: Implement removeTask() method.
    }
}