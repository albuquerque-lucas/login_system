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

    public function currentLoginFunction()
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
//                    $this->createRecord($user['id'], $user['name']);
//                    header($this->redirect);
                    $this->createString();
                }

            }else{
                header($this->redirect);
            }
        } else{
            echo 'Você já está logado!';
        }
    }

    private function checkLoginState()
    {


     if(!isset($_SESSION['id']) || !isset($_COOKIE['PHPSESSID'])){
        session_start();
     }

     if(isset($_COOKIE['userId']) && isset($_COOKIE['token']) && isset($_COOKIE['serial'])){

         $query = "SELECT * FROM sessions WHERE user_id = :userid AND token = :token AND serial = :serial;";
         $statement = $this->connection->prepare($query);

         $id = $_COOKIE['userId'];
         $token = $_COOKIE['token'];
         $serial = $_COOKIE['serial'];

         $statement->bindValue(':userId', $id, PDO::PARAM_INT);
         $statement->bindValue('token', $token, PDO::PARAM_INT);
         $statement->bindValue('serial', $serial, PDO::PARAM_INT);

         $statement->execute();

        $session = $statement->fetch(PDO::FETCH_ASSOC);

        if($session['user_id'] > 0)
        {
            if($session['user_id'] == $_COOKIE['userId'] && $session['token'] == $_COOKIE['token'] && $session['serial'] == $_COOKIE['serial']){
                return true;
            }
        }

;
    }

    }

    public function createString()
    {
        $string = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0pQAZWSXEDCRFVTGBYHNUJMIKOLP";
        $s = '';
        $r_new = '';
        $r_old = '';

        for($i=1; $i < strlen($string); $i++){
            while($r_old == $r_new)
            {
                $r_new = rand(0, 61);
            }
            $r_old = $r_new;
            $s = $s.$string[$r_new];
        }

        echo $s;
    }




}