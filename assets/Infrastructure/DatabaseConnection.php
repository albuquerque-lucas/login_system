<?php

namespace LucasAlbuquerque\LoginSystem\Infrastructure;

use PDO;
use PDOException;

class DatabaseConnection
{
    public static function connect(string $hostName, string $dbName, string $userName, string $password)
    {
        try {
            return new PDO("mysql:host=$hostName;dbname=$dbName", $userName, $password);
        } catch (PDOException $PDOException) {
            echo "Falha na conexão: " . $PDOException->getMessage() . "<br/>";
            echo "Arquivo: " . $PDOException->getFile() . "<br/>";
        }
    }
}