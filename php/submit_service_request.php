<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar y limpiar los datos del formulario
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $device_type = htmlspecialchars(trim($_POST['device_type']));
    $issue_description = htmlspecialchars(trim($_POST['issue_description']));

    // Tu dirección de correo a la que se enviará la solicitud
    $to = "dayanamichellepazchavez@gmail.com";
    $subject = "Nueva Consulta de Servicio Técnico de Suministros Dayana";

    // Contenido del correo
    $email_content = "Nombre: $name\n";
    $email_content .= "Correo Electrónico: $email\n";
    $email_content .= "Teléfono: " . ($phone ? $phone : "No especificado") . "\n";
    $email_content .= "Tipo de Dispositivo: " . ($device_type ? $device_type : "No especificado") . "\n\n";
    $email_content .= "Descripción del Problema:\n$issue_description\n";

    // Cabeceras del correo
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Intentar enviar el correo
    if (mail($to, $subject, $email_content, $headers)) {
        // Redirigir a una página de éxito o mostrar un mensaje
        header("Location: ../servicio-tecnico.html?status=success");
        exit();
    } else {
        // Redirigir a una página de error o mostrar un mensaje
        header("Location: ../servicio-tecnico.html?status=error");
        exit();
    }
} else {
    // Si se intenta acceder directamente al script sin enviar el formulario
    header("Location: ../servicio-tecnico.html");
    exit();
}
?> 