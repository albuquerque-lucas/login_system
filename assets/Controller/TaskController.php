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
            case '/concludeTask':
                $this->setTaskStatus($_POST['id'], $_POST['status']);
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

        $task = new Task(
        0,
        $taskName,
        $taskDescription,
        $taskInitialStatus,
        $taskStatusName,
        $taskCreationDate
        );
        $query = "INSERT into tasks(task_name, task_description, task_status, task_status_name, task_creation_date)
        VALUES (:task_name , :task_description, :statusCode, :statusName, :creationDate)";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':task_name', $task->getName());
        $statement->bindValue(':task_description', $task->getDescription());
        $statement->bindValue(':statusCode', $task->getStatus());
        $statement->bindValue('statusName', $task->getStatusName());
        $statement->bindValue(':creationDate', $task->getCreationDate());
        $statement->execute();
        header($this->redirect);
    }

    public function removeTask(int $id):void
    {
        $statement = $this->connection->prepare('DELETE FROM tasks WHERE id = :id');
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


            $query = "UPDATE tasks SET status = :status WHERE tasks.id = :id";
            $statement = $this->connection->prepare($query);
            $statement->bindValue(':id', $id);

            if($statusCode ==! null){
                $statement->bindValue(':status', 1);
                $conclusionDate = $this->getDateTime();
                $conclusionQuery = "UPDATE tasks set conclusionDate = :conclusionDate WHERE tasks.id = :id";
                $conclusionStmt = $this->connection->prepare($conclusionQuery);
                $conclusionStmt->bindValue(':conclusionDate', $conclusionDate);
                $conclusionStmt->bindValue(':id', $id);
                $conclusionStmt->execute();
            } else{
                $statement->bindValue(':status', 0);
                $conclusionQuery = "UPDATE tasks set conclusionDate = :conclusionDate WHERE tasks.id = :id";
                $conclusionStmt = $this->connection->prepare($conclusionQuery);
                $conclusionStmt->bindValue(':conclusionDate', null);
                $conclusionStmt->bindValue(':id', $id);
                $conclusionStmt->execute();
            }

            $statement->execute();
            header($this->redirect);


    }
}