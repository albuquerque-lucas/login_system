<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use LucasAlbuquerque\LoginSystem\Model\Task;

class TaskView implements ClassHandlerInterface
{
    use RenderHtmlTrait;

    private Task $Task;

    public function __construct()
    {
        $this->Task = new Task();
    }

    public function handle(): void
    {
        $tasks = $this->Task->getAllTasks();
        echo $this->renderHtml('views/home.php', [
            'tasks' => $tasks,
            'taskModel' => $this->Task,
        ]);
    }
}