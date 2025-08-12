<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$servername = "localhost";
$username = "root";     // Usuario por defecto de XAMPP
$password = "";         // Contraseña vacía por defecto de XAMPP
$dbname = "bytestore_db";

try {
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Establecer charset a utf8
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error al establecer el charset: " . $conn->error);
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?> 