<?php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = '127.0.0.1';
            $database = 'todo_list';
            $username = 'root';
            $password = '';
            $charset = 'utf8mb4';
            $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";

            try {
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $exception) {
                die('No se pudo conectar a la base de datos: ' . $exception->getMessage());
            }
        }

        return self::$connection;
    }
}
