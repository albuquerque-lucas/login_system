<?php


use LucasAlbuquerque\LoginSystem\Controller\{LoginController, LogoutController, TaskController};
use LucasAlbuquerque\LoginSystem\View\{LoginView, TaskView};

$routes = [
    '' => TaskController::class,
    '/home' => TaskController::class,
    '/create' => TaskController::class,
    '/delete' => TaskController::class,
    '/concludeTask' => TaskController::class,
    '/login' => LoginView::class,
    '/authenticate' => LoginController::class,
    '/logout' => LogoutController::class,
];

return $routes;