<?php
namespace App\Database;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = include(__DIR__ . '/../Config/config.php');
            $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
            try {
                self::$connection = new PDO($dsn, $config['db_user'], $config['db_pass']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database error: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
