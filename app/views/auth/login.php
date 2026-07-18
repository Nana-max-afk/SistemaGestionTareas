<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        input, button { width: 100%; padding: 10px; margin-bottom: 10px; }
        .alert { padding: 10px; margin-bottom: 10px; background: #f8d7da; }
        .success { background: #d4edda; }
    </style>
</head>
<body>
    <h2>Iniciar sesión</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="index.php?controller=auth&action=handleLogin" method="post">
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>

    <p><a href="index.php?controller=auth&action=register">Crear una cuenta</a></p>
</body>
</html>
