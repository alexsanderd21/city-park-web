<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Helper para responder JSON
function jsonOutput($success, $data = [], $message = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    switch ($action) {
        // ==========================================
        // 0. BÚSQUEDA REACTIVA DE CLIENTE POR DOCUMENTO
        // ==========================================
        case 'find_client':
            $doc = trim($_GET['documento'] ?? $_POST['documento'] ?? '');
            if (empty($doc)) {
                jsonOutput(false, [], 'Documento no proporcionado');
            }
            $stmt = $pdo->prepare("SELECT id, documento, CONCAT(nombres, ' ', apellidos) as nombre_completo, nombres, apellidos, correo, telefono, direccion, ciudad FROM clientes WHERE documento = :doc LIMIT 1");
            $stmt->execute([':doc' => $doc]);
            $cliente = $stmt->fetch();
            if ($cliente) {
                jsonOutput(true, $cliente, 'Cliente registrado encontrado');
            } else {
                jsonOutput(false, [], 'Cliente no registrado');
            }
            break;

        // ==========================================
        // 1. PROCESO DE CHECKOUT / COMPRA DE PASAPORTES
        // ==========================================
        case 'checkout':
            $documento = trim($_POST['documento'] ?? '');
            $nombre_completo = trim($_POST['nombre_completo'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? 'Armenia');
            $metodo_pago = trim($_POST['metodo_pago'] ?? 'Efectivo');
            $items_raw = $_POST['items'] ?? '[]';
            
            if (empty($documento) || empty($nombre_completo) || empty($correo) || empty($telefono)) {
                jsonOutput(false, [], 'Por favor completa todos los campos obligatorios del formulario.');
            }

            // Normalizar nombres y apellidos si vienen juntos
            $partes_nombre = explode(' ', $nombre_completo, 2);
            $nombres = $partes_nombre[0];
            $apellidos = $partes_nombre[1] ?? '';

            // Decodificar items del carrito
            $items = json_decode($items_raw, true);
            if (!is_array($items) || count($items) === 0) {
                jsonOutput(false, [], 'El carrito de compras está vacío.');
            }

            // 1.1 Buscar o registrar al cliente
            $stmtCliente = $pdo->prepare("SELECT id FROM clientes WHERE documento = :doc");
            $stmtCliente->execute([':doc' => $documento]);
            $clienteExistente = $stmtCliente->fetch();

            if ($clienteExistente) {
                $cliente_id = $clienteExistente['id'];
                // Actualizar datos de contacto del cliente existente
                $stmtUp = $pdo->prepare("UPDATE clientes SET nombres = :nom, apellidos = :ape, correo = :cor, telefono = :tel, direccion = :dir, ciudad = :ciu WHERE id = :id");
                $stmtUp->execute([
                    ':nom' => $nombres,
                    ':ape' => $apellidos,
                    ':cor' => $correo,
                    ':tel' => $telefono,
                    ':dir' => $direccion,
                    ':ciu' => $ciudad,
                    ':id' => $cliente_id
                ]);
            } else {
                // Registrar nuevo cliente
                $stmtIn = $pdo->prepare("INSERT INTO clientes (documento, nombres, apellidos, correo, telefono, direccion, ciudad) VALUES (:doc, :nom, :ape, :cor, :tel, :dir, :ciu)");
                $stmtIn->execute([
                    ':doc' => $documento,
                    ':nom' => $nombres,
                    ':ape' => $apellidos,
                    ':cor' => $correo,
                    ':tel' => $telefono,
                    ':dir' => $direccion,
                    ':ciu' => $ciudad
                ]);
                $cliente_id = $pdo->lastInsertId();
            }

            // 1.2 Calcular total de la orden
            $subtotal = 0;
            foreach ($items as $item) {
                $precio = (float)($item['precio'] ?? 0);
                $cant = (int)($item['cantidad'] ?? 1);
                $subtotal += ($precio * $cant);
            }
            $total = $subtotal;

            // 1.3 Generar código de orden único
            $codigo_orden = 'CP-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

            // Validar método de pago admitido
            if (!in_array($metodo_pago, ['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito'])) {
                $metodo_pago = 'Efectivo';
            }

            // 1.4 Registrar la venta
            $stmtVenta = $pdo->prepare("INSERT INTO ventas (codigo_orden, cliente_id, subtotal, total, metodo_pago, estado) VALUES (:cod, :cid, :sub, :tot, :mpago, 'Aprobado')");
            $stmtVenta->execute([
                ':cod' => $codigo_orden,
                ':cid' => $cliente_id,
                ':sub' => $subtotal,
                ':tot' => $total,
                ':mpago' => $metodo_pago
            ]);
            $venta_id = $pdo->lastInsertId();

            // 1.5 Registrar los detalles de la venta
            $stmtDetalle = $pdo->prepare("INSERT INTO detalle_ventas (venta_id, item_nombre, tipo_item, precio_unitario, cantidad, subtotal) VALUES (:vid, :inom, :itipo, :iprecio, :icant, :isub)");
            foreach ($items as $item) {
                $inom = $item['nombre'] ?? 'Pasaporte City-Park';
                $itipo = $item['tipo'] ?? 'Pasaporte';
                $iprecio = (float)($item['precio'] ?? 0);
                $icant = (int)($item['cantidad'] ?? 1);
                $isub = $iprecio * $icant;

                $stmtDetalle->execute([
                    ':vid' => $venta_id,
                    ':inom' => $inom,
                    ':itipo' => $itipo,
                    ':iprecio' => $iprecio,
                    ':icant' => $icant,
                    ':isub' => $isub
                ]);
            }

            jsonOutput(true, [
                'codigo_orden' => $codigo_orden,
                'venta_id' => $venta_id,
                'cliente' => [
                    'documento' => $documento,
                    'nombre_completo' => $nombre_completo,
                    'correo' => $correo,
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'ciudad' => $ciudad
                ],
                'items' => $items,
                'total' => $total,
                'total_formateado' => formatearCOP($total),
                'metodo_pago' => $metodo_pago,
                'fecha' => date('d/m/Y h:i A')
            ], '¡Reserva y compra confirmada con éxito! Presenta tu código en taquilla.');
            break;

        // ==========================================
        // 2. FORMULARIO DE CONTACTO
        // ==========================================
        case 'contacto':
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $asunto = trim($_POST['asunto'] ?? 'Consulta General');
            $mensaje = trim($_POST['mensaje'] ?? '');

            if (empty($nombre) || empty($correo) || empty($mensaje)) {
                jsonOutput(false, [], 'Por favor completa nombre, correo y mensaje.');
            }

            $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, correo, telefono, asunto, mensaje, leido) VALUES (:nom, :cor, :tel, :asu, :men, 0)");
            $stmt->execute([
                ':nom' => $nombre,
                ':cor' => $correo,
                ':tel' => $telefono,
                ':asu' => $asunto,
                ':men' => $mensaje
            ]);

            jsonOutput(true, [], '¡Gracias por contactarnos! Tu mensaje ha sido recibido por el equipo de City-Park y te responderemos a la brevedad.');
            break;

        // ==========================================
        // 3. ADMINISTRACIÓN: CRUD DE CLIENTES
        // ==========================================
        case 'client_list':
            $busqueda = trim($_GET['q'] ?? '');
            $sql = "SELECT id, documento, nombres, apellidos, CONCAT(nombres, ' ', apellidos) as nombre_completo, correo, telefono, direccion, ciudad, fecha_registro FROM clientes";
            
            if (!empty($busqueda)) {
                $sql .= " WHERE (documento LIKE :q OR nombres LIKE :q OR apellidos LIKE :q OR correo LIKE :q OR telefono LIKE :q)";
                $stmt = $pdo->prepare($sql . " ORDER BY id DESC");
                $stmt->execute([':q' => "%{$busqueda}%"]);
            } else {
                $stmt = $pdo->query($sql . " ORDER BY id DESC");
            }
            $clientes = $stmt->fetchAll();
            jsonOutput(true, $clientes);
            break;

        case 'client_save':
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $documento = trim($_POST['documento'] ?? '');
            $nombres = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? 'Armenia');

            if (empty($documento) || empty($nombres) || empty($correo)) {
                jsonOutput(false, [], 'Documento, nombres y correo son obligatorios.');
            }

            // Validar unicidad de documento
            if ($id) {
                $stmtCheck = $pdo->prepare("SELECT id FROM clientes WHERE documento = :doc AND id != :id");
                $stmtCheck->execute([':doc' => $documento, ':id' => $id]);
            } else {
                $stmtCheck = $pdo->prepare("SELECT id FROM clientes WHERE documento = :doc");
                $stmtCheck->execute([':doc' => $documento]);
            }
            if ($stmtCheck->fetch()) {
                jsonOutput(false, [], 'Ya existe otro cliente registrado con este documento de identidad.');
            }

            if ($id) {
                $stmt = $pdo->prepare("UPDATE clientes SET documento = :doc, nombres = :nom, apellidos = :ape, correo = :cor, telefono = :tel, direccion = :dir, ciudad = :ciu WHERE id = :id");
                $stmt->execute([
                    ':doc' => $documento,
                    ':nom' => $nombres,
                    ':ape' => $apellidos,
                    ':cor' => $correo,
                    ':tel' => $telefono,
                    ':dir' => $direccion,
                    ':ciu' => $ciudad,
                    ':id' => $id
                ]);
                $msg = 'Cliente actualizado correctamente.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO clientes (documento, nombres, apellidos, correo, telefono, direccion, ciudad) VALUES (:doc, :nom, :ape, :cor, :tel, :dir, :ciu)");
                $stmt->execute([
                    ':doc' => $documento,
                    ':nom' => $nombres,
                    ':ape' => $apellidos,
                    ':cor' => $correo,
                    ':tel' => $telefono,
                    ':dir' => $direccion,
                    ':ciu' => $ciudad
                ]);
                $id = $pdo->lastInsertId();
                $msg = 'Cliente creado correctamente.';
            }

            $stmtGet = $pdo->prepare("SELECT id, documento, nombres, apellidos, CONCAT(nombres, ' ', apellidos) as nombre_completo, correo, telefono, direccion, ciudad FROM clientes WHERE id = :id");
            $stmtGet->execute([':id' => $id]);
            jsonOutput(true, $stmtGet->fetch(), $msg);
            break;

        case 'client_delete':
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                jsonOutput(false, [], 'ID de cliente inválido.');
            }
            $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            jsonOutput(true, [], 'Cliente y sus registros vinculados eliminados correctamente.');
            break;

        // ==========================================
        // 4. ADMINISTRACIÓN: VENTAS / PASAPORTES
        // ==========================================
        case 'sales_list':
            $filtro_metodo = trim($_GET['metodo'] ?? '');
            $filtro_estado = trim($_GET['estado'] ?? '');
            $busqueda = trim($_GET['q'] ?? '');

            $sql = "SELECT v.*,
                           c.documento,
                           c.nombres,
                           c.apellidos,
                           c.correo,
                           c.telefono,
                           c.direccion,
                           (SELECT COUNT(*) FROM detalle_ventas dv WHERE dv.venta_id = v.id) as total_items,
                           (SELECT GROUP_CONCAT(CONCAT(dv.cantidad, 'x ', dv.item_nombre) SEPARATOR ', ') FROM detalle_ventas dv WHERE dv.venta_id = v.id) as descripcion_items
                    FROM ventas v
                    JOIN clientes c ON v.cliente_id = c.id
                    WHERE 1=1";
            $params = [];

            if (!empty($filtro_metodo)) {
                $sql .= " AND v.metodo_pago = :metodo";
                $params[':metodo'] = $filtro_metodo;
            }
            if (!empty($filtro_estado)) {
                $sql .= " AND v.estado = :estado";
                $params[':estado'] = $filtro_estado;
            }
            if (!empty($busqueda)) {
                $sql .= " AND (v.codigo_orden LIKE :q OR c.documento LIKE :q OR c.nombres LIKE :q OR c.apellidos LIKE :q OR c.correo LIKE :q)";
                $params[':q'] = "%{$busqueda}%";
            }

            $sql .= " ORDER BY v.id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $ventas = $stmt->fetchAll();

            foreach ($ventas as &$v) {
                $v['total_formateado'] = formatearCOP($v['total']);
                $v['fecha_formateada'] = date('d/m/Y h:i A', strtotime($v['fecha_venta']));
            }

            jsonOutput(true, $ventas);
            break;

        case 'sale_detail':
            $venta_id = (int)($_GET['id'] ?? 0);
            if ($venta_id <= 0) {
                jsonOutput(false, [], 'ID de venta inválido.');
            }

            $stmtV = $pdo->prepare("SELECT v.*, c.documento, c.nombres, c.apellidos, c.correo, c.telefono, c.direccion, c.ciudad
                                   FROM ventas v
                                   JOIN clientes c ON v.cliente_id = c.id
                                   WHERE v.id = :id");
            $stmtV->execute([':id' => $venta_id]);
            $venta = $stmtV->fetch();

            if (!$venta) {
                jsonOutput(false, [], 'Venta no encontrada.');
            }

            $stmtD = $pdo->prepare("SELECT * FROM detalle_ventas WHERE venta_id = :id");
            $stmtD->execute([':id' => $venta_id]);
            $detalles = $stmtD->fetchAll();

            foreach ($detalles as &$d) {
                $d['precio_formateado'] = formatearCOP($d['precio_unitario']);
                $d['subtotal_formateado'] = formatearCOP($d['subtotal']);
            }

            $venta['total_formateado'] = formatearCOP($venta['total']);
            $venta['fecha_formateada'] = date('d/m/Y h:i A', strtotime($venta['fecha_venta']));
            $venta['detalles'] = $detalles;

            jsonOutput(true, $venta);
            break;

        case 'sale_update_status':
            $venta_id = (int)($_POST['id'] ?? 0);
            $estado = trim($_POST['estado'] ?? 'Aprobado');
            if (!in_array($estado, ['Aprobado', 'Pendiente', 'Cancelado'])) {
                jsonOutput(false, [], 'Estado no válido.');
            }
            $stmt = $pdo->prepare("UPDATE ventas SET estado = :est WHERE id = :id");
            $stmt->execute([':est' => $estado, ':id' => $venta_id]);
            jsonOutput(true, [], 'Estado de la orden actualizado.');
            break;

        // ==========================================
        // 5. ADMINISTRACIÓN: MENSAJES
        // ==========================================
        case 'messages_list':
            $stmt = $pdo->query("SELECT id, nombre, correo, telefono, asunto, mensaje, leido, fecha_envio FROM mensajes_contacto ORDER BY id DESC");
            $mensajes = $stmt->fetchAll();
            foreach ($mensajes as &$m) {
                $m['fecha_formateada'] = date('d/m/Y h:i A', strtotime($m['fecha_envio']));
            }
            jsonOutput(true, $mensajes);
            break;

        case 'message_mark_read':
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = :id");
            $stmt->execute([':id' => $id]);
            jsonOutput(true, [], 'Mensaje marcado como leído.');
            break;

        case 'message_delete':
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = :id");
            $stmt->execute([':id' => $id]);
            jsonOutput(true, [], 'Mensaje eliminado.');
            break;

        default:
            jsonOutput(false, [], 'Acción no reconocida.');
    }
} catch (Exception $e) {
    jsonOutput(false, [], 'Error en el servidor: ' . $e->getMessage());
}
