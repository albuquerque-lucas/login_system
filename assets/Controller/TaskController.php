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
    private string $redirect;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->redirect = 'Location: /home';
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
                echo "Oi!!!!!";
                break;
        }


    }

    public function createTask():void
    {

        $taskName = filter_input(INPUT_POST, 'task_name', FILTER_DEFAULT);
        $taskDescription = filter_input(INPUT_POST, 'task_description', FILTER_DEFAULT);
        $taskInitialStatus = 0;
        $taskCreationDate = $this->getDateTime();
        $taskStatusName = 'Criado';
        $initialConclusionDate = '---';
        $initialInitDate = '---';

        $task = new Task(
        0,
        $taskName,
        $taskDescription,
        $taskInitialStatus,
        $taskStatusName,
        $taskCreationDate
        );
        $task->setConclusionDate($initialConclusionDate);
        $task->setInitDate($initialInitDate);
        $query = "INSERT into tasks(task_name, task_description, task_status, task_status_name, task_creation_date, task_init_date, task_conclusion_date)
        VALUES (:task_name , :task_description, :statusCode, :statusName, :creationDate, :initDate, :conclusionDate)";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':task_name', $task->getName());
        $statement->bindValue(':task_description', $task->getDescription());
        $statement->bindValue(':statusCode', $task->getStatus());
        $statement->bindValue('statusName', $task->getStatusName());
        $statement->bindValue(':creationDate', $task->getCreationDate());
        $statement->bindValue(':initDate', $task->getInitDate());
        $statement->bindValue(':conclusionDate', $task->getConclusionDate());
        $statement->execute();
        header($this->redirect);
    }

    public function removeTask(int $id):void
    {
        $statement = $this->connection->prepare('DELETE FROM tasks WHERE task_id = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        header($this->redirect);
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
                header($this->redirect);
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
                header($this->redirect);
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
                header($this->redirect);
            break;
            header($this->redirect);
        }
    }
}