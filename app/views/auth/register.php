<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        input, button { width: 100%; padding: 10px; margin-bottom: 10px; }
        .alert { padding: 10px; margin-bottom: 10px; background: #f8d7da; }
        .success { background: #d4edda; }
    </style>
</head>
<body>
    <h2>Registro</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="index.php?controller=auth&action=handleRegister" method="post">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>

    <p><a href="index.php?controller=auth&action=login">Ya tengo cuenta</a></p>
</body>
</html>
