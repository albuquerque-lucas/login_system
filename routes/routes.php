<?php


use LucasAlbuquerque\LoginSystem\Controller\{AuthController, ProfileController, TaskController};

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
    '/profile' => ProfileController::class,
];

return $routes;