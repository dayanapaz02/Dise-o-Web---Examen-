<?php

require_once('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar los datos
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $identidad = $conn->real_escape_string(trim($_POST['identidad']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $telefono = $conn->real_escape_string(trim($_POST['telefono']));
    $ingresos = $conn->real_escape_string(trim($_POST['ingresos']));

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($identidad)) {
        echo "<script>alert('Por favor complete todos los campos requeridos.'); window.location.href='../pre-aprobacion.html';</script>";
        exit();
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO solicitudes_pre_aprobacion (nombre, identidad, email, telefono, ingresos) 
            VALUES ('$nombre', '$identidad', '$email', '$telefono', '$ingresos')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('¡Solicitud de Pre-Aprobación enviada con éxito!');
                window.location.href='../credito-bytestore.html';
              </script>";
    } else {
        echo "<script>
                alert('Error al enviar la solicitud: " . $conn->error . "');
                window.location.href='../pre-aprobacion.html';
              </script>";
    }
}

$conn->close();

?> 