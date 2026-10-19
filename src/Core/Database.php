<?php

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()   {}

    public static function getConnection(): PDO
    {
        if(self::$connection === null) {
            $config = require __DIR__ . '/../Config/config.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        

        try {
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
    ]);
            } 
        catch(PDOException $e) {
                throw new RuntimeException('Database connection failed');
            }
        }
        return self::$connection;
    }
}