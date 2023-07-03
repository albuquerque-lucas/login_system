<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Interface\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use LucasAlbuquerque\LoginSystem\Model\Task;
use LucasAlbuquerque\LoginSystem\View\TaskView;
use LucasAlbuquerque\LoginSystem\Utils\DateTimeManager;

class TaskController implements ClassHandlerInterface
{
    use RenderHtmlTrait;

    private \PDO $connection;
    private TaskView $taskView;
    private Task $Task;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->Task = new Task();
        $this->taskView = new TaskView();
    }

    public function handle():void
    {
        switch ($_SERVER['PATH_INFO']){
            case '':
            case '/home':
                $this->taskView->renderHomePage();
                break;
            case '/tasks':
                $this->taskView->renderTaskPage();
                break;
            case '/create': 
                $this->createRequest();
                break;
            case '/delete':
                $this->removeRequest($_POST['id']);
                break;
            case '/update-task-status':
                $this->updateStatusRequest($_POST['status-zero']);
                break;
            case '/update-task':
                $taskData = file_get_contents('php://input');
                $data = json_decode($taskData, true);
                $this->updateRequest($data);
            break;
        }


    }

    public function updateStatusRequest($id)
    {
        $this->Task->updateStatus($id);
        $this->Task->updateDateTime($id);
        header('Location: /home');
    }

    public function createRequest():void
    {

        $name = filter_input(INPUT_POST, 'task_name', FILTER_DEFAULT);
        $description = filter_input(INPUT_POST, 'task_description', FILTER_DEFAULT);
        $userId = filter_input(INPUT_POST, 'task_user_id', FILTER_DEFAULT);
        $initialStatus = 1;
        $creationDate = DateTimeManager::getDateTime();
        $initDate = '---';
        $conclusionDate = '---';
        $this->Task->create(
        $name,
        $description,
        $initialStatus,
        $creationDate,
        $initDate,
        $conclusionDate,
        $userId
    );
        header('Location: /home');
    }

    public function updateRequest($data)
    {

        $taskId = $data['taskId'];
        $text = $data['text'];
        $column = $data['column'];
        $dataToUpdate = [$column => $text];
        $this->Task->update($taskId, $dataToUpdate);
    }

    public function removeRequest(int $id):void
    {
        $this->Task->delete($id);
        header('Location: /home');
    }
}