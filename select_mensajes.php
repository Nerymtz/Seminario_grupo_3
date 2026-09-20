<?php
session_start();

// Validación de sesión existente[cite: 1]
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Asignación de roles[cite: 1]
$tipo_usuario = "Usuario";
if ($_SESSION['tipo_id'] == 1) $tipo_usuario = "Administrador";
if ($_SESSION['tipo_id'] == 2) $tipo_usuario = "Supervisor";
if ($_SESSION['tipo_id'] == 3) $tipo_usuario = "Operador";

// Conexión a la base de datos (Ajusta estos valores)
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "tu_base_de_datos";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener los mensajes del id_recibe = 10
$sql = "SELECT * FROM bitacora WHERE id_recibe = 10 ORDER BY fecha DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes - Sistema de Bitácoras INSUCO</title>
    <style>
        /* Estilos base originales[cite: 1] */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background-color: #0f172a; color: #f8fafc; }
        
        .layout-wrapper { display: flex; width: 100%; min-height: 100vh; }
        .content-wrapper { flex-grow: 1; display: flex; flex-direction: column; width: calc(100% - 250px); }

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

        .main-content { padding: 2rem; }
        .page-header h1 { font-size: 1.8rem; margin-bottom: 1.5rem; color: #f8fafc; }
        .card { background-color: #1e293b; border-radius: 8px; padding: 2rem; border: 1px solid #334155; }
        
        /* Nuevos estilos para la tabla de registros */
        .table-responsive { overflow-x: auto; margin-top: 1.5rem; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #334155; }
        th { background-color: #0f172a; color: #94a3b8; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; }
        tbody tr:hover { background-color: #283548; transition: background-color 0.2s; }
        
        /* Estilos para los badges de status */
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-0 { background-color: #ca8a04; color: #fef08a; } /* Pendiente */
        .status-1 { background-color: #16a34a; color: #bbf7d0; } /* Resuelto/Leído */
    </style>
</head>
<body>

    <div class="layout-wrapper">
        
        <!-- SIDEBAR REQUERIDO -->
        <?php include 'sidebar.php'; ?>

        <div class="content-wrapper">
            
            <nav class="navbar">
                <div class="navbar-title">Sistema Institucional</div>
                <div class="user-info">
                    <div>
                        <!-- Uso de variables de sesión originales[cite: 1] -->
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></div>
                        <div class="user-role"><?php echo $tipo_usuario; ?></div>
                    </div>
                    <a href="logout.php" class="btn-logout">Salir</a>
                </div>
            </nav>

            <main class="main-content">
                <div class="page-header">
                    <h1>Mensajes Recibidos</h1>
                </div>

                <div class="card">
                    <h2>Bandeja de ID: 10</h2>
                    <p style="color: #94a3b8; margin-bottom: 1rem;">Mostrando todos los mensajes registrados en la bitácora.</p>
                    
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Formatear el status visualmente
                                        $statusText = $row['status'] == 0 ? "Pendiente" : "Completado";
                                        $statusClass = "status-" . $row['status'];
                                        
                                        echo "<tr>";
                                        echo "<td>#" . htmlspecialchars($row['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['mensaje']) . "</td>";
                                        echo "<td>" . date('d/m/Y H:i', strtotime($row['fecha'])) . "</td>";
                                        echo "<td><span class='badge {$statusClass}'>{$statusText}</span></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' style='text-align: center; color: #94a3b8; padding: 2rem;'>No hay mensajes registrados para este ID.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>
</html>
<?php
// Cerrar conexión
if(isset($conn)) $conn->close();
?>