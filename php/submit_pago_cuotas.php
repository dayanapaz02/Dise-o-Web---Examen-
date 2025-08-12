<?php

require_once('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar los datos
    $numero_contrato_identidad = $conn->real_escape_string(trim($_POST['numero_contrato_identidad']));
    $monto_pago = $conn->real_escape_string(trim($_POST['monto_pago']));
    $metodo_pago = $conn->real_escape_string(trim($_POST['metodo_pago']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    // Validaciones básicas
    if (empty($numero_contrato_identidad) || empty($monto_pago) || empty($metodo_pago) || empty($email)) {
        echo "<script>alert('Por favor complete todos los campos requeridos.'); window.location.href='../pago-cuotas.html';</script>";
        exit();
    }

    // Validar que el monto sea positivo
    if ($monto_pago <= 0) {
        echo "<script>alert('El monto del pago debe ser mayor a 0.'); window.location.href='../pago-cuotas.html';</script>";
        exit();
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO pagos_cuotas (numero_contrato_identidad, monto_pago, metodo_pago, email) 
            VALUES ('$numero_contrato_identidad', '$monto_pago', '$metodo_pago', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('¡Pago de cuotas procesado con éxito!');
                window.location.href='../credito-bytestore.html';
              </script>";
    } else {
        echo "<script>
                alert('Error al procesar el pago: " . $conn->error . "');
                window.location.href='../pago-cuotas.html';
              </script>";
    }
}

$conn->close();

?> 