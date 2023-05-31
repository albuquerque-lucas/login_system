<?php
require 'vendor/autoload.php';

use LucasAlbuquerque\LoginSystem\Infrastructure\DatabaseConnection;


$pdo = DatabaseConnection::connect('localhost', 'login_system', 'root', '');
$pathInfo = $_SERVER['PATH_INFO'];
var_dump($pathInfo);
$routes = require __DIR__ . '/routes/routes.php';

$controllerClass = $routes[$pathInfo];

if(!array_key_exists($pathInfo, $routes)){
    echo "Erro 404";
    exit();
}



$controller = new $controllerClass();
$controller->handle();