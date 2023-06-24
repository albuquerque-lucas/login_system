<?php

namespace LucasAlbuquerque\LoginSystem\Controller;
use LucasAlbuquerque\LoginSystem\Exceptions\AuthException;
use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;
use PDO;
use LucasAlbuquerque\LoginSystem\Model\Session;
use LucasAlbuquerque\LoginSystem\Utils\CookieManager;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;
use LucasAlbuquerque\LoginSystem\View\LoginView;
use LucasAlbuquerque\LoginSystem\View\RegisterView;

class AuthController implements ClassHandlerInterface
{
    private \PDO $connection;
    private $sessionModel;

    public function __construct()
    {
        $this->connection = DatabaseConnection::connect();
        $this->sessionModel = new Session();
    }

    public function handle(): void
    {

        switch($_SERVER['PATH_INFO']){
            case '/authenticate':
                $this->authenticate();
                break;
            case '/logout':
                $this->deleteRequest($_POST['userid']);
                break;
            case '/login':
                $loginView = new LoginView();
                $loginView->handle();
                break;
            case '/register':
                $registerView = new RegisterView();
                $registerView->handle();
        }
    }

    private function authenticate(): void
    {
        $userName = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

        try{
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
                    } else {
                        $message = "<span>Você precisa digitar um nome de usuário válido.</span>";
                        throw new AuthException($message, 'errorMessage');
                    };
    
                    if($user['user_id'] > 0){
                        $this->sessionModel->insert($user['user_id'], $user['user_username']);
                        $message = "<h4>Seja bem vindo, {$user['user_firstname']} {$user['user_lastname']}!</h4>";
                        $_SESSION['welcomeMessage'] = $message;
                        header('Location: /home');
                    } else{
                        $message = "<span>Usuário ou senha inválidos.</span>";
                        throw new AuthException($message, 'errorMessage');
                    }
                }else{
                    $message = "<span>Não foi possível verificar valores dos inputs.</span>";
                    throw new AuthException($message, 'errorMessage');
                }
            } else{
                $message = "<span>Você já está logado!</span>";
                throw new AuthException($message, 'authMessage');
            }
        } catch(AuthException $exception) {
            $message = $exception->getMessage();
            $messageType = $exception->getMessageType();
            $_SESSION['errorMessage'] = $message;
            $_SESSION['errorMessageType'] = $messageType;
            header('Location: /login');
        }

        
    }

    private function checkLoginState(): bool
    {
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_COOKIE['sessions_userid']) && isset($_COOKIE['sessions_token']) && isset($_COOKIE['sessions_serial'])){
        
        $id = $_COOKIE['sessions_userid'];
        $token = $_COOKIE['sessions_token'];
        $serial = $_COOKIE['sessions_serial'];
        $session = $this->sessionModel->findSession($id, $token, $serial);
        if ($session['sessions_id'] > 0) {
            if($session['sessions_userid'] == $_COOKIE['sessions_userid']
            && $session['sessions_token'] == $_COOKIE['sessions_token']
            && $session['sessions_serial'] == $_COOKIE['sessions_serial']) {
                    if($session['sessions_userid'] == $_SESSION['sessions_id']
                    && $session['sessions_token'] == $_SESSION['sessions_token']
                    && $session['sessions_serial'] == $_SESSION['sessions_serial']) {
                        return true;
                    } else{
                        SessionManager::createSession(
                            $_COOKIE['user_username'],
                            $id,
                            $token,
                            $serial
                        );
                        return true;
                    }
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }
    return false;
    }

    public function deleteRequest($userId): void
    {
        $currentSession = $this->sessionModel->findById($userId);
        $this->sessionModel->delete($currentSession);
        CookieManager::deleteCookies();
        header('Location: /login');
    }

    private function findUser($userName, $password): array
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
            header('Location: /login');
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
}