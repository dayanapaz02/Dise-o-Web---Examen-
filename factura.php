<?php
// Obtener datos de la factura desde la URL o sesión
$factura_numero = isset($_GET['numero']) ? $_GET['numero'] : '001';
$cliente_nombre = isset($_GET['cliente']) ? $_GET['cliente'] : 'Cliente';
$cliente_direccion = isset($_GET['direccion']) ? $_GET['direccion'] : 'Dirección';
$cliente_telefono = isset($_GET['telefono']) ? $_GET['telefono'] : 'Teléfono';
$productos_json = isset($_GET['productos']) ? $_GET['productos'] : '[]';
$fecha = date('d/m/Y');
$hora = date('H:i:s');

// Decodificar productos del carrito
$productos = json_decode($productos_json, true);
if (!$productos) {
    $productos = [];
}

// Calcular subtotal sumando los productos
$subtotal = 0;
foreach ($productos as $producto) {
    $subtotal += $producto['price'] * $producto['quantity'];
}
// Calcular ISV y total
$isv = $subtotal * 0.15;
$total = $subtotal + $isv;

// Debug: mostrar los productos recibidos
error_log("Productos en factura: " . print_r($productos, true));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura ByteStore - #<?php echo $factura_numero; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .factura-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 14px;
            opacity: 0.9;
        }

        .pdf-button {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin: 15px 0;
            transition: background 0.3s;
        }

        .pdf-button:hover {
            background: #218838;
        }

        .factura-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 2px solid #e9ecef;
        }

        .factura-title {
            color: #007bff;
            font-size: 24px;
            font-weight: 700;
        }

        .factura-numero {
            color: #dc3545;
            font-size: 18px;
            font-weight: 600;
        }

        .cliente-section {
            padding: 20px 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .cliente-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .cliente-item {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .cliente-label {
            font-weight: 600;
            color: #495057;
        }

        .productos-section {
            padding: 20px 30px;
        }

        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .productos-table th {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .productos-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .productos-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .totales-section {
            padding: 20px 30px;
            background: #f8f9fa;
        }

        .totales-container {
            display: flex;
            justify-content: flex-end;
        }

        .totales-item {
            background: #e9ecef;
            padding: 15px 25px;
            margin-left: 15px;
            border-radius: 5px;
            text-align: center;
            min-width: 150px;
        }

        .totales-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .totales-valor {
            font-size: 18px;
            font-weight: 700;
            color: #495057;
        }

        .footer-section {
            padding: 20px 30px;
            text-align: center;
            background: #343a40;
            color: white;
        }

        .footer-text {
            font-size: 14px;
            opacity: 0.8;
        }

        @media print {
            body {
                background: white;
            }
            .factura-container {
                box-shadow: none;
                margin: 0;
            }
            .pdf-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="factura-container">
        <!-- Header con información de la empresa -->
        <div class="header-section">
            <div class="company-info">
                <div class="company-name">BYTE STORE</div>
                <div class="company-details">
                    Tegucigalpa, Honduras C.A.<br>
                    www.bytestore.com<br>
                    info@bytestore.com
                </div>
            </div>
            <button class="pdf-button" onclick="window.print()">
                <i class="fas fa-file-pdf"></i> Clic para crear PDF
            </button>
        </div>

        <!-- Información de la empresa repetida -->
        <div style="padding: 15px 30px; background: #f8f9fa; text-align: center; border-bottom: 1px solid #e9ecef;">
            <strong>BYTE STORE</strong> - Tegucigalpa, Honduras C.A.<br>
            www.bytestore.com - info@bytestore.com
        </div>

        <!-- Encabezado de la factura -->
        <div class="factura-header">
            <div class="factura-title">Factura</div>
            <div class="factura-numero">Factura No: <?php echo $factura_numero; ?></div>
        </div>

        <!-- Información del cliente -->
        <div class="cliente-section">
            <div class="cliente-info">
                <div class="cliente-item">
                    <span class="cliente-label">Cliente:</span> <?php echo $cliente_nombre; ?>
                </div>
                <div class="cliente-item">
                    <span class="cliente-label">Dirección:</span> <?php echo $cliente_direccion; ?>
                </div>
                <div class="cliente-item">
                    <span class="cliente-label">Teléfono:</span> <?php echo $cliente_telefono; ?>
                </div>
                <div class="cliente-item">
                    <span class="cliente-label">Fecha:</span> <?php echo $fecha; ?> - <?php echo $hora; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="productos-section">
            <table class="productos-table">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                    <tr>
                        <td>1</td>
                        <td>Pago de Cuotas - ByteStore</td>
                        <td>L<?php echo number_format($subtotal, 2); ?></td>
                        <td>L<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['quantity']; ?></td>
                            <td><?php echo htmlspecialchars($producto['name']); ?></td>
                            <td>L<?php echo number_format($producto['price'], 2); ?></td>
                            <td>L<?php echo number_format($producto['price'] * $producto['quantity'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="totales-section">
            <div class="totales-container">
                <div class="totales-item">
                    <div class="totales-label">Subtotal:</div>
                    <div class="totales-valor">L<?php echo number_format($subtotal, 2); ?></div>
                </div>
                <div class="totales-item">
                    <div class="totales-label">ISV (15%):</div>
                    <div class="totales-valor">L<?php echo number_format($isv, 2); ?></div>
                </div>
                <div class="totales-item">
                    <div class="totales-label">Total a pagar:</div>
                    <div class="totales-valor">L<?php echo number_format($total, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-text">
                ¡Gracias por su compra!<br>
                Para cualquier consulta, contáctenos al info@bytestore.com
            </div>
        </div>
    </div>

    <script>
        // Auto-redirect después de 5 segundos si no se imprime
        setTimeout(function() {
            if (!window.matchMedia('print').matches) {
                // Solo redirigir si no se está imprimiendo
                console.log('Factura generada exitosamente');
            }
        }, 5000);
    </script>
</body>
</html> 