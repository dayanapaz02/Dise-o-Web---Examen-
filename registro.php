<?php
// Incluir el archivo de configuración de la base de datos
require_once 'php/db_config.php';

// Configuración de la paginación
$recordsPerPage = 5; // Puedes ajustar cuántos registros quieres por página
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$usuarios = []; // Inicializar array de usuarios
$totalPages = 1; // Inicializar totalPages

try {
    // Obtener el total de registros
    $totalRecordsStmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $totalRecordsStmt->execute();
    $totalRecords = $totalRecordsStmt->get_result()->fetch_row()[0];
    $totalRecordsStmt->close();

    // DEBUG: Mostrar el total de registros
    // echo "<!-- Total Records: " . $totalRecords . " -->";
    
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Obtener los registros para la página actual
    $stmt = $conn->prepare("SELECT id, full_name, email, registration_date FROM users ORDER BY registration_date DESC LIMIT ?, ?");
    
    if ($stmt === false) {
        // Error al preparar la sentencia, muestra el error de MySQLi
        throw new Exception("Error al preparar la consulta de usuarios: " . $conn->error);
    }

    $stmt->bind_param("ii", $offset, $recordsPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // DEBUG: Mostrar el contenido de \$usuarios
    // echo "<!-- Contenido de \$usuarios: ";
    // print_r($usuarios); 
    // echo " -->";

} catch (Exception $e) {
    // Manejar el error de la base de datos al recuperar los registros
    error_log("Error al recuperar registros: " . $e->getMessage());
    // Opcional: mostrar un mensaje al usuario, pero es mejor solo logearlo en producción
}

$conn->close(); // Cerrar la conexión después de todas las operaciones
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate - Suministros Dayana</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/registro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<?php 
// Mostrar mensajes de estado o error de las redirecciones
if (isset($_GET['status'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = '';
    $message_type = ''; // success, danger

    switch ($status) {
        case 'deleted':
            $message = "Registro eliminado exitosamente.";
            $message_type = "success";
            break;
        case 'updated':
            $message = "Registro actualizado exitosamente.";
            $message_type = "success";
            break;
        case 'id_not_provided':
            $message = "Error: ID de usuario no proporcionado para la operación.";
            $message_type = "danger";
            break;
        case 'error_delete_execution':
        case 'error_delete_exception':
            $message = "Error al intentar eliminar el registro.";
            $message_type = "danger";
            break;
        case 'db_error_check_email':
        case 'db_prepare_failed':
        case 'db_execution_failed':
            $message = "Error interno de la base de datos. Por favor, inténtelo de nuevo.";
            $message_type = "danger";
            break;
        default:
            // Puedes agregar más casos para otros estados si los hay
            break;
    }

    if (!empty($message)) {
        echo '<div class="status-message ' . $message_type . '">' . $message . '</div>';
    }
}

// Mostrar mensajes de error específicos del registro
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    $error_message = '';

    switch ($error) {
        case 'campos_vacios':
            $error_message = "Por favor, complete todos los campos.";
            break;
        case 'email_invalido':
            $error_message = "El formato del correo electrónico es inválido.";
            break;
        case 'contrasenas_no_coinciden':
            $error_message = "Las contraseñas no coinciden.";
            break;
        case 'email_ya_registrado':
            $error_message = "Este correo electrónico ya está registrado. Por favor, use otro.";
            break;
        default:
            $error_message = "Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo.";
            break;
    }
    echo '<div class="status-message danger">' . $error_message . '</div>';
}

?>

    <header class="main-header">
        <!-- Barra superior (descuento, estado de orden, soporte, iniciar sesión) -->
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

        <!-- Sección principal del header (logo, buscador, íconos de usuario) -->
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
                    <a href="favotitos.html" class="action-item">
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

        <!-- Barra de navegación principal (INICIO, MARCAS, PRODUCTOS, etc.) -->
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
        <div class="content-wrapper">
            <!-- Contenedor del formulario de registro -->
            <div class="registro-container form-card">
                <h2>Crear una Cuenta</h2>
                <form action="php/submit_registration.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit">Registrarse</button>
                </form>
                <p class="mt-3" style="text-align: center; color: #eee; font-size: 0.9em;">
                    ¿Ya tienes cuenta? <a href="login.html" style="color: #007bff; text-decoration: none;">Inicia sesión aquí</a>
                </p>
            </div>

            <!-- Nueva sección para la tabla de registros -->
            <div class="registros-table-container form-card">
                <h2>Usuarios Registrados</h2>
                <table class="registros-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['registration_date']); ?></td>
                                    <td>
                                        <!-- Botones de acción: Ver (opcional), Editar, Eliminar -->
                                        <!-- El botón 'Ver' es opcional, ya que la información principal ya está en la tabla -->
                                        <!-- <a href="ver_registro.php?id=<?php echo $usuario['id']; ?>" class="action-button view">Ver</a> -->
                                        <a href="editar_registro.php?id=<?php echo $usuario['id']; ?>" class="action-button edit">Editar</a>
                                        <a href="eliminar_registro.php?id=<?php echo $usuario['id']; ?>" class="action-button delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este registro?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No hay usuarios registrados aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=1" class="page-link">&laquo; Primera</a>
                        <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link">&lsaquo; Anterior</a>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="page-link <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link">Siguiente &rsaquo;</a>
                        <a href="?page=<?php echo $totalPages; ?>" class="page-link">Última &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Puedes añadir un pie de página o scripts si los tienes -->
</body>
</html> 