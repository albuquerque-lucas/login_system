<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;
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
        $tasks = $this->Task->getAll();
        $sessionInfo = SessionManager::verifySessionInformation();
        list($status, $user) = $sessionInfo;
        echo $this->renderHtml('views/home.php', [
            'tasks' => $tasks,
            'taskModel' => $this->Task,
            'status' => $status,
            'user' => $user,
        ]);
    }
}