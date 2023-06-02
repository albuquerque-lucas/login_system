<?php


use LucasAlbuquerque\LoginSystem\Controller\{AuthController, TaskController, UserController};
use LucasAlbuquerque\LoginSystem\View\{LoginView, TaskView, RegisterView};

$routes = [
    '' => TaskController::class,
    '/home' => TaskController::class,
    '/create' => TaskController::class,
    '/delete' => TaskController::class,
    '/concludeTask' => TaskController::class,
    '/login' => LoginView::class,
    '/authenticate' => AuthController::class,
    '/logout' => AuthController::class,
    '/register' => RegisterView::class,
    '/create-user' => UserController::class, 
];

return $routes;