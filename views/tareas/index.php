<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <style>
        :root { color-scheme: light; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #f5f7ff 0%, #eef6ff 100%);
            color: #1f2937;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 24px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.12);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 12px;
        }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            background: #e0f2fe;
            color: #075985;
        }
        .filters a {
            margin-right: 8px;
            text-decoration: none;
            color: #475569;
            font-weight: 600;
        }
        .filters a.active {
            color: #2563eb;
        }
        form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 18px; }
        input, textarea, button {
            padding: 12px;
            font-size: 15px;
            border: 1px solid #dbe3f0;
            border-radius: 10px;
        }
        button {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            border: none;
            cursor: pointer;
        }
        .alert {
            padding: 12px 14px;
            margin-bottom: 16px;
            border-radius: 10px;
        }
        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
        .tarea {
            border: 1px solid #e5e7eb;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 12px;
            background: #fafcff;
        }
        .tarea h3 { margin: 0 0 8px; }
        .tarea p { margin: 0 0 10px; color: #4b5563; }
        .completada { text-decoration: line-through; color: #9ca3af; }
        .actions a { margin-right: 10px; color: #2563eb; text-decoration: none; font-weight: 600; }
        .footer-links { margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h2>Panel de tareas</h2>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></p>
            </div>
            <a href="index.php?action=logout">Cerrar sesión</a>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="filters">
            <a href="index.php?action=home&filtro=all" class="<?php echo ($filtro ?? 'all') === 'all' ? 'active' : ''; ?>">Todas</a>
            <a href="index.php?action=home&filtro=pendientes" class="<?php echo ($filtro ?? 'all') === 'pendientes' ? 'active' : ''; ?>">Pendientes</a>
            <a href="index.php?action=home&filtro=completadas" class="<?php echo ($filtro ?? 'all') === 'completadas' ? 'active' : ''; ?>">Completadas</a>
        </div>

        <hr>

        <?php if (!empty($tareaEditar)): ?>
            <h3>Editar tarea</h3>
            <form method="post" action="index.php?action=actualizar">
                <input type="hidden" name="id" value="<?php echo (int) $tareaEditar['id']; ?>">
                <input type="text" name="titulo" placeholder="Título de la tarea" value="<?php echo htmlspecialchars($tareaEditar['titulo']); ?>" required>
                <textarea name="descripcion" placeholder="Descripción (opcional)"><?php echo htmlspecialchars($tareaEditar['descripcion'] ?? ''); ?></textarea>
                <button type="submit">Guardar cambios</button>
                <a href="index.php?action=home">Cancelar</a>
            </form>
        <?php else: ?>
            <h3>Nueva tarea</h3>
            <form method="post" action="index.php?action=crear">
                <input type="text" name="titulo" placeholder="Título de la tarea" required>
                <textarea name="descripcion" placeholder="Descripción (opcional)"></textarea>
                <button type="submit">Agregar tarea</button>
            </form>
        <?php endif; ?>

        <?php foreach ($tareas as $tarea): ?>
            <div class="tarea">
                <span class="badge"><?php echo (int) $tarea['completada'] === 1 ? 'Completada' : 'Pendiente'; ?></span>
                <h3 class="<?php echo (int) $tarea['completada'] === 1 ? 'completada' : ''; ?>"><?php echo htmlspecialchars($tarea['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($tarea['descripcion'] ?? ''); ?></p>
                <div class="actions">
                    <?php if ((int) $tarea['completada'] === 0): ?>
                        <a href="index.php?action=completar&id=<?php echo (int) $tarea['id']; ?>">Marcar como completada</a>
                    <?php else: ?>
                        <a href="index.php?action=pendiente&id=<?php echo (int) $tarea['id']; ?>">Marcar como pendiente</a>
                    <?php endif; ?>
                    <a href="index.php?action=editar&id=<?php echo (int) $tarea['id']; ?>">Editar</a>
                    <a href="index.php?action=eliminar&id=<?php echo (int) $tarea['id']; ?>">Eliminar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
