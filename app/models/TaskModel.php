<?php

require_once __DIR__ . '/../config/database.php';

class TaskModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE user_id = :user_id ORDER BY completed ASC, created_at DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, string $title, ?string $description): bool
    {
        $stmt = $this->db->prepare('INSERT INTO tasks (user_id, title, description) VALUES (:user_id, :title, :description)');
        return $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function update(int $taskId, int $userId, string $title, ?string $description): bool
    {
        $stmt = $this->db->prepare('UPDATE tasks SET title = :title, description = :description WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'id' => $taskId,
            'user_id' => $userId,
        ]);
    }

    public function toggleCompleted(int $taskId, int $userId): bool
    {
        $stmt = $this->db->prepare('UPDATE tasks SET completed = 1 - completed WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([
            'id' => $taskId,
            'user_id' => $userId,
        ]);
    }

    public function delete(int $taskId, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([
            'id' => $taskId,
            'user_id' => $userId,
        ]);
    }
}
