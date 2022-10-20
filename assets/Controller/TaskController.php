<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Helpers\RenderHtmlTrait;
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

    }

    public function handle():void
    {
        switch ($_SERVER['PATH_INFO']){
            case '/create':
                $this->createTask($_POST['name'], $_POST['description']);
                break;
            case '/delete':
                $this->removeTask($_POST['id']);
                break;
        }


    }

    public function createTask(string $name, string $description):void
    {
        $task = new Task(null, $name, $description);
        $query = "INSERT into tasks(name, description) VALUES (:name , :description)";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':name', $task->name());
        $statement->bindValue(':description', $task->description());
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
}