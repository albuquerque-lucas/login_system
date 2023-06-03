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
      $userFirstName = filter_input(INPUT_POST, 'firstname', FILTER_DEFAULT);
      $userLastName = filter_input(INPUT_POST, 'lastname', FILTER_DEFAULT);
      $this->createUser($userName, $userMail, $userPassword, $userFirstName, $userLastName);
    }

    private function createUser(string $userName, string $userMail, string $userPassword, string $userFirstName, string $userLastName)
    {
      $user = $this->findUser($userName, $userPassword);
      if(!$user){
        $createQuery = "INSERT INTO users (user_username, user_email, user_password, user_status, user_firstname, user_lastname) VALUES (:username, :usermail, :userpassword, :userstatus, :firstname, :lastname)";
        $statement = $this->connection->prepare($createQuery);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':usermail', $userMail);
        $statement->bindValue(':userpassword', $userPassword);
        $statement->bindValue(':firstname', $userFirstName);
        $statement->bindValue(':lastname', $userLastName);
        $statement->bindValue(':userstatus', 1);
        $statement->execute();
        session_start();
        $_SESSION['tempUserName'] = $userName;
        $_SESSION['tempUserPassword'] = $userPassword;
        header($this->redirectAuth);
      } else{
        echo "<h1>Usuário já existente.</h1>";
        header($this->redirectAuth);
      }
    }

    public function findUser($userName, $password)
    {
      $query = "SELECT * FROM users WHERE user_username = :username AND user_password = :password";

      $statement = $this->connection->prepare($query);
      $statement->bindValue(':username', $userName);
      $statement->bindValue(':password', $password);
      $statement->execute();

      return $statement->fetch(PDO::FETCH_ASSOC);
    }
}