<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use Exception;
use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use PDO;

class AuthController implements ClassHandlerInterface
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

        switch($_SERVER['PATH_INFO']){
            case '/authenticate':
                $this->authenticate();
                break;
            case '/logout':
                $this->deleteSession($_POST['userid']);
                break;
        }
    }

    private function authenticate()
    {
        $userName = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

        if(!$this->checkLoginState()){
            $tempUserName = $_SESSION['tempUserName'];
            $tempUserPassword = $_SESSION['tempUserPassword'];

            $userCheckQuery = "SELECT user_username, user_password FROM users WHERE user_username = :username AND user_password = :password";
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
                if($user['user_id'] > 0){
                    $this->createRecord($user['user_id'], $user['user_username']);
                    $message = "<h4>Seja bem vindo, {$user['user_firstname']} {$user['user_lastname']}!</h4>";
                    $this->setUserMessage('welcomeMessage', $message, 180);
                    header($this->redirectHome);
                } else{
                    $message = "<span>Usuário ou senha inválidos.</span>";
                    $this->setUserMessage('errorMessage', $message, 15);
                    header($this->redirectLogin);
                }
            }else{
                $message = "<span>Não foi possível verificar valores dos inputs.</span>";
                $this->setUserMessage('errorMessage', $message, 15);
                header($this->redirectLogin);
            }
        } else{
            $message = '<p>Você já está logado!</p>';
            $this->setUserMessage('authMessage', $message, 15);
            header($this->redirectHome);
        }
    }

    public function checkLoginState()
    {
    if(!isset($_SESSION)){
        session_start();
    }

    if(isset($_COOKIE['sessions_userid']) && isset($_COOKIE['sessions_token']) && isset($_COOKIE['sessions_serial'])){
        $query = "SELECT * FROM sessions WHERE sessions_userid = :userId AND sessions_token = :token AND sessions_serial = :serial;";
        $statement = $this->connection->prepare($query);

        $id = $_COOKIE['sessions_userid'];
        $token = $_COOKIE['sessions_token'];
        $serial = $_COOKIE['sessions_serial'];

        $statement->bindValue(':userId', $id, PDO::PARAM_INT);
        $statement->bindValue(':token', $token, PDO::PARAM_INT);
        $statement->bindValue(':serial', $serial, PDO::PARAM_INT);

        $statement->execute();

        $session = $statement->fetch(PDO::FETCH_ASSOC);

        if($session['sessions_userid'] > 0)
        {
            if($session['sessions_userid'] == $_COOKIE['sessions_userid'] && $session['sessions_token'] == $_COOKIE['sessions_token'] && $session['sessions_serial'] == $_COOKIE['sessions_serial']){
                {
                    if($session['sessions_userid'] == $_SESSION['sessions_id'] && $session['sessions_token'] == $_SESSION['sessions_token'] && $session['sessions_serial'] == $_SESSION['sessions_serial']){
                        return true;
                    } else{
                        $this->createSession($_COOKIE['user_username'], $_COOKIE['sessions_userid'], $_COOKIE['sessions_token'], $_COOKIE['sessions_serial']);
                        return true;
                    }
                }
            }
        } else {

        }
    } else {

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
            setcookie('sessions_userid', $userId, time() + (86400) * 30, "/");
            setcookie('sessions_username', $userName, time() + (86400) * 30, "/");
            setcookie('sessions_token', $token, time() + (86400) * 30, "/");
            setcookie('sessions_serial', $serial, time() + (86400) * 30, "/");
    }

    private function createSession($userName, $userId, $token, $serial):void
    {
        if(!isset($_SESSION)){
            session_start();
        }
            $_SESSION['sessions_username'] = $userName;
            $_SESSION['sessions_id'] = $userId;
            $_SESSION['sessions_token'] = $token;
            $_SESSION['sessions_serial'] = $serial;
    }

    private function deleteCookies(): void
    {
        setcookie('sessions_userid', '', time() - 1, "/");
        setcookie('sessions_username', '', time() - 1, "/");
        setcookie('sessions_token', '', time() - 1, "/");
        setcookie('sessions_serial', '', - 1, "/");
        header($this->redirectHome);
    }

    public function deleteSession($userId):void
    {
        $searchQuery = "SELECT * FROM sessions WHERE sessions_userid = :user_id";
        $searchStatement = $this->connection->prepare($searchQuery);
        $searchStatement->bindValue(':user_id', $userId);
        $searchStatement->execute();
        $currentSession = $searchStatement->fetch(\PDO::FETCH_ASSOC);

        $deleteQuery = "DELETE FROM sessions WHERE sessions_userid = :user_id;";
        $statement = $this->connection->prepare($deleteQuery);
        $statement->bindValue(':user_id', $currentSession['sessions_userid']);
        $statement->execute();
        $this->deleteCookies();
        session_destroy();
        header($this->redirectLogin);
    }

    private function findUser($userName, $password)
    {
        $query = "SELECT * FROM users WHERE user_username = :username AND user_password = :password";

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':username', $userName);
        $statement->bindValue(':password', $password);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $userPasswordHash = $result['user_password_hash'];

        if (password_verify($password, $userPasswordHash)) {
            return $result;
        } else {
            $expiration = time() + 30;
            $message = "<span>Usuário ou senha inválidos.</span>";
            setcookie('errorMessage', $message, $expiration);
            header($this->redirectLogin);
        }

    }

    public function checkSessionStatus()
    {
        $sessionsQuery = "SELECT * FROM sessions";
        $statement = $this->connection->prepare($sessionsQuery);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $status = $this->checkLoginState();
        if(!$result){
            return false;
        } else {
            $sessionsUserId = $result['sessions_userid'];
            $userNameQuery = "SELECT u.* FROM users u JOIN sessions s ON u.user_id = $sessionsUserId";
            $userNameStatement = $this->connection->prepare($userNameQuery);
            $userNameStatement->execute();
            $user = $userNameStatement->fetch(PDO::FETCH_ASSOC);
        }

        return [$status, $user];
    }

    public function setUserMessage($type, $message, $expTime)
    {
        $expiration = time() + $expTime;
        setcookie($type, $message, $expiration);
    }
}