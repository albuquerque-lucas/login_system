<?php

namespace LucasAlbuquerque\LoginSystem\Controller;
use LucasAlbuquerque\LoginSystem\Exceptions\AuthException;
use LucasAlbuquerque\LoginSystem\Handler\ClassHandlerInterface;
use LucasAlbuquerque\LoginSystem\Model\Session;
use LucasAlbuquerque\LoginSystem\Model\User;
use LucasAlbuquerque\LoginSystem\Utils\CookieManager;
use LucasAlbuquerque\LoginSystem\Utils\SessionManager;
use LucasAlbuquerque\LoginSystem\View\LoginView;
use LucasAlbuquerque\LoginSystem\View\RegisterView;

class AuthController implements ClassHandlerInterface
{
    private $sessionModel;
    private $userModel;
    private $loginView;
    private $registerView;

    public function __construct()
    {
        $this->sessionModel = new Session();
        $this->userModel = new User();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
    }

    public function handle(): void
    {

        switch($_SERVER['PATH_INFO']){
            case '/authenticate':
                $this->authenticate();
                break;
            case '/logout':
                $this->deleteRequest();
                break;
            case '/login':
                $this->loginView->handle();
                break;
            case '/register':
                $this->registerView->handle();
                break;
            case '/create-user':
                $this->createUserRequest();
                break;
        }
    }

    private function createUserRequest()
    {
        $userName = filter_input(INPUT_POST,'username', FILTER_DEFAULT);
        $userMail = filter_input(INPUT_POST,'email', FILTER_DEFAULT);
        $userPassword = filter_input(INPUT_POST,'password', FILTER_DEFAULT);
        $userFirstName = filter_input(INPUT_POST, 'firstname', FILTER_DEFAULT);
        $userLastName = filter_input(INPUT_POST, 'lastname', FILTER_DEFAULT);
        $passwordHash = password_hash($userPassword, PASSWORD_ARGON2ID);
        $this->userModel->create($userName, $userMail, $passwordHash, $userFirstName, $userLastName);
    }

    private function authenticate(): void
    {
        $userName = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
        
        try{
            if(!SessionManager::verifySessionState()){
                if (!(isset($userName) && isset($password))) {
                    $tempUserName = $_SESSION['tempUserName'];
                    $tempUserPassword = $_SESSION['tempUserPassword'];
                }
                $result = $this->userModel->findByNamePassword($tempUserName, $tempUserPassword);
                if(isset($userName) && isset($password) || $result) {
                    if(!$result && ($userName || $password)) {
                        $user = $this->userModel->findByNamePassword($userName, $password);
                    } else if ($result) {
                        $user = $this->userModel->findByNamePassword($tempUserName, $tempUserPassword);
                    } else {
                        $message = "<span>Você precisa digitar um nome de usuário válido.</span>";
                        throw new AuthException($message, 'errorMessage');
                    };
                    
                    if($user['user_id'] > 0){
                        $this->sessionModel->insert($user['user_id'], $user['user_username']);
                        $message = "<h4>Seja bem vindo, {$user['user_fullname']}!</h4>";
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

    public function deleteRequest(): void
    {
        $userId = filter_input(INPUT_POST, 'userid', FILTER_DEFAULT);
        $currentSession = $this->sessionModel->findById($userId);
        $this->sessionModel->delete($currentSession);
        CookieManager::deleteCookies();
        header('Location: /login');
    }
}