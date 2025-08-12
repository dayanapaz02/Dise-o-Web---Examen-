<?php
session_start(); // Asegúrate de iniciar la sesión en cada página que la use

$welcome_message = "";
$is_logged_in = false;

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $is_logged_in = true;
    $username = htmlspecialchars($_SESSION['username']);
    $welcome_message = "¡Bienvenido, " . $username . "!";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ... tu head aquí, incluyendo links a css/styles.css y Font Awesome ... -->
</head>
<body>
    <!-- Tu encabezado <header class="main-header">...</header> aquí -->

    <main>
        <!-- Coloca este div donde quieras el mensaje de bienvenida, por ejemplo, debajo del header -->
        <?php if ($is_logged_in): ?>
            <div class="welcome-banner" style="background-color: #28a745; color: white; padding: 15px; text-align: center; margin-bottom: 20px;">
                <p><?php echo $welcome_message; ?></p>
            </div>
        <?php else: ?>
            <!-- Opcional: un mensaje para usuarios no logueados -->
            <div class="welcome-banner" style="background-color: #007bff; color: white; padding: 15px; text-align: center; margin-bottom: 20px;">
                <p>¡Inicia sesión para una mejor experiencia de compra!</p>
            </div>
        <?php endif; ?>

        <!-- ... el resto del contenido de tu página ... -->
    </main>

    <!-- ... scripts o pie de página ... -->
</body>
</html> 