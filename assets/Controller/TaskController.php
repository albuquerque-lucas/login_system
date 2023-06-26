<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use DateTime;
use DateTimeZone;
use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Traits\RenderHtmlTrait;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use LucasAlbuquerque\LoginSystem\Model\Task;
use LucasAlbuquerque\LoginSystem\View\TaskView;
use PDO;

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
                $this->taskView->handle();
                break;
            case '/create':
                $this->createTask();
                break;
            case '/delete':
                $this->removeTask($_POST['id']);
                break;
            case '/update-status':
                $taskData = filter_input(INPUT_POST, 'status-zero', FILTER_DEFAULT);
                $checkboxData = filter_input(INPUT_POST, 'status-checkbox', FILTER_DEFAULT);
                if ($taskData) {
                    $data = json_decode($taskData, true);
                } else if ($checkboxData) {
                    $data = json_decode($checkboxData, true);
                }
                list($taskId, $taskStatus) = $data;
                $this->setTaskStatus($taskId, $taskStatus);
                break;
            case '/update-name-description':
                $taskData = file_get_contents('php://input');
                $data = json_decode($taskData, true);
                $taskId = $data['taskId'];
                $text = $data['text'];
                $column = $data['column'];
                $this->setTaskTextData($taskId, $text, $column);
                break;
            case '/update-task':
                $taskData = file_get_contents('php://input');
                $data = json_decode($taskData, true);
                $taskId = $data['taskId'];
                $text = $data['text'];
                $column = $data['column'];
                $this->updateRequest($taskId, $column, $text);
                break;
        }


    }

    public function createTask():void
    {

        $name = filter_input(INPUT_POST, 'task_name', FILTER_DEFAULT);
        $description = filter_input(INPUT_POST, 'task_description', FILTER_DEFAULT);
        $initialStatus = 1;
        $creationDate = $this->getDateTime();
        $initDate = '---';
        $conclusionDate = '---';
        $this->Task->create(
        $name,
        $description,
        $initialStatus,
        $creationDate,
        $initDate,
        $conclusionDate
    );
        header('Location: /home');
    }

    public function updateRequest($id, $column, $text)
    {
        $this->Task->updateText($id, $column, $text);
    }

    public function removeTask(int $id):void
    {
        $this->Task->delete($id);
        header('Location: /home');
    }


    private function getDateTime()
    {
        $now = new DateTime('now');
        $now->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        $dateTime = $now->format('Y-m-d H:i:s');
        return $dateTime;
    }

    private function setTaskStatus(int $id, $statusCode): void
    {
        $query = "UPDATE tasks SET task_status = :status, task_status_name = :newStatusName WHERE task_id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id);

        switch($statusCode) {
            case 0:
                $dateTime = $this->getDateTime();
                $newStatusName = 'Iniciada / Pendente';
                $statement->bindValue(':status', 1);
                $statement->bindValue(':newStatusName', $newStatusName);
                $initQuery = "UPDATE tasks SET task_init_date = :initDate WHERE task_id = :id";
                $initStatement = $this->connection->prepare($initQuery);
                $initStatement->bindValue(':id', $id);
                $initStatement->bindValue(':initDate', $dateTime);
                $statement->execute();
                $initStatement->execute();
                header('Location: /home');
            break;
            case 1:
                $dateTime = $this->getDateTime();
                $newStatusName = 'Finalizada';
                $statement->bindValue(':status', 2);
                $statement->bindValue(':newStatusName', $newStatusName);
                $finishQuery = "UPDATE tasks SET task_conclusion_date = :conclusionDate WHERE task_id = :id";
                $finishStatement = $this->connection->prepare($finishQuery);
                $finishStatement->bindValue(':id', $id);
                $finishStatement->bindValue(':conclusionDate', $dateTime);
                $statement->execute();
                $finishStatement->execute();
                header('Location: /home');
            break;
            case 2:
                $dateTime = '---';
                $newStatusName = 'Iniciada / Pendente';
                $statement->bindValue(':status', 1);
                $statement->bindValue(':newStatusName', $newStatusName);
                $unfinishQuery = "UPDATE tasks SET task_conclusion_date = :conclusionDate WHERE task_id = :id";
                $unfinishStatement = $this->connection->prepare($unfinishQuery);
                $unfinishStatement->bindValue(':id', $id);
                $unfinishStatement->bindValue(':conclusionDate', $dateTime);
                $statement->execute();
                $unfinishStatement->execute();
                header('Location: /home');
            break;
            header('Location: /home');
        }
    }

    public function setTaskTextData($id, $value, $column): void
    {
        $allowedColumns = ['task_name', 'task_description'];
        $query = "UPDATE tasks SET $column = :value WHERE task_id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':value', $value);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}