<?php
require 'vendor/autoload.php';

use LucasAlbuquerque\LoginSystem\View\NotFoundView;

$pathInfo = $_SERVER['PATH_INFO'];
$routes = require __DIR__ . '/routes/routes.php';

$controllerClass = $routes[$pathInfo];

if(!array_key_exists($pathInfo, $routes)){
    $notFoundView = new NotFoundView();
    $notFoundView->handle();
    exit();
}

$controller = new $controllerClass();
$controller->handle();