<?php
// Incluir el archivo de configuración de la base de datos
require_once 'php/db_config.php';

$user = null; // Variable para almacenar los datos del usuario
$message = '';
$message_type = '';

// 1. Manejar la recuperación de datos del usuario para prellenar el formulario
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $sql = "SELECT id, full_name, email FROM users WHERE id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta de selección: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
        } else {
            $message = "Usuario no encontrado.";
            $message_type = "danger";
        }
        $stmt->close();

    } catch (Exception $e) {
        error_log("Excepción al obtener registro para edición: " . $e->getMessage());
        $message = "Error al cargar los datos del usuario.";
        $message_type = "danger";
    }
} else if (isset($_POST['update_user'])) {
    // 2. Manejar el envío del formulario para actualizar los datos
    $user_id = htmlspecialchars(trim($_POST['user_id']));
    $full_name = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Validaciones básicas
    if (empty($full_name) || empty($email) || empty($user_id)) {
        $message = "Todos los campos obligatorios deben ser llenados.";
        $message_type = "danger";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "El formato del correo electrónico es inválido.";
        $message_type = "danger";
    } else if (!empty($password) && $password !== $confirm_password) {
        $message = "Las contraseñas no coinciden.";
        $message_type = "danger";
    } else {
        try {
            // Construir la consulta de actualización
            $sql = "UPDATE users SET full_name = ?, email = ?";
            $params = ["ss", $full_name, $email];

            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password_hash = ?";
                $params[0] .= "s"; // Añadir tipo para la contraseña
                $params[] = $password_hash;
            }

            $sql .= " WHERE id = ?";
            $params[0] .= "i"; // Añadir tipo para el ID
            $params[] = $user_id;

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta de actualización: " . $conn->error);
            }
            
            // Usar call_user_func_array para bind_param con un array de parámetros dinámico
            call_user_func_array([$stmt, 'bind_param'], refValues($params));

            if ($stmt->execute()) {
                $message = "Registro actualizado exitosamente.";
                $message_type = "success";
                // Opcional: Redirigir a registro.php después de la actualización exitosa
                header("Location: registro.php?status=updated");
                exit();
            } else {
                error_log("Error al ejecutar actualización: " . $stmt->error);
                $message = "Error al actualizar el registro.";
                $message_type = "danger";
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Excepción al actualizar registro: " . $e->getMessage());
            $message = "Error: " . $e->getMessage();
            $message_type = "danger";
        }
    }

    // Si hubo un error o éxito al actualizar, volvemos a cargar los datos del usuario por su ID
    // Esto es útil si no redirigimos, para que el formulario se mantenga prellenado
    if (isset($user_id)) {
        try {
            $sql = "SELECT id, full_name, email FROM users WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error al recargar usuario después de actualizar: " . $e->getMessage());
        }
    }

} else {
    $message = "ID de usuario no proporcionado.";
    $message_type = "danger";
}

$conn->close();

// Función auxiliar para pasar parámetros por referencia a bind_param
function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //PHP 5.3+ 
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = & $arr[$key];
        return $refs;
    }
    return $arr;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Suministros Dayana</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/registro.css"> <!-- Reutilizamos estilos de registro.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 2em;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .edit-container h2 {
            text-align: center;
            margin-bottom: 1.5em;
            color: #333;
        }
        .message {
            padding: 1em;
            margin-bottom: 1em;
            border-radius: 4px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: calc(100% - 20px); /* Ajuste para padding */
            padding: 10px;
            margin-bottom: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .button-group {
            text-align: center;
            margin-top: 1.5em;
        }
        .button-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s ease;
        }
        .button-group button:hover {
            background-color: #0056b3;
        }
        .button-group a {
            display: inline-block;
            margin-left: 1em;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }
        .button-group a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <!-- Contenido del encabezado (igual que en registro.php) -->
        <div class="header-top-bar">
            <div class="container">
                <span class="discount-message">Hasta L10,000 de descuento en tu nuevo smart TV. Compra 👉 <a href="#" class="here-link">aquí.</a></span>
                <div class="top-links">
                    <a href="#">Estado de Orden</a>
                    <a href="#">Soporte</a>
                    <a href="login.html" class="login-settings">Iniciar Sesión <i class="fas fa-cog"></i></a>
                </div>
            </div>
        </div>

        <div class="header-main-section">
            <div class="container main-content">
                <div class="logo">
                    <a href="index.html">BYTE STORE</a>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Buscar productos...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="user-actions">
                    <a href="login.html" class="action-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="favoritos.html" class="action-item">
                        <i class="fas fa-heart"></i>
                        <span>Favoritos</span>
                    </a>
                    <a href="carrito.html" class="action-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Carrito</span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="main-nav">
            <div class="container">
                <ul>
                    <li><a href="index.html">INICIO</a></li>
                    <li><a href="marcas.html">MARCAS</a></li>
                    <li><a href="productos.html">PRODUCTOS</a></li>
                    <li><a href="exclusivos-online.html">EXCLUSIVOS ONLINE</a></li>
                    <li><a href="credito-bytestore.html">CRÉDITO BYTE STORE</a></li>
                    <li><a href="servicio-tecnico.html">SERVICIO TÉCNICO</a></li>
                    <li><a href="ubicacion.html">UBICACIÓN</a></li>
                    <li><a href="blog.html">BLOG</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="edit-container form-card">
            <h2>Editar Usuario</h2>
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($user): ?>
            <form action="editar_registro.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <div class="button-group">
                    <button type="submit" name="update_user">Actualizar Registro</button>
                    <a href="registro.php">Cancelar</a>
                </div>
            </form>
            <?php else: ?>
                <p>No se pudo cargar la información del usuario.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Puedes añadir un pie de página o scripts si los tienes -->
</body>
</html> 