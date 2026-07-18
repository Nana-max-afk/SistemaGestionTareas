<?php

require_once __DIR__ . '/../models/Tarea.php';

class TareaController
{
    private Tarea $tareaModel;

    public function __construct()
    {
        $this->tareaModel = new Tarea();
    }

    public function home(): void
    {
        $this->requireAuth();
        $filtro = $this->obtenerFiltro();
        $tareas = $this->tareaModel->listarPorUsuario($this->usuarioId(), $filtro);
        $tareaEditar = null;
        $this->render('tareas/index.php', compact('tareas', 'filtro', 'tareaEditar'));
    }

    public function editar(): void
    {
        $this->requireAuth();
        $filtro = $this->obtenerFiltro();
        $tareas = $this->tareaModel->listarPorUsuario($this->usuarioId(), $filtro);
        $tareaId = (int) ($_GET['id'] ?? 0);
        $tareaEditar = $this->tareaModel->obtenerPorId($tareaId, $this->usuarioId());

        if ($tareaEditar === null) {
            $_SESSION['error'] = 'No se encontró la tarea que quieres editar.';
            header('Location: index.php?action=home');
            exit;
        }

        $this->render('tareas/index.php', compact('tareas', 'filtro', 'tareaEditar'));
    }

    public function crear(): void
    {
        $this->requireAuth();

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '') {
            $_SESSION['error'] = 'El título de la tarea es obligatorio.';
            header('Location: index.php?action=home');
            exit;
        }

        if (strlen($titulo) > 150) {
            $_SESSION['error'] = 'El título no puede superar los 150 caracteres.';
            header('Location: index.php?action=home');
            exit;
        }

        $this->tareaModel->crear($this->usuarioId(), $titulo, $descripcion !== '' ? $descripcion : null);
        $_SESSION['success'] = 'Tarea creada correctamente.';
        header('Location: index.php?action=home');
        exit;
    }

    public function actualizar(): void
    {
        $this->requireAuth();

        $tareaId = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($tareaId <= 0 || $titulo === '') {
            $_SESSION['error'] = 'No se pudo actualizar la tarea.';
            header('Location: index.php?action=home');
            exit;
        }

        if (strlen($titulo) > 150) {
            $_SESSION['error'] = 'El título no puede superar los 150 caracteres.';
            header('Location: index.php?action=home');
            exit;
        }

        $this->tareaModel->actualizar($tareaId, $this->usuarioId(), $titulo, $descripcion !== '' ? $descripcion : null);
        $_SESSION['success'] = 'Tarea actualizada correctamente.';
        header('Location: index.php?action=home');
        exit;
    }

    public function completar(): void
    {
        $this->requireAuth();

        $tareaId = (int) ($_GET['id'] ?? 0);
        if ($tareaId > 0) {
            $this->tareaModel->actualizarEstado($tareaId, $this->usuarioId(), true);
            $_SESSION['success'] = 'Tarea marcada como completada.';
        }

        header('Location: index.php?action=home');
        exit;
    }

    public function pendiente(): void
    {
        $this->requireAuth();

        $tareaId = (int) ($_GET['id'] ?? 0);
        if ($tareaId > 0) {
            $this->tareaModel->actualizarEstado($tareaId, $this->usuarioId(), false);
            $_SESSION['success'] = 'Tarea marcada como pendiente.';
        }

        header('Location: index.php?action=home');
        exit;
    }

    public function eliminar(): void
    {
        $this->requireAuth();

        $tareaId = (int) ($_GET['id'] ?? 0);
        if ($tareaId > 0) {
            $this->tareaModel->eliminar($tareaId, $this->usuarioId());
            $_SESSION['success'] = 'Tarea eliminada.';
        }

        header('Location: index.php?action=home');
        exit;
    }

    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    private function usuarioId(): int
    {
        return (int) ($_SESSION['usuario_id'] ?? 0);
    }

    private function obtenerFiltro(): string
    {
        $filtro = $_GET['filtro'] ?? 'all';
        return in_array($filtro, ['all', 'pendientes', 'completadas'], true) ? $filtro : 'all';
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $view;
    }
}
