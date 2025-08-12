-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2025 at 01:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventario`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_factura`
--

INSERT INTO `detalle_factura` (`id`, `factura_id`, `producto_id`, `cantidad`, `precio`, `total`) VALUES
(9, 9, 4, 1, 5000.00, 5000.00),
(10, 10, 4, 1, 5000.00, 5000.00),
(11, 11, 4, 1, 5000.00, 5000.00),
(12, 12, 4, 1, 5000.00, 5000.00),
(13, 13, 4, 2, 5000.00, 10000.00),
(14, 14, 4, 1, 5000.00, 5000.00),
(15, 15, 4, 1, 5000.00, 5000.00),
(16, 16, 4, 1, 5000.00, 5000.00),
(17, 17, 4, 1, 5000.00, 5000.00),
(18, 18, 7, 1, 100.00, 100.00),
(19, 19, 3, 1, 200.00, 200.00),
(20, 19, 5, 1, 350.00, 350.00),
(21, 20, 4, 1, 5000.00, 5000.00),
(22, 20, 5, 1, 350.00, 350.00),
(23, 21, 4, 1, 5000.00, 5000.00),
(24, 22, 5, 2, 350.00, 700.00),
(25, 22, 6, 1, 5000.00, 5000.00),
(26, 23, 4, 1, 5000.00, 5000.00),
(27, 24, 5, 1, 350.00, 350.00),
(28, 25, 5, 1, 350.00, 350.00),
(29, 25, 6, 2, 5000.00, 10000.00),
(30, 25, 3, 1, 200.00, 200.00),
(31, 26, 4, 1, 5000.00, 5000.00),
(32, 27, 4, 1, 5000.00, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facturas`
--

INSERT INTO `facturas` (`id`, `cliente_id`, `fecha`, `subtotal`, `iva`, `total`) VALUES
(9, 17, '2025-07-22 04:52:43', 5000.00, 750.00, 5750.00),
(10, 17, '2025-07-22 04:54:56', 5000.00, 750.00, 5750.00),
(11, 17, '2025-07-22 04:57:43', 5000.00, 750.00, 5750.00),
(12, 17, '2025-07-22 04:59:52', 5000.00, 750.00, 5750.00),
(13, 17, '2025-07-22 05:03:45', 10000.00, 1500.00, 11500.00),
(14, 17, '2025-07-22 05:08:38', 5000.00, 750.00, 5750.00),
(15, 17, '2025-07-22 05:10:06', 5000.00, 750.00, 5750.00),
(16, 17, '2025-07-22 05:12:20', 5000.00, 750.00, 5750.00),
(17, 17, '2025-07-22 14:46:24', 5000.00, 750.00, 5750.00),
(18, 32, '2025-07-22 14:48:24', 100.00, 15.00, 115.00),
(19, 32, '2025-07-22 14:49:15', 550.00, 82.50, 632.50),
(20, 32, '2025-07-22 14:50:11', 5350.00, 802.50, 6152.50),
(21, 32, '2025-07-22 14:54:26', 5000.00, 750.00, 5750.00),
(22, 32, '2025-07-22 14:55:15', 5700.00, 855.00, 6555.00),
(23, 32, '2025-07-22 14:56:03', 5000.00, 750.00, 5750.00),
(24, 32, '2025-07-22 14:56:57', 350.00, 52.50, 402.50),
(25, 32, '2025-07-22 15:06:25', 10550.00, 1582.50, 12132.50),
(26, 17, '2025-07-29 02:43:20', 5000.00, 750.00, 5750.00),
(27, 17, '2025-07-29 02:53:22', 5000.00, 750.00, 5750.00);

-- --------------------------------------------------------

--
-- Table structure for table `libroscomentarios`
--

CREATE TABLE `libroscomentarios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `editorial` varchar(255) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `anio_publicacion` int(4) DEFAULT NULL,
  `categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `comentarios` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `libroscomentarios`
--

INSERT INTO `libroscomentarios` (`id`, `titulo`, `autor`, `editorial`, `isbn`, `anio_publicacion`, `categoria`, `descripcion`, `precio`, `stock`, `comentarios`, `fecha_registro`) VALUES
(1, 'Matemáticas 1° Grado', 'María González', 'Ediciones Fares', '978-99926-1234-5', 2023, 'Educación Básica', 'Libro de matemáticas para primer grado con ejercicios prácticos y actividades interactivas.', 450.00, 25, 'Libro actualizado según el nuevo currículo', '2025-08-05 12:54:29'),
(2, 'Lenguaje 1° Grado', 'Carlos Rodríguez', 'Ediciones Fares', '978-99926-1235-2', 2023, 'Educación Básica', 'Libro de lenguaje y comunicación para desarrollar habilidades de lectura y escritura.', 420.00, 30, 'Incluye actividades de comprensión lectora', '2025-08-05 12:54:29'),
(3, 'Ciencias Naturales 2° Grado', 'Ana Martínez', 'Ediciones Fares', '978-99926-1236-9', 2023, 'Educación Básica', 'Exploración del mundo natural con experimentos y actividades prácticas.', 480.00, 20, 'Con experimentos seguros para niños', '2025-08-05 12:54:29'),
(4, 'Estudios Sociales 3° Grado', 'Luis Pérez', 'Ediciones Fares', '978-99926-1237-6', 2023, 'Educación Básica', 'Historia, geografía y civismo para comprender nuestro entorno social.', 460.00, 22, 'Incluye mapas y actividades interactivas', '2025-08-05 12:54:29'),
(5, 'Matemáticas Avanzadas I BTP', 'Roberto Silva', 'Ediciones Fares', '978-99926-1238-3', 2023, 'I BTP', 'Matemáticas para primer año de Bachillerato Técnico con enfoque práctico.', 520.00, 15, 'Incluye ejercicios de aplicación técnica', '2025-08-05 12:54:29'),
(6, 'Lenguaje y Literatura I BCH', 'Carmen Herrera', 'Ediciones Fares', '978-99926-1239-0', 2023, 'I BCH', 'Literatura universal y análisis de textos para Ciencias y Humanidades.', 500.00, 18, 'Con análisis de obras clásicas', '2025-08-05 12:54:29'),
(7, 'Física II BTP', 'Miguel Ángel López', 'Ediciones Fares', '978-99926-1240-6', 2023, 'II BTP', 'Física aplicada para segundo año de Bachillerato Técnico.', 550.00, 12, 'Incluye experimentos de laboratorio', '2025-08-05 12:54:29'),
(8, 'Química III BTP', 'Patricia Morales', 'Ediciones Fares', '978-99926-1241-3', 2023, 'III BTP', 'Química avanzada para tercer año de Bachillerato Técnico.', 580.00, 10, 'Con prácticas de laboratorio incluidas', '2025-08-05 12:54:29'),
(9, 'Cien años de soledad', 'Gabriel Garcia Marquez', 'Español', '05102003', 2012, 'I BTP', 'Libro de poesia', 500.00, 52, 'Ninguno', '2025-08-05 12:55:55'),
(10, 'Cien años de soledad', 'Gabriel Garcia Marquez', 'Español', '05102003', 2010, 'I BCH', 'gnj', 500.00, 50, 'k', '2025-08-05 13:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `producto_nombre` varchar(255) NOT NULL,
  `fecha_carga` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo_producto` varchar(50) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `codigo_producto`, `nombre`, `descripcion`, `precio`, `stock`, `fecha_creacion`) VALUES
(3, '12', 'Mouse', 'Mouse Inalambrico', 200.00, 55, '2025-06-24 13:48:00'),
(4, '13', 'Laptop', 'Laptop hp', 5000.00, 60, '2025-06-24 14:14:08'),
(5, '14', 'Teclado', 'Teclado Dell', 350.00, 20, '2025-06-24 14:22:41'),
(6, '15', 'CPU', 'CPU Dell coreI5', 5000.00, 25, '2025-06-24 14:35:25'),
(7, '4', 'dayaaa', 'chocolate', 100.00, 5, '2025-07-22 00:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono_residencial` varchar(20) DEFAULT NULL,
  `celular` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registros`
--

INSERT INTO `registros` (`id`, `codigo`, `nombre`, `direccion`, `telefono_residencial`, `celular`, `created_at`) VALUES
(17, NULL, 'Michelle Paz', 'Cañaveral', '78512', '94562536', '2025-05-27 04:40:45'),
(18, NULL, 'Marvin', 'Cañaveral', '96938704', '85963245', '2025-05-27 04:40:58'),
(19, NULL, 'Mary', 'Peña Blanca', '94641590', '94562536', '2025-05-27 04:41:18'),
(20, NULL, 'Lourdes', 'Cañaveral', '96938704', '89562512', '2025-05-27 04:41:29'),
(29, NULL, 'Redin Méndez', 'Cañaveral', '94641590', '94641590', '2025-05-27 17:26:03'),
(31, NULL, 'yui', 'jhk', '789', '7890', '2025-06-10 12:56:33'),
(32, NULL, 'Dayana', 'Colonia Montreal, Santa cruz de Yojoa, Cortes', '94641590', '89562512', '2025-06-10 13:27:59'),
(33, NULL, 'Genesis', 'Cañaveral', '89562310', '95650506', '2025-06-10 13:28:09'),
(34, NULL, 'Redin Méndez', 'Cañaveral', '89562310', '89562512', '2025-06-10 13:28:33'),
(35, NULL, 'Genesis', 'Cañaveral', '89562310', '89562512', '2025-06-10 13:28:44'),
(36, NULL, 'Dayana', 'peñaa', '96938704', '94641590', '2025-06-10 13:28:59'),
(37, NULL, 'Michelle Paz', 'Peña Blanca', '89562310', '85963245', '2025-06-10 13:29:14'),
(38, NULL, 'Michelle Paz', 'Peña Blanca', '94641590', '94641590', '2025-06-10 13:29:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facturas_ibfk_1` (`cliente_id`);

--
-- Indexes for table `libroscomentarios`
--
ALTER TABLE `libroscomentarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_producto` (`codigo_producto`);

--
-- Indexes for table `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `libroscomentarios`
--
ALTER TABLE `libroscomentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`),
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `registros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
