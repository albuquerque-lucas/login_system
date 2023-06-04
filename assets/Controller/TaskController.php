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
            case '/update-task':
                $this->setTaskStatus($_POST['taskId'], $_POST['status']);
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
        var_dump($id);
        var_dump($statusCode);
        exit();
        // $query = "UPDATE tasks SET task_status = :status, task_status_name = :statusName WHERE id = :id";
        // $statement = $this->connection->prepare($query);
        // $statement->bindValue(':id', $id);

        // switch($statusCode) {
        //     case 0:
        //         $dateTime = $this->getDateTime();
        //         $newStatusName = 'Iniciada / Pendente';
        //         $statement->bindValue(':status', 1);
        //         $initQuery = "UPDATE tasks SET task_init_date = :initDate, task_status_name = :newStatusName WHERE id = :id";
        //         $initStatement = $this->connection->prepare($initQuery);
        //         $initStatement->bindValue(':initDate', $dateTime);
        //         $initStatement->bindValue(':newStatusName', $newStatusName);
        //         $statement->execute();
        //         $initStatement->execute();
        //     break;
        //     case 1:
        //         $dateTime = $this->getDateTime();
        //         $newStatusName = 'Finalizada';
        //         $statement->bindValue(':status', 2);
        //         $finishQuery = "UPDATE tasks SET task_conclusion_date = :conclusionDate, task_status_name = :newStatusName WHERE id = :id";
        //         $finishStatement = $this->connection->prepare($finishQuery);
        //         $finishStatement->bindValue(':conclusionDate', $dateTime);
        //         $finishStatement->bindValue(':newStatusName', $newStatusName);
        //         $statement->execute();
        //         $finishStatement->execute();
        //     break;
        //     case 2:
        //         $dateTime = '---';
        //         $newStatusName = 'Iniciada / Pendente';
        //         $statement->bindValue(':status', 1);
        //         $unfinishQuery = "UPDATE tasks SET task_conclusion_date = :conclusionDate, task_status_name = :newStatusName WHERE id = :id";
        //         $unfinishStatement = $this->connection->prepare($unfinishQuery);
        //         $unfinishStatement->bindValue(':conclusionDate', $dateTime);
        //         $unfinishStatement->bindValue(':newStatusName', $newStatusName);
        //         $statement->execute();
        //         $unfinishStatement->execute();
        //     break;
        // }
        // header($this->redirect);
    }
}