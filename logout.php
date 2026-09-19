<?php
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión por completo
session_destroy();

// Redirigir al login
header("Location: index.php");
exit();
?>