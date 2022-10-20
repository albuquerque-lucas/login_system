<?php


use LucasAlbuquerque\LoginSystem\Controller\{LoginController, LogoutController, TaskController};
use LucasAlbuquerque\LoginSystem\View\{LoginView, TaskView};

$routes = [
    '' => LoginView::class,
    '/home' => TaskView::class,
    '/create' => TaskController::class,
    '/delete' => TaskController::class,
    '/login' => LoginView::class,
    '/authenticate' => LoginController::class,
    '/logout' => LogoutController::class,
];

return $routes;