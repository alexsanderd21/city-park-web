-- Base de datos para City-Park
CREATE DATABASE IF NOT EXISTS `city_park_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `city_park_db`;

-- Tabla de Usuarios Administradores
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `rol` VARCHAR(30) DEFAULT 'admin',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuario admin predeterminado (admin / admin123)
INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`, `rol`) 
VALUES (1, 'admin', '$2y$10$wE8wY0Zf3tH0Uo5.9iE.G.0Xb2Z9j2jQ1jA1m6nZJ6zE7pI7k6.6y', 'Administrador Principal', 'admin')
ON DUPLICATE KEY UPDATE `usuario` = 'admin';

-- Tabla de Clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `documento` VARCHAR(30) NOT NULL UNIQUE,
  `nombres` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(120) NOT NULL,
  `telefono` VARCHAR(30) NOT NULL,
  `direccion` VARCHAR(200) NOT NULL,
  `ciudad` VARCHAR(100) DEFAULT 'Armenia',
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar clientes semilla para demostración
INSERT INTO `clientes` (`documento`, `nombres`, `apellidos`, `correo`, `telefono`, `direccion`, `ciudad`) VALUES
('1094928371', 'Carlos', 'Pérez Restrepo', 'carlos.perez@example.com', '3116859356', 'Cra 14 # 18-20', 'Armenia'),
('1094837261', 'María', 'Gómez Jaramillo', 'maria.gomez@example.com', '3046767956', 'Av. Centenario # 25-40', 'Armenia'),
('1094726152', 'Juan', 'López Echeverri', 'juan.lopez@example.com', '3125558899', 'Calle 21 # 15-30', 'Armenia')
ON DUPLICATE KEY UPDATE `documento`=`documento`;

-- Tabla de Ventas / Pasaportes Vendidos
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo_orden` VARCHAR(30) NOT NULL UNIQUE,
  `cliente_id` INT NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `metodo_pago` ENUM('Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito') NOT NULL DEFAULT 'Efectivo',
  `estado` ENUM('Aprobado', 'Pendiente', 'Cancelado') NOT NULL DEFAULT 'Aprobado',
  `notas` TEXT NULL,
  `fecha_venta` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Detalles de Venta / Pasaportes
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `venta_id` INT NOT NULL,
  `item_nombre` VARCHAR(150) NOT NULL,
  `tipo_item` VARCHAR(50) DEFAULT 'Pasaporte',
  `precio_unitario` DECIMAL(12,2) NOT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar ventas de ejemplo iniciales
INSERT INTO `ventas` (`id`, `codigo_orden`, `cliente_id`, `subtotal`, `descuento`, `total`, `metodo_pago`, `estado`, `fecha_venta`) VALUES
(1, 'CP-2026-001', 1, 70000.00, 0.00, 70000.00, 'Tarjeta Débito', 'Aprobado', NOW() - INTERVAL 2 DAY),
(2, 'CP-2026-002', 2, 48000.00, 0.00, 48000.00, 'Tarjeta Crédito', 'Aprobado', NOW() - INTERVAL 1 DAY),
(3, 'CP-2026-003', 3, 110000.00, 0.00, 110000.00, 'Efectivo', 'Aprobado', NOW())
ON DUPLICATE KEY UPDATE `codigo_orden`=`codigo_orden`;

INSERT INTO `detalle_ventas` (`venta_id`, `item_nombre`, `tipo_item`, `precio_unitario`, `cantidad`, `subtotal`) VALUES
(1, 'Pasaporte Junior', 'Pasaporte', 35000.00, 2, 70000.00),
(2, 'Pasaporte Estrella', 'Pasaporte', 48000.00, 1, 48000.00),
(3, 'Combo Familiar', 'Combo', 110000.00, 1, 110000.00)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Tabla de Mensajes de Contacto
CREATE TABLE IF NOT EXISTS `mensajes_contacto` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(120) NOT NULL,
  `telefono` VARCHAR(30) NULL,
  `asunto` VARCHAR(150) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `leido` TINYINT(1) DEFAULT 0,
  `fecha_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mensajes de prueba iniciales
INSERT INTO `mensajes_contacto` (`nombre`, `correo`, `telefono`, `asunto`, `mensaje`, `leido`, `fecha_envio`) VALUES
('Laura Montes', 'laura.montes@correo.com', '3157774433', 'Cotización Fiesta de Cumpleaños', 'Hola, me gustaría cotizar una fiesta de cumpleaños para 15 niños para el próximo sábado.', 0, NOW() - INTERVAL 3 HOUR),
('Andrés Valencia', 'andres.v@correo.com', '3109988776', 'Convenio Colegio Infantil', 'Buenas tardes, quisiéramos coordinar una visita pedagógica con 40 estudiantes de primaria.', 0, NOW() - INTERVAL 1 DAY)
ON DUPLICATE KEY UPDATE `id`=`id`;
