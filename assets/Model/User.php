<?php

namespace LucasAlbuquerque\LoginSystem\Model;

use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use \PDO;

class User
{
  private \PDO $connection;
  public function __construct()
  {
    $this->connection = DatabaseConnection::connect();
  }

  public function create(
  $userName, 
  $userMail,
  $userPassword,
  $userFirstName,
  $userLastName
)
  {
    $foundUser = $this->findByName($userName);
    if (!$foundUser) {
      $queryCreate = "INSERT INTO users (user_username, user_email, user_password_hash, user_status, user_firstname, user_lastname)
        VALUES (:username, :usermail, :passwordhash, :userstatus, :firstname, :lastname)";
        $filteredFirstName = $this->sanitizeString($userFirstName);
        $filteredLastName = $this->sanitizeString($userLastName);
        $statement = $this->connection->prepare($queryCreate);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':usermail', $userMail);
        $statement->bindValue(':passwordhash', $userPassword);
        $statement->bindValue(':userstatus', 1);
        $statement->bindValue(':firstname', $filteredFirstName);
        $statement->bindValue(':lastname', $filteredLastName);
    
        $statement->execute();
        session_start();
        $_SESSION['tempUserName'] = $userName;
        $_SESSION['tempUserPassword'] = $userPassword;
        header('Location: /authenticate');
    } else {
      session_start();
      $message = "<span>Nome de usuário indisponível.</span>";
      $expTime = time() + 1;
      setcookie('errorMessage', $message, $expTime);
      header('Location: /register');
    }
  }

public function findByName($userName)
{
  $querySelectByName = "SELECT * FROM users WHERE user_username = :username";

  $statement = $this->connection->prepare($querySelectByName);
  $statement->bindValue(':username', $userName);
  $statement->execute();

  return $statement->fetch(PDO::FETCH_ASSOC);
}

public function findByNamePassword($userName, $password)
{
  $querySelect = "SELECT * FROM users WHERE user_username = :username";

  $statement = $this->connection->prepare($querySelect);
  $statement->bindValue(':username', $userName);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  if (!$result) {
    return false;
  }
  $userPasswordHash = $result['user_password_hash'];
  if (password_verify($password, $userPasswordHash)) {
    return $result;
} else if ($password === $userPasswordHash) {
  return $result;
}
  else {
    $message = "<span>Usuário ou senha inválidos. Errinho da classe User.</span>";
    $_SESSION['errorMessage'] = $message;
    $_SESSION['errorMessageType'] = 'errorMessage';
    header('Location: /login');
}
}

public function sanitizeString($string)
{
    $filteredString = ucfirst(strtolower(trim($string)));
    return $filteredString;
}
}

?>