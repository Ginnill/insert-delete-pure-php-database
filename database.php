<?php

use PDO;

class Connection
{
    private static $connection = null;

    public static function getConnection()
    {
        if (empty(self::$connection)) {
            self::$connection = new PDO(
                "mysql:host=localhost;dbname=loja;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }
        return self::$connection;
    }
}