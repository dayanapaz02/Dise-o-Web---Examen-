<?php
// Iniciar sesión (aunque no se use directamente para el registro, es buena práctica si hay dependencias)
session_start();

// Incluir el archivo de configuración de la base de datos
require_once 'db_config.php';

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recoger y sanear los datos del formulario
    $full_name = htmlspecialchars(trim($_POST['nombre'])); // Usar 'nombre' según tu HTML
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // 2. Validaciones básicas
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        // Redirigir con mensaje de error si faltan campos
        header("Location: ../registro.html?error=campos_vacios");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../registro.html?error=email_invalido");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: ../registro.html?error=contrasenas_no_coinciden");
        exit();
    }

    // 3. Hashear la contraseña de forma segura
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 4. Verificar si el correo ya existe
    $check_email_sql = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($check_email_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $conn->close();
            header("Location: ../registro.html?error=email_ya_registrado");
            exit();
        }
        $stmt->close();
    } else {
        // Error al preparar la consulta de verificación de email
        error_log("Error al preparar check_email_sql: " . $conn->error);
        header("Location: ../registro.html?error=db_error_check_email");
        exit();
    }


    // 5. Preparar la sentencia SQL para insertar el nuevo usuario
    // Esta es la línea crucial donde suele fallar si la tabla o columnas no existen/son incorrectas
    $insert_sql = "INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)";

    // 6. Intentar preparar la sentencia
    if ($stmt = $conn->prepare($insert_sql)) {
        // Línea 39 (aproximadamente): Ahora $stmt es un objeto válido, se puede llamar a bind_param
        $stmt->bind_param("sss", $full_name, $email, $password_hash); // "sss" porque son 3 strings

        // 7. Ejecutar la sentencia
        if ($stmt->execute()) {
            // Registro exitoso
            $stmt->close();
            $conn->close();
            header("Location: ../login.html?registration=success"); // Redirigir a la página de login
            exit();
        } else {
            // Error al ejecutar la sentencia
            error_log("Error al ejecutar INSERT: " . $stmt->error);
            header("Location: ../registro.html?error=db_execution_failed");
            exit();
        }
    } else {
        // *** ESTE ES EL PUNTO CLAVE PARA TU ERROR ***
        // Si prepare() falla, significa que hay un problema con la consulta SQL o la tabla.
        // Registra el error para diagnóstico.
        error_log("Error al preparar INSERT SQL: " . $conn->error);
        header("Location: ../registro.html?error=db_prepare_failed");
        exit();
    }

} else {
    // Si se accede directamente sin POST, redirigir al formulario de registro
    header("Location: ../registro.html");
    exit();
}
?> 