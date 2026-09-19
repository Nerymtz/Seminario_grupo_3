<?php
// Iniciar la sesión para poder leer los datos del usuario
session_start();

// Validar si el usuario realmente inició sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión activa, lo regresamos al login
    header("Location: index.php");
    exit();
}

// Asignar un nombre legible al tipo de usuario para mostrarlo en pantalla
$tipo_usuario = "Usuario";
if ($_SESSION['tipo_id'] == 1) $tipo_usuario = "Administrador";
if ($_SESSION['tipo_id'] == 2) $tipo_usuario = "Supervisor";
if ($_SESSION['tipo_id'] == 3) $tipo_usuario = "Operador";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Bitácoras INSUCO</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        
        body { 
            background-color: #0f172a; 
            color: #f8fafc; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Barra de navegación superior */
        .navbar {
            background-color: #1e293b;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #dc2626; /* Acento rojo INSUCO */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #e2e8f0;
            letter-spacing: 0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .user-role {
            font-size: 0.8rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Botón de salir rojo oscuro */
        .btn-logout {
            background-color: #1e293b;
            color: #ef4444;
            border: 1px solid #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background-color: #ef4444;
            color: white;
        }

        /* Contenedor principal */
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            flex-grow: 1;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: #f8fafc;
        }

        /* Tarjeta de bienvenida / contenido */
        .card {
            background-color: #1e293b;
            border-radius: 8px;
            padding: 2rem;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <!-- Barra Superior -->
    <nav class="navbar">
        <div class="navbar-brand">
            <!-- Asumiendo que el logo está en la misma carpeta -->
            <img src="./logo.png" alt="Logo INSUCO">
            <span class="navbar-title">Sistema de Bitácoras</span>
        </div>
        
        <div class="user-info">
            <div class="user-details">
                <!-- Imprimimos el nombre de la sesión de forma segura -->
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></div>
                <div class="user-role"><?php echo $tipo_usuario; ?></div>
            </div>
            <!-- Enlace para cerrar sesión -->
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="main-content">
        <div class="page-header">
            <h1>Panel de Control</h1>
        </div>

        <div class="card">
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</h2>
            <br>
            <p style="color: #94a3b8;">Has iniciado sesión con el rol de <strong><?php echo $tipo_usuario; ?></strong>.</p>
            <br>
            <p style="color: #94a3b8;">Próximamente aquí aparecerán las tablas y formularios para gestionar las bitácoras.</p>
        </div>
    </main>

</body>
</html>