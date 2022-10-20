<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Helpers\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use LucasAlbuquerque\LoginSystem\Repository\TaskRepository;

class TaskView implements ClassHandlerInterface
{
    use RenderHtmlTrait;

    private \PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();

    }

    public function handle(): void
    {
        $tasks = $this->allTasks();
        echo $this->renderHtml('views/home.php', [
            'tasks' => $tasks
        ]);
    }

    public function allTasks():array
    {
        $statement = $this->connection->query("SELECT * FROM tasks;");
        return $statement->fetchAll();
    }
}