<?php


use LucasAlbuquerque\LoginSystem\Controller\{AuthController, TaskController, UserController};
use LucasAlbuquerque\LoginSystem\View\{LoginView, RegisterView};

$routes = [
    '' => TaskController::class,
    '/home' => TaskController::class,
    '/create' => TaskController::class,
    '/delete' => TaskController::class,
    '/login' => LoginView::class,
    '/authenticate' => AuthController::class,
    '/logout' => AuthController::class,
    '/register' => RegisterView::class,
    '/create-user' => UserController::class, 
    '/update-status' => TaskController::class,
    '/update-name-description' => TaskController::class,
];

return $routes;