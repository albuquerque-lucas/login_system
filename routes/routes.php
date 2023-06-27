<?php


use LucasAlbuquerque\LoginSystem\Controller\{AuthController, TaskController, UserController};

$routes = [
    '' => TaskController::class,
    '/home' => TaskController::class,
    '/create' => TaskController::class,
    '/delete' => TaskController::class,
    '/login' => AuthController::class,
    '/authenticate' => AuthController::class,
    '/logout' => AuthController::class,
    '/register' => AuthController::class,
    '/create-user' => AuthController::class, 
    '/update-task-status' => TaskController::class,
    '/update-task' => TaskController::class,
];

return $routes;