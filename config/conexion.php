<?php
$host = 'localhost';
$usuario = 'root';
$contrasena = 'iscmexico2026';
$base_de_datos = 'seminario_db'; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

    $conexion->set_charset("utf8mb4");


} catch (mysqli_sql_exception $e) { 

    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>