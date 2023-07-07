<?php

namespace LucasAlbuquerque\LoginSystem\Infrastructure;

use PDO;
use PDOException;

class DatabaseConnection
{
    public static function connect()
    {
        $hostName = 'localhost';
        $dbName = 'u663236748_login_system';
        $userName = 'u663236748_root';
        $password = 'Mamao123Mamao';
        try {
            return new PDO("mysql:host=$hostName;dbname=$dbName", $userName, $password);
            echo "conectado";
        } catch (PDOException $PDOException) {
            echo "Falha na conexão: " . $PDOException->getMessage() . "<br/>";
            echo "Arquivo: " . $PDOException->getFile() . "<br/>";
        }
    }
}