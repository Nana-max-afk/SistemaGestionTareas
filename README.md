# To-Do List con PHP Vanilla

Sistema de gestión de tareas desarrollado en PHP puro, siguiendo una arquitectura MVC simple y limpia, con POO, PDO y sesiones.

## Funcionalidades
- Registro e inicio de sesión de usuarios
- Creación de tareas
- Edición de tareas
- Cambio de estado: pendiente/completada
- Eliminación de tareas
- Filtros: todas, pendientes y completadas
- Seguridad con password_hash y password_verify

## Tecnologías
- PHP 8+
- MySQL
- PDO
- HTML/CSS

## Requisitos
- Servidor web con PHP
- MySQL
- Composer no es necesario

## Instalación
1. Crear la base de datos y ejecutar el script SQL de [database/schema.sql](database/schema.sql)
2. Ajustar las credenciales de conexión en [config/Database.php](config/Database.php)
3. Levantar el proyecto desde la raíz con Apache, Nginx o PHP built-in server

## Ejecución rápida
```bash
php -S 127.0.0.1:8000
```
Luego abrir:
```text
http://127.0.0.1:8000/index.php?action=login
```

## Estructura
```text
config/
controllers/
models/
views/
database/
index.php
```
