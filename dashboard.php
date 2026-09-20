<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

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
        body { background-color: #0f172a; color: #f8fafc; }
        
        /* Contenedor Flex para separar el Sidebar del Contenido */
        .layout-wrapper { display: flex; width: 100%; min-height: 100vh; }
        
        /* Contenedor derecho (Barra superior y contenido) */
        .content-wrapper { flex-grow: 1; display: flex; flex-direction: column; width: calc(100% - 250px); }

        /* Barra Superior */
        .navbar { 
            background-color: #1e293b; 
            padding: 1rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 4px solid #dc2626; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3); 
        }
        .navbar-title { font-size: 1.2rem; font-weight: 600; color: #e2e8f0; }
        
        /* Opciones del usuario en la barra superior */
        .user-info { display: flex; align-items: center; gap: 20px; text-align: right; }
        .user-name { font-weight: 600; font-size: 1rem; }
        .user-role { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        
        .btn-logout { 
            background-color: transparent; 
            color: #ef4444; 
            border: 1px solid #ef4444; 
            padding: 0.4rem 1rem; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.2s; 
        }
        .btn-logout:hover { background-color: #ef4444; color: white; }

        /* Área de trabajo */
        .main-content { padding: 2rem; }
        .page-header h1 { font-size: 1.8rem; margin-bottom: 1.5rem; color: #f8fafc; }
        .card { background-color: #1e293b; border-radius: 8px; padding: 2rem; border: 1px solid #334155; }
    </style>
</head>
<body>

    <div class="layout-wrapper">
        
        <!-- INCLUIMOS EL SIDEBAR AQUÍ -->
        <?php// include 'sidebar.php'; ?>

        <!-- CONTENIDO DERECHO -->
        <div class="content-wrapper">
            
            <nav class="navbar">
                <div class="navbar-title">Sistema Institucional</div>
                <div class="user-info">
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></div>
                        <div class="user-role"><?php echo $tipo_usuario; ?></div>
                    </div>
                    <a href="logout.php" class="btn-logout">Salir</a>
                </div>
            </nav>

            <main class="main-content">
                <div class="page-header">
                    <h1>Panel de Control</h1>
                </div>

                <div class="card">
                    <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>!</h2>
                    <br>
                    <p style="color: #94a3b8;">Navega usando el menú lateral para acceder a las herramientas de <strong><?php echo strtolower($tipo_usuario); ?></strong>.</p>
                </div>
            </main>

        </div>
    </div>

</body>
</html>