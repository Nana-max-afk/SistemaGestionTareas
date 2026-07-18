<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
            color: #fff;
        }
        .card {
            width: min(92vw, 420px);
            background: rgba(255,255,255,0.95);
            color: #111827;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        form { display: flex; flex-direction: column; gap: 10px; }
        input, button { padding: 12px; font-size: 15px; border-radius: 10px; border: 1px solid #dbe3f0; }
        button { background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white; border: none; cursor: pointer; }
        .alert { padding: 10px 12px; margin-bottom: 12px; border-radius: 10px; }
        .error { background: #fee2e2; color: #991b1b; }
        a { color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Crear cuenta</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="post" action="index.php?action=handleRegister">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión</a></p>
    </div>
</body>
</html>
