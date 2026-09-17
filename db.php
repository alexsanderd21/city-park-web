<?php
// Configuración y Conexión a la Base de Datos City-Park
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para cargar variables del archivo .env
function loadCityParkEnv($filePath = __DIR__ . '/.env') {
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Cargar variables de entorno
loadCityParkEnv();

// Obtener parámetros de conexión
$db_host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost');
$db_port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306);
$db_name = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'city_park_db');
$db_user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root');
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_ENV['DB_PASS'] ?? '');
$app_url = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? 'http://localhost/city-park');

try {
    // 1. Intentar conectar a la base de datos configurada en .env
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 3 // Timeout rápido si se prueba host remoto en local
    ]);
} catch (PDOException $e) {
    // 2. Si falla (por ejemplo, host remoto de InfinityFree bloquea acceso desde IP externa de localhost),
    // intentar conectar con fallback local para desarrollo en XAMPP
    try {
        $local_dsn = "mysql:host=localhost;port=3306;dbname=city_park_db;charset=utf8mb4";
        $pdo = new PDO($local_dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $ex_local) {
        // Si no existe la base local, intentar crearla
        try {
            $pdo_init = new PDO("mysql:host=localhost;port=3306;charset=utf8mb4", 'root', '');
            $pdo_init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `city_park_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            $pdo = new PDO("mysql:host=localhost;port=3306;dbname=city_park_db;charset=utf8mb4", 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Exception $final_ex) {
            die("Error de conexión a Base de Datos (Configurada: {$db_host} / Local: localhost): " . $e->getMessage());
        }
    }
}

// Función auxiliar para formatear montos en Pesos Colombianos (COP)
function formatearCOP($valor) {
    return '$' . number_format((float)$valor, 0, ',', '.');
}

// Asegurar y sincronizar tablas y columnas requeridas
try {
    // 1. Tabla usuarios
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `usuarios` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `usuario` VARCHAR(50) NOT NULL UNIQUE,
          `password` VARCHAR(255) NOT NULL,
          `nombre` VARCHAR(100) NOT NULL,
          `rol` VARCHAR(30) DEFAULT 'admin',
          `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 2. Tabla clientes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `clientes` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `documento` VARCHAR(30) NULL,
          `cedula` VARCHAR(50) NULL,
          `nombre` VARCHAR(100) NULL,
          `nombres` VARCHAR(100) NULL,
          `apellidos` VARCHAR(100) NULL,
          `correo` VARCHAR(120) NULL,
          `email` VARCHAR(120) NULL,
          `telefono` VARCHAR(30) NOT NULL,
          `direccion` VARCHAR(200) NOT NULL,
          `ciudad` VARCHAR(100) DEFAULT 'Armenia',
          `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Asegurar columnas complementarias en clientes
    $clienteCols = $pdo->query("DESCRIBE clientes")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('documento', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `documento` VARCHAR(50) NULL");
    }
    if (!in_array('cedula', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `cedula` VARCHAR(50) NULL");
    }
    if (!in_array('nombres', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `nombres` VARCHAR(100) NULL");
    }
    if (!in_array('apellidos', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `apellidos` VARCHAR(100) NULL");
    }
    if (!in_array('correo', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `correo` VARCHAR(120) NULL");
    }
    if (!in_array('email', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `email` VARCHAR(120) NULL");
    }
    if (!in_array('ciudad', $clienteCols)) {
        $pdo->exec("ALTER TABLE clientes ADD COLUMN `ciudad` VARCHAR(100) DEFAULT 'Armenia'");
    }

    // 3. Tabla ventas
    $pdo->exec("
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
    ");

    // 4. Tabla detalle_ventas
    $pdo->exec("
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
    ");

    // 5. Tabla mensajes_contacto
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `mensajes_contacto` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `nombre` VARCHAR(100) NOT NULL,
          `correo` VARCHAR(120) NULL,
          `email` VARCHAR(120) NULL,
          `telefono` VARCHAR(30) NULL,
          `asunto` VARCHAR(150) DEFAULT 'Consulta General',
          `mensaje` TEXT NOT NULL,
          `leido` TINYINT(1) DEFAULT 0,
          `fecha_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $msgCols = $pdo->query("DESCRIBE mensajes_contacto")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('correo', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `correo` VARCHAR(120) NULL");
    }
    if (!in_array('email', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `email` VARCHAR(120) NULL");
    }
    if (!in_array('telefono', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `telefono` VARCHAR(30) NULL");
    }
    if (!in_array('asunto', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `asunto` VARCHAR(150) DEFAULT 'Consulta General'");
    }
    if (!in_array('leido', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `leido` TINYINT(1) DEFAULT 0");
    }
    if (!in_array('fecha_envio', $msgCols)) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN `fecha_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }

    // 6. Verificar y crear usuario admin por defecto si no existe
    $stmtAdmin = $pdo->prepare("SELECT COUNT(*) FROM `usuarios` WHERE `usuario` = 'admin'");
    $stmtAdmin->execute();
    if ($stmtAdmin->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmtInsert = $pdo->prepare("INSERT INTO `usuarios` (`usuario`, `password`, `nombre`, `rol`) VALUES ('admin', :pass, 'Administrador Principal', 'admin')");
        $stmtInsert->execute([':pass' => $hash]);
    }
} catch (Exception $e) {
    // Continuar
}
