<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('db_config.php');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar y limpiar los datos
        $numero_cuenta = $conn->real_escape_string(trim($_POST['numero_cuenta'] ?? ''));
        $monto_pago = $conn->real_escape_string(trim($_POST['monto_pago'] ?? ''));
        $fecha_pago = $conn->real_escape_string(trim($_POST['fecha_pago'] ?? ''));
        $metodo_pago = $conn->real_escape_string(trim($_POST['metodo_pago'] ?? ''));

        // Validaciones básicas
        if (empty($numero_cuenta) || empty($monto_pago) || empty($fecha_pago) || empty($metodo_pago)) {
            throw new Exception('Por favor complete todos los campos requeridos.');
        }

        // Validar número de cuenta (debe ser numérico)
        if (!is_numeric($numero_cuenta)) {
            throw new Exception('El número de cuenta debe ser numérico.');
        }

        // Validar monto de pago
        if (!is_numeric($monto_pago) || $monto_pago <= 0) {
            throw new Exception('Por favor ingrese un monto válido.');
        }

        // Validar fecha de pago
        $fecha_actual = new DateTime();
        $fecha_pago_obj = new DateTime($fecha_pago);
        if ($fecha_pago_obj < $fecha_actual) {
            throw new Exception('La fecha de pago no puede ser anterior a la fecha actual.');
        }

        // Insertar en la base de datos
        $sql = "INSERT INTO pagos_cuotas (numero_cuenta, monto_pago, fecha_pago, metodo_pago) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("sdss", $numero_cuenta, $monto_pago, $fecha_pago, $metodo_pago);
        
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
        }

        // Éxito
        echo "<script>
                alert('¡Pago registrado con éxito!');
                window.location.href='../credito-bytestore.html';
              </script>";
    }
} catch (Exception $e) {
    echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href='../pago-cuotas.html';
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