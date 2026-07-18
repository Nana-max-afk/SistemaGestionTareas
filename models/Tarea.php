<?php

require_once __DIR__ . '/../config/Database.php';

class Tarea
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function crear(int $usuarioId, string $titulo, ?string $descripcion): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tareas (usuario_id, titulo, descripcion, completada, creado_en) VALUES (:usuario_id, :titulo, :descripcion, 0, NOW())'
        );

        return $stmt->execute([
            'usuario_id' => $usuarioId,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
        ]);
    }

    public function listarPorUsuario(int $usuarioId, string $filtro = 'all'): array
    {
        $query = 'SELECT * FROM tareas WHERE usuario_id = :usuario_id';

        if ($filtro === 'pendientes') {
            $query .= ' AND completada = 0';
        } elseif ($filtro === 'completadas') {
            $query .= ' AND completada = 1';
        }

        $query .= ' ORDER BY creado_en DESC, id DESC';

        $stmt = $this->db->prepare($query);
        $stmt->execute(['usuario_id' => $usuarioId]);

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $tareaId, int $usuarioId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM tareas WHERE id = :id AND usuario_id = :usuario_id LIMIT 1'
        );
        $stmt->execute([
            'id' => $tareaId,
            'usuario_id' => $usuarioId,
        ]);

        $tarea = $stmt->fetch();
        return $tarea ?: null;
    }

    public function actualizar(int $tareaId, int $usuarioId, string $titulo, ?string $descripcion): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id AND usuario_id = :usuario_id'
        );

        return $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'id' => $tareaId,
            'usuario_id' => $usuarioId,
        ]);
    }

    public function actualizarEstado(int $tareaId, int $usuarioId, bool $completada): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE tareas SET completada = :completada WHERE id = :id AND usuario_id = :usuario_id'
        );

        return $stmt->execute([
            'completada' => $completada ? 1 : 0,
            'id' => $tareaId,
            'usuario_id' => $usuarioId,
        ]);
    }

    public function eliminar(int $tareaId, int $usuarioId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM tareas WHERE id = :id AND usuario_id = :usuario_id'
        );

        return $stmt->execute([
            'id' => $tareaId,
            'usuario_id' => $usuarioId,
        ]);
    }
}
