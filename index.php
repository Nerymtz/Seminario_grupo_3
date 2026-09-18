<?php
// Iniciar sesión para mantener al usuario conectado si el login es exitoso
session_start();

// Cargar la configuración de la base de datos
require_once 'config/conexion.php';

$error = "";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $turnstile_response = $_POST['cf-turnstile-response'] ?? '';

    // 1. Validar el Captcha con Cloudflare Turnstile
    $secret = '0x4AAAAAAE8XQZnSqayE31bzzX6EnaRbEk4';
    $verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    
    $data = [
        'secret' => $secret,
        'response' => $turnstile_response
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($verify_url, false, $context);
    $captcha_success = json_decode($result);

    if ($captcha_success && $captcha_success->success) {
        // 2. Validar Usuario en la Base de Datos MariaDB
        $stmt = $conexion->prepare("SELECT id, usuario, password, tipo_id FROM usuarios WHERE usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            if (password_verify($password, $fila['password'])) {
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['nombre_usuario'] = $fila['usuario'];
                $_SESSION['tipo_id'] = $fila['tipo_id'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario no existe.";
        }
        $stmt->close();
    } else {
        $error = "Por favor, completa el Captcha de seguridad.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSUCO - Sistema de Bitácoras</title>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        
        body { 
            background-color: #0f172a; 
            color: #f8fafc; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
        }
        
        .login-card { 
            background-color: #1e293b; 
            padding: 3rem 2.5rem; 
            border-radius: 12px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); 
            width: 100%; 
            max-width: 420px; 
            text-align: center; 
            border: 1px solid #334155;
            border-top: 5px solid #dc2626; /* Acento rojo INSUCO en la parte superior */
        }
        
        /* Ajuste clave para el logo horizontal de INSUCO */
        .logo { 
            width: 100%; 
            max-width: 220px; /* Más ancho para que se lea bien el texto */
            height: auto; 
            margin-bottom: 0.5rem; 
            object-fit: contain; /* Evita que se deforme */
            border-radius: 0; /* Quitamos el borde redondo que lo cortaba */
        }
        
        .institucion-nombre {
            font-size: 0.85rem;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        h1 { 
            font-size: 1.6rem; 
            font-weight: 600; 
            margin-bottom: 2.5rem; 
            color: #e2e8f0; 
        }
        
        .input-group { text-align: left; margin-bottom: 1.5rem; }
        .input-group label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: #94a3b8; font-weight: 500; }
        
        .input-group input { 
            width: 100%; 
            padding: 0.8rem 1rem; 
            border-radius: 8px; 
            border: 1px solid #334155; 
            background-color: #0f172a; 
            color: #f8fafc; 
            font-size: 1rem; 
            transition: all 0.3s ease; 
        }
        
        /* Borde rojo al seleccionar el input */
        .input-group input:focus { 
            outline: none; 
            border-color: #dc2626; 
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.25); 
        }
        
        /* Botón con el color rojo de INSUCO */
        .btn-submit { 
            width: 100%; 
            padding: 0.85rem; 
            margin-top: 1rem; 
            background-color: #dc2626; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 1.05rem; 
            font-weight: 600; 
            cursor: pointer; 
            transition: background-color 0.2s ease; 
        }
        
        .btn-submit:hover { background-color: #b91c1c; }
        
        .captcha-container { margin: 1.5rem 0; display: flex; justify-content: center; }
        
        .error-mensaje { 
            background-color: rgba(239, 68, 68, 0.1); 
            color: #ef4444; 
            padding: 0.75rem; 
            border-radius: 8px; 
            margin-bottom: 1.5rem; 
            border: 1px solid rgba(239, 68, 68, 0.2); 
            font-size: 0.9rem; 
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo con el nuevo CSS aplicado -->
        <img src="./logo_insuco.png" alt="Logo INSUCO" class="logo">
        
        <div class="institucion-nombre">Plataforma Institucional</div>
        <h1>Sistema de Bitácoras</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error-mensaje"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="input-group">
                <label for="usuario">Usuario o Correo</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required autocomplete="username">
            </div>
            
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>
            
            <div class="captcha-container">
                <div class="cf-turnstile" data-sitekey="0x4AAAAAAE8XQQyVmubKmRaz" data-theme="dark"></div>
            </div>
            
            <button type="submit" class="btn-submit">Entrar</button>
        </form>
    </div>

</body>
</html>