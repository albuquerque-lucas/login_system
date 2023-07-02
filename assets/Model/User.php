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
      $queryCreate = "INSERT INTO users (user_username, user_email, user_password_hash, user_access_level_id, user_firstname, user_lastname, user_fullname)
        VALUES (:username, :usermail, :passwordhash, :useraccess, :firstname, :lastname, :fullname)";
        $filteredFirstName = $this->sanitizeString($userFirstName);
        $filteredLastName = $this->sanitizeString($userLastName);
        $fullName = "$filteredFirstName $filteredLastName";
        $basicAccessLevel = 1;
        $statement = $this->connection->prepare($queryCreate);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':usermail', $userMail);
        $statement->bindValue(':passwordhash', $userPassword);
        $statement->bindValue(':useraccess', $basicAccessLevel);
        $statement->bindValue(':firstname', $filteredFirstName);
        $statement->bindValue(':lastname', $filteredLastName);
        $statement->bindValue(':fullname', $fullName);
    
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

public function findUserSession($id)
{
  $querySelectSession = "SELECT u.* FROM users u JOIN sessions s ON u.user_id = :id";
  $statement = $this->connection->prepare($querySelectSession);
  $statement->bindValue(':id', $id);
  $statement->execute();
  return $statement->fetch(PDO::FETCH_ASSOC);
}

public function getUserAccess($id)
{
  $querySelect = "SELECT u.user_access_level_id, al.access_level_name
  FROM users u
  JOIN access_levels al ON u.user_access_level_id = al.access_level_id
  WHERE u.user_id = :id";

$statement = $this->connection->prepare($querySelect);
$statement->bindValue(':id', $id);
$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);
return $result;
}

public function sanitizeString($string)
{
    $filteredString = ucfirst(strtolower(trim($string)));
    return $filteredString;
}
}

?>