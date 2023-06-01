<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use PDO;
use Symfony\Bridge\Doctrine\Middleware\Debug\Statement;

class UserController implements ClassHandlerInterface
{
  private \PDO $connection;
  private string $redirectAuth;
  public function __construct()
    {
      $this->connection = DatabaseConnection::connect();
      $this->redirectAuth = 'Location: /authenticate';
    }

    public function handle(): void
    {
      $userName = filter_input(INPUT_POST,'username', FILTER_DEFAULT);
      $userMail = filter_input(INPUT_POST,'email', FILTER_DEFAULT);
      $userPassword = filter_input(INPUT_POST,'password', FILTER_DEFAULT);
      echo "<h1>Usuário criado.</h1>";
      $this->createUser($userName, $userMail, $userPassword);
    }

    private function createUser($userName, $userMail, $userPassword)
    {
      $user = $this->findUser($userName, $userPassword);
      if(!$user){
        $createQuery = "INSERT INTO users (user_name, user_email, user_password, user_status) VALUES (:username, :usermail, :userpassword, :userstatus)";
        $statement = $this->connection->prepare($createQuery);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':usermail', $userMail);
        $statement->bindValue(':userpassword', $userPassword);
        $statement->bindValue(':userstatus', 1);
        $statement->execute();
      } else{
        echo "<h1>Usuário já existente.</h1>";
      }
    }

    public function findUser($userName, $password)
    {
      $query = "SELECT * FROM users WHERE user_name = :username AND user_password = :password";

      $statement = $this->connection->prepare($query);
      $statement->bindValue(':username', $userName);
      $statement->bindValue(':password', $password);
      $statement->execute();

      return $statement->fetch(PDO::FETCH_ASSOC);
    }
}