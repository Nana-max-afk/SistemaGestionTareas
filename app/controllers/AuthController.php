<?php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(): void
    {
        session_start();
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
        session_start();
        include __DIR__ . '/../views/auth/register.php';
    }

    public function handleLogin(): void
    {
        session_start();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ];
            header('Location: index.php?controller=task&action=index');
            exit;
        }

        $_SESSION['error'] = 'Credenciales incorrectas.';
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    public function handleRegister(): void
    {
        session_start();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'El correo ya está registrado.';
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $_SESSION['success'] = 'Usuario registrado correctamente.';
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
