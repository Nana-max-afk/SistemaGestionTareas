<?php

require_once __DIR__ . '/../models/TaskModel.php';

class TaskController
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index(): void
    {
        $this->ensureAuthenticated();
        $tasks = $this->taskModel->getByUserId($_SESSION['user']['id']);
        include __DIR__ . '/../views/tasks/index.php';
    }

    public function create(): void
    {
        $this->ensureAuthenticated();
        include __DIR__ . '/../views/tasks/index.php';
    }

    public function store(): void
    {
        $this->ensureAuthenticated();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($title === '') {
            $_SESSION['error'] = 'El título de la tarea es obligatorio.';
            header('Location: index.php?controller=task&action=index');
            exit;
        }

        $this->taskModel->create((int) $_SESSION['user']['id'], $title, $description !== '' ? $description : null);
        $_SESSION['success'] = 'Tarea creada correctamente.';
        header('Location: index.php?controller=task&action=index');
        exit;
    }

    public function update(): void
    {
        $this->ensureAuthenticated();

        $taskId = (int) ($_POST['task_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($taskId <= 0 || $title === '') {
            $_SESSION['error'] = 'No se pudo actualizar la tarea.';
            header('Location: index.php?controller=task&action=index');
            exit;
        }

        $this->taskModel->update($taskId, (int) $_SESSION['user']['id'], $title, $description !== '' ? $description : null);
        $_SESSION['success'] = 'Tarea actualizada.';
        header('Location: index.php?controller=task&action=index');
        exit;
    }

    public function complete(): void
    {
        $this->ensureAuthenticated();

        $taskId = (int) ($_GET['id'] ?? 0);
        if ($taskId > 0) {
            $this->taskModel->toggleCompleted($taskId, (int) $_SESSION['user']['id']);
            $_SESSION['success'] = 'Estado de la tarea actualizado.';
        }

        header('Location: index.php?controller=task&action=index');
        exit;
    }

    public function delete(): void
    {
        $this->ensureAuthenticated();

        $taskId = (int) ($_GET['id'] ?? 0);
        if ($taskId > 0) {
            $this->taskModel->delete($taskId, (int) $_SESSION['user']['id']);
            $_SESSION['success'] = 'Tarea eliminada.';
        }

        header('Location: index.php?controller=task&action=index');
        exit;
    }

    private function ensureAuthenticated(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }
}
