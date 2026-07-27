<?php

namespace LucasAlbuquerque\LoginSystem\Infrastructure;

use PDO;
use PDOException;
use RuntimeException;

class DatabaseConnection
{
    public static function connect()
    {
        $configPath = dirname(__DIR__, 2) . '/config/database.php';

        if (!file_exists($configPath)) {
            throw new RuntimeException(
                'Database configuration not found. Copy config/database.example.php to config/database.php.'
            );
        }

        $config = require $configPath;

        $hostName = $config['host'];
        $dbName = $config['database'];
        $userName = $config['username'];
        $password = $config['password'];

        try {
            return new PDO("mysql:host=$hostName;dbname=$dbName", $userName, $password);
        } catch (PDOException $PDOException) {
            echo 'Database connection failed.';
        }
    }
}
