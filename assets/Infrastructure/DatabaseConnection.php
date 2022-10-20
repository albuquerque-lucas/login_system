<?php

namespace LucasAlbuquerque\LoginSystem\Infrastructure;

use PDO;
use PDOException;

class DatabaseConnection
{

    public static function connect()
    {
        try {
            return  new PDO("mysql:hostname=localhost;dbname=login_system", 'root', '');;
        } catch (PDOException $PDOException) {
            echo "Falha na conexão: {$PDOException->getMessage()}";
        }

    }

}