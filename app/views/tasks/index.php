<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis tareas</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; }
        form { margin-bottom: 20px; }
        input, textarea, button { width: 100%; padding: 10px; margin-bottom: 10px; }
        .task { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; }
        .completed { text-decoration: line-through; color: #999; }
        .actions a { margin-right: 10px; }
        .alert { padding: 10px; margin-bottom: 10px; background: #d4edda; }
    </style>
</head>
<body>
    <h2>Gestión de tareas</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
    <p><a href="index.php?controller=auth&action=logout">Cerrar sesión</a></p>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="index.php?controller=task&action=store" method="post">
        <input type="text" name="title" placeholder="Título de la tarea" required>
        <textarea name="description" placeholder="Descripción (opcional)"></textarea>
        <button type="submit">Guardar tarea</button>
    </form>

    <?php foreach ($tasks as $task): ?>
        <div class="task">
            <h3 class="<?php echo $task['completed'] ? 'completed' : ''; ?>"><?php echo htmlspecialchars($task['title']); ?></h3>
            <p><?php echo htmlspecialchars($task['description'] ?? ''); ?></p>
            <div class="actions">
                <a href="index.php?controller=task&action=complete&id=<?php echo $task['id']; ?>"><?php echo $task['completed'] ? 'Marcar pendiente' : 'Marcar completada'; ?></a>
                <a href="index.php?controller=task&action=delete&id=<?php echo $task['id']; ?>">Eliminar</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
