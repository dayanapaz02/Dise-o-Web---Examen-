<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('db_config.php');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar y limpiar los datos
        $nombre = $conn->real_escape_string(trim($_POST['nombre'] ?? ''));
        $email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
        $telefono = $conn->real_escape_string(trim($_POST['telefono'] ?? ''));
        $ingresos = $conn->real_escape_string(trim($_POST['ingresos'] ?? ''));
        $monto_solicitado = $conn->real_escape_string(trim($_POST['monto_solicitado'] ?? ''));

        // Validaciones básicas
        if (empty($nombre) || empty($email) || empty($monto_solicitado)) {
            throw new Exception('Por favor complete todos los campos requeridos.');
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Por favor ingrese un correo electrónico válido.');
        }

        // Validar monto solicitado
        if (!is_numeric($monto_solicitado) || $monto_solicitado <= 0) {
            throw new Exception('Por favor ingrese un monto válido.');
        }

        // Insertar en la base de datos
        $sql = "INSERT INTO pre_aprobaciones (nombre, email, telefono, ingresos, monto_solicitado) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("sssdd", $nombre, $email, $telefono, $ingresos, $monto_solicitado);
        
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
        }

        // Éxito
        echo "<script>
                alert('¡Solicitud de Pre-Aprobación enviada con éxito!');
                window.location.href='../credito-bytestore.html';
              </script>";
    }
} catch (Exception $e) {
    echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href='../pre-aprobacion.html';
          </script>";
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?> 