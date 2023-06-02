<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use PDO;
use Symfony\Bridge\Doctrine\Middleware\Debug\Statement;

class LoginController implements ClassHandlerInterface
{
    private \PDO $connection;
    private string $redirectHome;
    private string $redirectLogin;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->redirectHome =  'Location: /home';
        $this->redirectLogin = 'Location: /login';
    }

    public function handle(): void
    {
        $this->authenticate();
    }

    private function authenticate()
    {
        $userName = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

        if(!$this->checkLoginState()){

            $tempUserName = $_COOKIE['tempUserName'];
            $tempUserPassword = $_COOKIE['tempUserPassword'];

            $userCheckQuery = "SELECT user_name, user_password FROM users WHERE user_name = :username AND user_password = :password";
            $statement = $this->connection->prepare($userCheckQuery);
            $statement->bindValue(':username', $tempUserName);
            $statement->bindValue(':password', $tempUserPassword);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if(isset($userName) && isset($password) || $result) {
                if($userName || $password) {
                    $user = $this->findUser($userName, $password);
                } else if ($result) {
                    $user = $this->findUser($tempUserName, $tempUserPassword);
                };

                // var_dump($user);
                // exit();
                if($user['user_id'] > 0){
                    $this->createRecord($user['user_id'], $user['user_name']);
                    header($this->redirectHome);
                } else{
                    echo "Usuário ou senha inválidos!";
                    exit();
                }
            }else{
                echo "<p>Não foi possível verificar valores dos inputs.</p>";
                // header($this->redirectLogin);
            }
        } else{
            echo 'Bem vindo, ' . $_SESSION['user_name'] . '! ' . 'Você já está logado!';
            header($this->redirectHome);
        }
    }

    public function checkLoginState()
    {
    if(!isset($_SESSION)){
        session_start();
    }

    if(isset($_COOKIE['user_id']) && isset($_COOKIE['sessions_token']) && isset($_COOKIE['sessions_serial'])){

         $query = "SELECT * FROM sessions WHERE user_id = :userId AND sessions_token = :token AND sessions_serial = :serial;";
        $statement = $this->connection->prepare($query);

        $id = $_COOKIE['user_id'];
        $token = $_COOKIE['sessions_token'];
        $serial = $_COOKIE['sessions_serial'];

        $statement->bindValue(':userId', $id, PDO::PARAM_INT);
        $statement->bindValue(':token', $token, PDO::PARAM_INT);
        $statement->bindValue(':serial', $serial, PDO::PARAM_INT);

        $statement->execute();

        $session = $statement->fetch(PDO::FETCH_ASSOC);

        if($session['user_id'] > 0)
        {
            if($session['user_id'] == $_COOKIE['user_id'] && $session['sessions_token'] == $_COOKIE['sessions_token'] && $session['sessions_serial'] == $_COOKIE['sessions_serial']){
                {
                    if($session['user_id'] == $_SESSION['sessions_id'] && $session['sessions_token'] == $_SESSION['sessions_token'] && $session['sessions_serial'] == $_SESSION['sessions_serial']){
                        return true;
                    } else{
                        $this->createSession($_COOKIE['user_name'], $_COOKIE['user_id'], $_COOKIE['sessions_token'], $_COOKIE['sessions_serial']);
                        return true;
                    }
                }
            }
        }
    }
    }

    private function createString($length): string
    {
        $string = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0pQAZWSXEDCRFVTGBYHNUJMIKOLP";
        return substr(str_shuffle($string), 0, 32);
    }

    private function createRecord($userId, $userName): void
    {
        $query = "INSERT INTO sessions (sessions_userid, sessions_token, sessions_serial, sessions_datetime) VALUES (:userid, :token, :serial, :date)";
        $token = $this->createString(32);
        $serial = $this->createString(32);

        $this->createCoockie($userName, $userId, $token, $serial);
        $this->createSession($userName, $userId, $token, $serial);

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':userid', $userId);
        $statement->bindValue(':token', $token);
        $statement->bindValue(':serial', $serial);
        $statement->bindValue(':date', '20/12/1999');
        $statement->execute();
    }



    private function createCoockie($userName, $userId, $token, $serial): void
    {
            setcookie('user_id', $userId, time() + (86400) * 30, "/");
            setcookie('user_name', $userName, time() + (86400) * 30, "/");
            setcookie('token', $token, time() + (86400) * 30, "/");
            setcookie('serial', $serial, time() + (86400) * 30, "/");
    }

    private function createSession($userName, $userId, $token, $serial):void
    {
        if(!isset($_SESSION)){
            session_start();
        }
            $_SESSION['user_name'] = $userName;
            $_SESSION['id'] = $userId;
            $_SESSION['token'] = $token;
            $_SESSION['serial'] = $serial;
    }

    private function deleteCoockies(): void
    {
        setcookie('user_id', '', time() - 1, "/");
        setcookie('user_name', '', time() - 1, "/");
        setcookie('token', '', time() - 1, "/");
        setcookie('serial', '', - 1, "/");
        header($this->redirectHome);
    }

    private function deleteSession($userId):void
    {
        $searchQuery = "SELECT * FROM sessions WHERE user_id = :user_id";
        $searchStatement = $this->connection->prepare($searchQuery);
        $searchStatement->bindValue(':user_id', $userId);
        $searchStatement->execute();
        $currentSession = $searchStatement->fetch(\PDO::FETCH_ASSOC);

        $deleteQuery = "DELETE FROM sessions WHERE user_id = :user_id;";
        $statement = $this->connection->prepare($deleteQuery);
        $statement->bindValue(':user_id', $currentSession['user_id']);
        $statement->execute();
    }

    private function findUser($userName, $password)
    {
        $query = "SELECT * FROM users WHERE user_name = :username AND user_password = :password";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':password', $password);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);

    }
}