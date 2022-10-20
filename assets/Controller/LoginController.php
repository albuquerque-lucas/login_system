<?php

namespace LucasAlbuquerque\LoginSystem\Controller;

use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use PDO;

class LoginController implements ClassHandlerInterface
{

    private \PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->redirect =  'Location: /home';
    }

    public function handle(): void
    {
        $this->currentLoginFunction();
    }

    private function currentLoginFunction()
    {

        $userName = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if(!$this->checkLoginState()){
            if(isset($userName) && isset($password)) {

                $query = "SELECT * FROM users WHERE name = :username AND password = :password";

                $statement = $this->connection->prepare($query);
                $statement->bindValue(':username', $userName);
                $statement->bindValue(':password', $password);
                $statement->execute();

                $user = $statement->fetch(PDO::FETCH_ASSOC);

                if($user['id'] > 0){
                    $this->createRecord($user['id'], $user['name']);
                    header($this->redirect);
                } else{
                    echo "Usuário ou senha inválidos!";
                    exit();
                }

            }else{
                header($this->redirect);
            }
        } else{
            echo 'Bem vindo, ' . $_SESSION['user_name'] . '! ' . 'Você já está logado!';
            header($this->redirect);
        }
    }

    public function checkLoginState()
    {

     if(!isset($_SESSION)){
        session_start();
     }

     if(isset($_COOKIE['user_id']) && isset($_COOKIE['token']) && isset($_COOKIE['serial'])){

         $query = "SELECT * FROM sessions WHERE user_id = :userId AND token = :token AND serial = :serial;";
         $statement = $this->connection->prepare($query);

         $id = $_COOKIE['user_id'];
         $token = $_COOKIE['token'];
         $serial = $_COOKIE['serial'];

         $statement->bindValue(':userId', $id, PDO::PARAM_INT);
         $statement->bindValue('token', $token, PDO::PARAM_INT);
         $statement->bindValue('serial', $serial, PDO::PARAM_INT);

         $statement->execute();

        $session = $statement->fetch(PDO::FETCH_ASSOC);

        if($session['user_id'] > 0)
        {
            if($session['user_id'] == $_COOKIE['user_id'] && $session['token'] == $_COOKIE['token'] && $session['serial'] == $_COOKIE['serial']){
                {
                    if($session['user_id'] == $_SESSION['id'] && $session['token'] == $_SESSION['token'] && $session['serial'] == $_SESSION['serial']){
                        return true;
                    } else{
                        $this->createSession($_COOKIE['user_name'], $_COOKIE['user_id'], $_COOKIE['token'], $_COOKIE['serial']);
                        return true;
                    }
                }

            }
        }

;
    }

    }

    private function createString($length): string
    {
        $string = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0pQAZWSXEDCRFVTGBYHNUJMIKOLP";
//        $s = '';
//        $r_new = '';
//        $r_old = '';
//
//        for($i=1; $i < $length; $i++){
//            while($r_old == $r_new)
//            {
//                $r_new = rand(0, 61);
//            }
//            $r_old = $r_new;
//            $s = $s.$string[$r_new];
//        }

        return substr(str_shuffle($string), 0, 32);
    }

    private function createRecord($userId, $userName): void
    {

        $deleteQuery = "DELETE FROM sessions WHERE user_id = :user_id;";
        $insertQuery = "INSERT INTO sessions (user_id, token, serial, date) VALUES (:user_id, :token, :serial, :date)";

        $deleteStatement = $this->connection->prepare($deleteQuery);
        $deleteStatement->bindValue(':user_id', $userId);
        $deleteStatement->execute();

        $token = $this->createString(32);
        $serial = $this->createString(32);

        $this->createCoockie($userName, $userId, $token, $serial);
        $this->createSession($userName, $userId, $token, $serial);

        $insertStatement = $this->connection->prepare($insertQuery);
        $insertStatement->bindValue(':user_id', $userId);
        $insertStatement->bindValue(':token', $token);
        $insertStatement->bindValue(':serial', $serial);
        $insertStatement->bindValue(':date', '20/12/1999');
        $insertStatement->execute();

    }



    private function createCoockie($userName, $userId, $token, $serial): void
    {
            setcookie('user_id', $userId, time() + (86400) * 30, "/");
            setcookie('user_name', $userName, time() + (86400) * 30, "/");
            setcookie('token', $token, time() + (86400) * 30, "/");
            setcookie('serial', $serial, time() + (86400) * 30, "/");
    }

    private function createSession($userName, $userId, $token, $serial)
    {
        if(!isset($_SESSION)){
            session_start();
        }
            $_SESSION['user_name'] = $userName;
            $_SESSION['id'] = $userId;
            $_SESSION['token'] = $token;
            $_SESSION['serial'] = $serial;
    }


}