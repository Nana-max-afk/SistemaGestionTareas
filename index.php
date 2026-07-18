<?php

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/TareaController.php';

$action = $_GET['action'] ?? 'login';

if ($action === 'home' || $action === 'crear' || $action === 'editar' || $action === 'actualizar' || $action === 'completar' || $action === 'pendiente' || $action === 'eliminar') {
    $controller = new TareaController();
    $controller->$action();
    exit;
}

if ($action === 'login' || $action === 'register' || $action === 'handleLogin' || $action === 'handleRegister' || $action === 'logout') {
    $controller = new AuthController();
    $controller->$action();
    exit;
}

$controller = new AuthController();
$controller->login();
