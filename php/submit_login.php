<?php
session_start(); // Inicia la sesión al principio del archivo

// ... tu lógica de conexión a la base de datos y verificación de credenciales ...

if (/* credenciales son correctas */) {
    // Guarda el estado de la sesión y el nombre de usuario
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $nombre_del_usuario_obtenido_de_db; // Asume que obtienes el nombre del usuario
    $_SESSION['user_id'] = $id_del_usuario_obtenido_de_db; // Guarda el ID del usuario si lo usas

    header("Location: ../index.html"); // Redirige a la página de inicio o a donde quieras después del login
    exit();
} else {
    // ... manejar error de login, quizás con un mensaje en la sesión y redirigir de vuelta al login
    $_SESSION['login_error'] = "Credenciales incorrectas.";
    header("Location: ../login.html");
    exit();
}
?> 