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

            try {
                $serverDsn = "mysql:host={$host};charset={$charset}";
                $serverConnection = new PDO($serverDsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

                $serverConnection->exec("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $serverConnection->exec(
                    "CREATE TABLE IF NOT EXISTS {$database}.usuarios (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        nombre VARCHAR(100) NOT NULL,
                        email VARCHAR(255) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB"
                );
                $serverConnection->exec(
                    "CREATE TABLE IF NOT EXISTS {$database}.tareas (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        usuario_id INT UNSIGNED NOT NULL,
                        titulo VARCHAR(150) NOT NULL,
                        descripcion TEXT NULL,
                        completada TINYINT(1) DEFAULT 0,
                        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        CONSTRAINT fk_tareas_usuario
                            FOREIGN KEY (usuario_id) REFERENCES {$database}.usuarios(id)
                            ON DELETE CASCADE
                            ON UPDATE CASCADE
                    ) ENGINE=InnoDB"
                );

                self::$connection = new PDO("mysql:host={$host};dbname={$database};charset={$charset}", $username, $password, [
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
