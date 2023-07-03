<?php

namespace LucasAlbuquerque\LoginSystem\View;

use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;
use LucasAlbuquerque\LoginSystem\Model\Task;

class TaskView
{
    use RenderHtmlTrait;

    private Task $Task;

    public function __construct()
    {
        $this->Task = new Task();
    }

    public function renderHomePage(): void
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

    public function renderTaskPage(): void
    {
        $sessionInfo = SessionManager::verifySessionInformation();
        $toSendData = array_slice($sessionInfo, 0, 2, true) + array_slice($sessionInfo, -1, 1, true);
        $status = $toSendData[0];
        $user = $toSendData[1];
        $userTasks = $toSendData[5];
        echo $this->renderHtml('views/tasks.php', [
            'userTasks' => $userTasks,
            'taskModel' => $this->Task,
            'status' => $status,
            'user' => $user,
        ]);
    }
}