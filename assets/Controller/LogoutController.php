<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use Cassandra\Statement;
use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;

class LogoutController implements ClassHandlerInterface
{

    private string $redirect;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->redirect = 'Location: /home';
    }

    public function handle(): void
    {
        $this->deleteSession($_GET['user_id']);
        $this->deleteCoockies();
    }

    private function deleteCoockies(): void
    {
        setcookie('user_id', '', time() - 1, "/");
        setcookie('user_name', '', time() - 1, "/");
        setcookie('token', '', time() - 1, "/");
        setcookie('serial', '', - 1, "/");
        header($this->redirect);
    }

    private function deleteSession():void
    {
        $searchQuery = "SELECT * FROM sessions WHERE id = :id";
        $searchStatement = $this->connection->prepare($searchQuery);
        $searchStatement->bindValue(':id', 0);
        $searchStatement->execute();
        $currentSession = $searchStatement->fetch(\PDO::FETCH_ASSOC);

        $deleteQuery = "DELETE FROM sessions WHERE user_id = :user_id;";
        $statement = $this->connection->prepare($deleteQuery);
        $statement->bindValue(':user_id', $currentSession['user_id']);
        $statement->execute();
    }
}