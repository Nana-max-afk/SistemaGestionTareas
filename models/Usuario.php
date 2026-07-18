<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function registrar(string $nombre, string $email, string $password): bool
    {
        if ($this->buscarPorEmail($email) !== null) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nombre, email, password, creado_en) VALUES (:nombre, :email, :password, NOW())'
        );

        return $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $passwordHash,
        ]);
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    public function verificarLogin(string $email, string $password): ?array
    {
        $usuario = $this->buscarPorEmail($email);

        if ($usuario === null) {
            return null;
        }

        if (!password_verify($password, $usuario['password'])) {
            return null;
        }

        return $usuario;
    }
}
