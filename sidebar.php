<!-- Estilos exclusivos del sidebar (se aplicarán automáticamente donde lo incluyas) -->
<style>
    .sidebar { 
        width: 250px; 
        background-color: #1e293b; 
        border-right: 1px solid #334155; 
        min-height: 100vh; 
        display: flex; 
        flex-direction: column; 
    }
    .sidebar-header { 
        padding: 1.5rem; 
        text-align: center; 
        border-bottom: 1px solid #334155; 
    }
    .sidebar-header img { 
        width: 100%; 
        max-width: 180px; 
        height: auto; 
        object-fit: contain;
    }
    .sidebar-menu { 
        list-style: none; 
        padding: 0; 
        margin-top: 1rem; 
        flex-grow: 1; 
    }
    .sidebar-menu li { margin-bottom: 0.2rem; }
    .sidebar-menu a { 
        display: block; 
        padding: 0.8rem 1.5rem; 
        color: #94a3b8; 
        text-decoration: none; 
        font-size: 0.95rem; 
        font-weight: 500; 
        transition: all 0.2s; 
        border-left: 3px solid transparent; 
    }
    .sidebar-menu a:hover { 
        background-color: #0f172a; 
        color: #f8fafc; 
        border-left-color: #dc2626; /* Línea roja de INSUCO al pasar el mouse */
    }
    .menu-label { 
        padding: 1.2rem 1.5rem 0.5rem; 
        font-size: 0.75rem; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        font-weight: 600; 
    }
</style>

<aside class="sidebar">
    <div class="sidebar-header">
        <!-- Reutilizamos tu logo de INSUCO -->
        <img src="./logo.png" alt="Logo INSUCO">
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-label">Principal</li>
        <li><a href="dashboard.php">Inicio</a></li>
        
        <?php if ($_SESSION['tipo_id'] == 1): // Menú exclusivo para Administrador ?>
            <li class="menu-label">Administración</li>
            <li><a href="#">Gestión de Usuarios</a></li>
            <li><a href="#">Roles y Permisos</a></li>
            <li><a href="#">Todas las Bitácoras</a></li>
            <li><a href="#">Configuración Global</a></li>
        <?php endif; ?>
        
        <?php if ($_SESSION['tipo_id'] == 2): // Menú exclusivo para Supervisor ?>
            <li class="menu-label">Supervisión</li>
            <li><a href="#">Revisar Bitácoras</a></li>
            <li><a href="#">Aprobar Entradas</a></li>
            <li><a href="#">Reportes de Alumnos</a></li>
        <?php endif; ?>
        
        <?php if ($_SESSION['tipo_id'] == 3): // Menú exclusivo para Operador ?>
            <li class="menu-label">Mis Bitácoras</li>
            <li><a href="#">Crear Nueva Entrada</a></li>
            <li><a href="#">Historial de Entradas</a></li>
        <?php endif; ?>
    </ul>
</aside>