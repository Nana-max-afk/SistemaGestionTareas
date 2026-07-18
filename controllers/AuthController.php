<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function login(): void
    {
        $this->iniciarSesion();

        if (!empty($_SESSION['usuario_id'])) {
            header('Location: index.php?action=home');
            exit;
        }

        $this->render('auth/login.php');
    }

    public function register(): void
    {
        $this->iniciarSesion();
        $this->render('auth/register.php');
    }

    public function handleLogin(): void
    {
        $this->iniciarSesion();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['error'] = 'Correo y contraseña son obligatorios.';
            header('Location: index.php?action=login');
            exit;
        }

        if (!$this->emailValido($email)) {
            $_SESSION['error'] = 'Ingresa un correo electrónico válido.';
            header('Location: index.php?action=login');
            exit;
        }

        $usuario = $this->usuarioModel->verificarLogin($email, $password);

        if ($usuario !== null) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];

            header('Location: index.php?action=home');
            exit;
        }

        $_SESSION['error'] = 'Credenciales inválidas.';
        header('Location: index.php?action=login');
        exit;
    }

    public function handleRegister(): void
    {
        $this->iniciarSesion();

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nombre === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: index.php?action=register');
            exit;
        }

        if (!$this->emailValido($email)) {
            $_SESSION['error'] = 'Ingresa un correo electrónico válido.';
            header('Location: index.php?action=register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
            header('Location: index.php?action=register');
            exit;
        }

        $registrado = $this->usuarioModel->registrar($nombre, $email, $password);

        if ($registrado) {
            $_SESSION['success'] = 'Usuario registrado correctamente.';
            header('Location: index.php?action=login');
            exit;
        }

        $_SESSION['error'] = 'El correo ya está registrado.';
        header('Location: index.php?action=register');
        exit;
    }

    public function logout(): void
    {
        $this->iniciarSesion();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    private function iniciarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function emailValido(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function render(string $view): void
    {
        include __DIR__ . '/../views/' . $view;
    }
}
