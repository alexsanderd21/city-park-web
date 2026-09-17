<?php
require_once __DIR__ . '/db.php';

$login_error = '';

// Procesar Inicio de Sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($usuario) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :u LIMIT 1");
        $stmt->execute([':u' => $usuario]);
        $user = $stmt->fetch();

        // Validar usuario admin y contraseña admin123 (o hash correspondiente)
        if ($user && (password_verify($password, $user['password']) || ($usuario === 'admin' && $password === 'admin123'))) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_user'] = $user['usuario'];
            $_SESSION['admin_name'] = $user['nombre'];
            header('Location: admin.php');
            exit;
        } else {
            $login_error = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $login_error = 'Por favor ingresa usuario y contraseña.';
    }
}

// Comprobar si está autenticado
$is_logged = !empty($_SESSION['admin_logged']);

// Si está logueado, consultar métricas iniciales
if ($is_logged) {
    // Total Clientes
    $total_clientes = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    // Total Pasaportes / Ventas
    $total_ventas = $pdo->query("SELECT COUNT(*) FROM ventas")->fetchColumn();
    // Total Ingresos COP
    $total_ingresos = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE estado = 'Aprobado'")->fetchColumn();
    // Mensajes sin leer
    $total_mensajes_nuevos = $pdo->query("SELECT COUNT(*) FROM mensajes_contacto WHERE leido = 0")->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrativo | City-Park</title>
  
  <link rel="icon" type="image/jpeg" href="imagenes/logo1.jpeg">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  
  <style>
    body { background-color: #f1f5f9; }
    .admin-wrapper { display: flex; min-height: 100vh; }
    .admin-sidebar { width: 260px; flex-shrink: 0; background: #0f172a; }
    .admin-content { flex-grow: 1; min-width: 0; }
    @media (max-width: 991.98px) {
      .admin-sidebar { position: fixed; z-index: 1050; left: -260px; top: 0; bottom: 0; transition: left 0.3s ease; }
      .admin-sidebar.show { left: 0; }
    }
  </style>
</head>
<body>

<?php if (!$is_logged): ?>
  <!-- ===================================================
       PANTALLA DE LOGIN ADMINISTRATIVO
       =================================================== -->
  <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 440px; width: 100%;">
      <div class="bg-primary p-4 text-center text-white">
        <img src="imagenes/logo1.jpeg" alt="Logo City-Park" style="height: 64px; border-radius: 12px;" class="mb-3 shadow-sm">
        <h3 class="fw-bold mb-1">City<span class="text-warning">-Park</span></h3>
        <p class="small text-white-50 mb-0">Panel de Control & Administración</p>
      </div>
      
      <div class="card-body p-4 p-md-5 bg-white">
        <?php if (!empty($login_error)): ?>
          <div class="alert alert-danger alert-dismissible fade show small py-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($login_error) ?>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="admin.php">
          <input type="hidden" name="login_submit" value="1">
          
          <div class="mb-3">
            <label class="form-label small fw-bold">Usuario</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
              <input type="text" name="usuario" class="form-control" placeholder="Ingresa tu usuario" required autofocus>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label small fw-bold">Contraseña</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
              <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar al Panel
          </button>
        </form>

        <div class="text-center mt-4 border-top pt-3">
          <a href="index.php" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Regresar al Sitio Web
          </a>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>

  <!-- ===================================================
       PANEL ADMINISTRATIVO AUTENTICADO
       =================================================== -->
  <div class="admin-wrapper">
    
    <!-- Sidebar de Navegación -->
    <aside class="admin-sidebar p-3 d-flex flex-column justify-content-between" id="adminSidebar">
      <div>
        <!-- Brand -->
        <div class="d-flex align-items-center gap-2 mb-4 px-2 text-white">
          <img src="imagenes/logo1.jpeg" alt="Logo" style="height: 42px; border-radius: 8px;">
          <div>
            <h5 class="fw-bold mb-0 text-white">City<span class="text-warning">-Park</span></h5>
            <span class="badge bg-primary-subtle text-primary small">Administración</span>
          </div>
        </div>

        <!-- Menú de Pestañas -->
        <nav class="nav flex-column" id="adminNavTabs">
          <a class="admin-nav-item active" href="#" data-section="sec-dashboard">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
          <a class="admin-nav-item" href="#" data-section="sec-clientes" onclick="cargarClientesAdmin()">
            <i class="bi bi-people-fill"></i> Clientes
          </a>
          <a class="admin-nav-item" href="#" data-section="sec-ventas" onclick="cargarVentasAdmin()">
            <i class="bi bi-ticket-perforated-fill"></i> Pasaportes Vendidos
          </a>
          <a class="admin-nav-item" href="#" data-section="sec-mensajes" onclick="cargarMensajesAdmin()">
            <i class="bi bi-envelope-paper-fill"></i> Mensajes de Contacto
            <span class="badge bg-danger rounded-pill ms-auto" id="navBadgeMensajes"><?= (int)$total_mensajes_nuevos ?></span>
          </a>
        </nav>
      </div>

      <!-- Pie de Sidebar -->
      <div class="border-top border-secondary pt-3 px-2">
        <div class="d-flex align-items-center justify-content-between text-white-50 mb-3 small">
          <span class="text-truncate" style="max-width: 140px;">
            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['admin_user']) ?>
          </span>
          <a href="logout.php" class="text-danger fw-bold text-decoration-none small" title="Cerrar Sesión">
            <i class="bi bi-power"></i> Salir
          </a>
        </div>
        <a href="index.php" target="_blank" class="btn btn-sm btn-outline-light w-100 rounded-pill">
          <i class="bi bi-box-arrow-up-right me-1"></i> Ver Sitio Web
        </a>
      </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="admin-content d-flex flex-column">
      
      <!-- Top Navbar Admin -->
      <header class="bg-white border-bottom p-3 d-flex align-items-center justify-content-between shadow-sm">
        <div class="d-flex align-items-center gap-3">
          <button class="btn btn-outline-secondary btn-sm d-lg-none" id="btnToggleSidebar">
            <i class="bi bi-list fs-5"></i>
          </button>
          <h5 class="mb-0 fw-bold text-dark" id="currentSectionTitle">Resumen General</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
            <i class="bi bi-check-circle-fill me-1"></i> Sistema Conectado (MySQL)
          </span>
          <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill">
            <i class="bi bi-door-open-fill me-1"></i> Salir
          </a>
        </div>
      </header>

      <!-- Contenedor Dinámico de Secciones -->
      <div class="p-3 p-md-4 flex-grow-1">

        <!-- ==========================================
             1. SECCIÓN: DASHBOARD
             ========================================== -->
        <section id="sec-dashboard" class="admin-section">
          <div class="row g-3 mb-4">
            
            <!-- Clientes -->
            <div class="col-sm-6 col-xl-3">
              <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <span class="text-muted small fw-bold text-uppercase">Clientes</span>
                  <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                  </div>
                </div>
                <h3 class="fw-bold mb-1"><?= number_format($total_clientes, 0) ?></h3>
                <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-short"></i> Registrados en BD</span>
              </div>
            </div>

            <!-- Pasaportes Vendidos -->
            <div class="col-sm-6 col-xl-3">
              <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <span class="text-muted small fw-bold text-uppercase">Órdenes / Ventas</span>
                  <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-ticket-detailed-fill"></i>
                  </div>
                </div>
                <h3 class="fw-bold mb-1"><?= number_format($total_ventas, 0) ?></h3>
                <span class="text-success small fw-semibold">Pases generados</span>
              </div>
            </div>

            <!-- Ingresos COP -->
            <div class="col-sm-6 col-xl-3">
              <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <span class="text-muted small fw-bold text-uppercase">Ingresos Totales</span>
                  <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-cash-stack"></i>
                  </div>
                </div>
                <h3 class="fw-bold mb-1 text-primary"><?= formatearCOP($total_ingresos) ?></h3>
                <span class="text-muted small">Pesos Colombianos</span>
              </div>
            </div>

            <!-- Mensajes Nuevos -->
            <div class="col-sm-6 col-xl-3">
              <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <span class="text-muted small fw-bold text-uppercase">Buzón de Contacto</span>
                  <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="bi bi-envelope-fill"></i>
                  </div>
                </div>
                <h3 class="fw-bold mb-1"><?= number_format($total_mensajes_nuevos, 0) ?></h3>
                <span class="text-danger small fw-semibold">Sin leer</span>
              </div>
            </div>

          </div>

          <!-- Acciones Rápidas -->
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3">Acciones Rápidas del Administrador</h5>
            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-primary rounded-pill" onclick="abrirModalNuevoCliente()">
                <i class="bi bi-person-plus-fill me-1"></i> Registrar Nuevo Cliente
              </button>
              <button class="btn btn-outline-primary rounded-pill" onclick="cambiarSeccion('sec-ventas')">
                <i class="bi bi-ticket-perforated me-1"></i> Ver Pasaportes Vendidos
              </button>
              <button class="btn btn-outline-secondary rounded-pill" onclick="cambiarSeccion('sec-mensajes')">
                <i class="bi bi-chat-dots-fill me-1"></i> Leer Mensajes
              </button>
            </div>
          </div>
        </section>

        <!-- ==========================================
             2. SECCIÓN: GESTIÓN DE CLIENTES (CRUD & REACTIVE FILTER)
             ========================================== -->
        <section id="sec-clientes" class="admin-section" style="display: none;">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            
            <!-- Barra Superior de Clientes con Búsqueda Reactiva -->
            <div class="card-header bg-white p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
              <div>
                <h5 class="fw-bold text-dark mb-0">Gestión de Clientes</h5>
                <p class="text-muted small mb-0">Crea, edita, actualiza y elimina clientes con filtrado reactivo en vivo.</p>
              </div>

              <div class="d-flex align-items-center gap-2">
                <!-- Buscador Reactivo en Vivo 🔍 -->
                <div class="input-group input-group-sm" style="max-width: 280px;">
                  <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                  <input type="text" id="clientLiveSearch" class="form-control" placeholder="Buscar por cédula, nombre, correo...">
                </div>
                <button class="btn btn-sm btn-primary rounded-pill px-3 text-nowrap fw-bold" onclick="abrirModalNuevoCliente()">
                  <i class="bi bi-plus-lg me-1"></i> Nuevo Cliente
                </button>
              </div>
            </div>

            <!-- Tabla Reactiva de Clientes -->
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" id="tablaClientesAdmin">
                <thead class="table-light small text-uppercase">
                  <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Nombres y Apellidos</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección / Ciudad</th>
                    <th>Fecha Registro</th>
                    <th class="text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbodyClientesAdmin">
                  <!-- Llenado dinámico vía AJAX -->
                </tbody>
              </table>
            </div>

            <div class="p-3 bg-light text-center small text-muted" id="emptyClientesNotice" style="display: none;">
              No se encontraron clientes registrados o que coincidan con la búsqueda.
            </div>

            <!-- Paginación de Clientes (5 por página) -->
            <div class="card-footer bg-white p-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" id="clientPaginationContainer" style="display: none;">
              <div class="small text-muted" id="clientPaginationInfo">
                Mostrando <strong>1</strong> a <strong>5</strong> de <strong>5</strong> clientes (5 por página)
              </div>
              <nav aria-label="Paginación de clientes">
                <ul class="pagination pagination-sm mb-0 justify-content-center" id="clientPaginationList">
                  <!-- Botones dinámicos de paginación -->
                </ul>
              </nav>
            </div>
          </div>
        </section>

        <!-- ==========================================
             3. SECCIÓN: PASAPORTES VENDIDOS / VENTAS
             ========================================== -->
        <section id="sec-ventas" class="admin-section" style="display: none;">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            
            <div class="card-header bg-white p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
              <div>
                <h5 class="fw-bold text-dark mb-0">Pasaportes Vendidos & Reservas</h5>
                <p class="text-muted small mb-0">Registro en pesos colombianos ($ COP) con métodos de pago (Efectivo, Débito, Crédito).</p>
              </div>

              <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Filtro Método de Pago -->
                <select id="salesFilterMetodo" class="form-select form-select-sm" style="width: auto;" onchange="cargarVentasAdmin()">
                  <option value="">Todos los Pagos</option>
                  <option value="Efectivo">💵 Efectivo</option>
                  <option value="Tarjeta Débito">💳 Tarjeta Débito</option>
                  <option value="Tarjeta Crédito">💳 Tarjeta Crédito</option>
                </select>

                <!-- Filtro Estado -->
                <select id="salesFilterEstado" class="form-select form-select-sm" style="width: auto;" onchange="cargarVentasAdmin()">
                  <option value="">Todos los Estados</option>
                  <option value="Aprobado">Aprobado</option>
                  <option value="Pendiente">Pendiente</option>
                  <option value="Cancelado">Cancelado</option>
                </select>

                <!-- Buscador Reactivo -->
                <div class="input-group input-group-sm" style="max-width: 240px;">
                  <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                  <input type="text" id="salesLiveSearch" class="form-control" placeholder="Buscar orden o cliente...">
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                  <tr>
                    <th>Código</th>
                    <th>Cliente / Documento</th>
                    <th>Pasaportes & Actividades</th>
                    <th>Total COP</th>
                    <th>Método de Pago</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-end">Ticket</th>
                  </tr>
                </thead>
                <tbody id="tbodyVentasAdmin">
                  <!-- Llenado dinámico -->
                </tbody>
              </table>
            </div>

            <div class="p-3 bg-light text-center small text-muted" id="emptyVentasNotice" style="display: none;">
              No se encontraron pasaportes vendidos con los filtros aplicados.
            </div>
          </div>
        </section>

        <!-- ==========================================
             4. SECCIÓN: MENSAJES DE CONTACTO
             ========================================== -->
        <section id="sec-mensajes" class="admin-section" style="display: none;">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-bold text-dark mb-0">Buzón de Mensajes de Contacto</h5>
                <p class="text-muted small mb-0">Consultas, solicitudes de fiestas y convenios recibidos desde el sitio web.</p>
              </div>
              <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="cargarMensajesAdmin()">
                <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                  <tr>
                    <th>Estado</th>
                    <th>Remitente</th>
                    <th>Contacto</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th class="text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbodyMensajesAdmin">
                  <!-- Llenado dinámico -->
                </tbody>
              </table>
            </div>

            <div class="p-3 bg-light text-center small text-muted" id="emptyMensajesNotice" style="display: none;">
              No hay mensajes de contacto registrados.
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>

  <!-- ===================================================
       MODAL: CREAR / EDITAR CLIENTE (CRUD)
       =================================================== -->
  <div class="modal fade" id="modalClienteForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="modalClienteTitle">Nuevo Cliente</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formClienteCRUD">
          <input type="hidden" name="id" id="clienteId">
          <div class="modal-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label small fw-bold">Documento de Identidad (Cédula) *</label>
                <input type="text" name="documento" id="clienteDocumento" class="form-control" placeholder="Ej: 1094928371" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Nombres *</label>
                <input type="text" name="nombres" id="clienteNombres" class="form-control" placeholder="Ej: Carlos" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Apellidos *</label>
                <input type="text" name="apellidos" id="clienteApellidos" class="form-control" placeholder="Ej: Pérez" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Correo Electrónico *</label>
                <input type="email" name="correo" id="clienteCorreo" class="form-control" placeholder="carlos@correo.com" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Teléfono / Celular *</label>
                <input type="tel" name="telefono" id="clienteTelefono" class="form-control" placeholder="3116859356" required>
              </div>
              <div class="col-md-8">
                <label class="form-label small fw-bold">Dirección *</label>
                <input type="text" name="direccion" id="clienteDireccion" class="form-control" placeholder="Cra 14 # 18-20" required>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-bold">Ciudad</label>
                <input type="text" name="ciudad" id="clienteCiudad" class="form-control" value="Armenia">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary rounded-pill fw-bold">Guardar Cliente</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ===================================================
       MODAL: CONFIRMAR ELIMINACIÓN DE CLIENTE
       =================================================== -->
  <div class="modal fade" id="modalDeleteCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content border-0 shadow-lg text-center p-3">
        <div class="modal-body">
          <div class="fs-1 text-danger mb-2"><i class="bi bi-trash3-fill"></i></div>
          <h5 class="fw-bold">¿Eliminar Cliente?</h5>
          <p class="text-muted small">Esta acción no se puede deshacer y borrará los registros vinculados a este cliente.</p>
          <input type="hidden" id="deleteClienteId">
          <div class="d-flex justify-content-center gap-2 mt-3">
            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger rounded-pill px-3 fw-bold" onclick="confirmarEliminarCliente()">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================
       MODAL: DETALLE DE TICKET VENDIDO
       =================================================== -->
  <div class="modal fade" id="modalAdminTicket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title fw-bold">🎟️ Detalle de Venta & Pasaportes</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" id="adminTicketPrintable">
          <div class="ticket-print-box">
            <div class="ticket-header">
              <img src="imagenes/logo1.jpeg" alt="Logo" style="height: 50px; border-radius: 8px;">
              <h4 class="fw-bold text-primary mt-2 mb-0">City-Park Armenia</h4>
              <p class="text-muted small mb-0">Comprobante de Pasaportes Vendidos</p>
            </div>
            
            <div class="row g-3 mb-3">
              <div class="col-sm-6">
                <span class="text-muted small d-block">Código de Orden:</span>
                <span class="fw-bold text-danger fs-5" id="admTicketOrder">---</span>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-muted small d-block">Fecha:</span>
                <span class="fw-bold text-dark" id="admTicketDate">---</span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Cliente:</span>
                <span class="fw-bold text-dark" id="admTicketClient">---</span>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-muted small d-block">Método de Pago:</span>
                <span class="badge bg-primary fs-6" id="admTicketPayment">---</span>
              </div>
            </div>

            <h6 class="fw-bold text-dark border-top pt-3 mb-2">Ítems / Pasaportes:</h6>
            <ul class="list-group list-group-flush mb-3" id="admTicketItems"></ul>

            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
              <span class="fw-bold fs-5 text-dark">Total Pagado:</span>
              <span class="fw-bold fs-4 text-success" id="admTicketTotal">$0</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary rounded-pill fw-bold" onclick="window.print()">
            <i class="bi bi-printer-fill me-1"></i> Imprimir Ticket
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navegación de pestañas en panel admin
    const navItems = document.querySelectorAll('#adminNavTabs .admin-nav-item');
    const sections = document.querySelectorAll('.admin-section');
    const pageTitle = document.getElementById('currentSectionTitle');

    function cambiarSeccion(targetSectionId) {
      sections.forEach(sec => sec.style.display = 'none');
      navItems.forEach(n => n.classList.remove('active'));

      const targetSec = document.getElementById(targetSectionId);
      if (targetSec) targetSec.style.display = 'block';

      const activeNav = document.querySelector(`#adminNavTabs [data-section="${targetSectionId}"]`);
      if (activeNav) {
        activeNav.classList.add('active');
        pageTitle.textContent = activeNav.textContent.trim().replace(/\d+$/, '');
      }

      // Cargar datos correspondientes
      if (targetSectionId === 'sec-clientes') cargarClientesAdmin();
      if (targetSectionId === 'sec-ventas') cargarVentasAdmin();
      if (targetSectionId === 'sec-mensajes') cargarMensajesAdmin();

      // Cerrar sidebar en móvil
      document.getElementById('adminSidebar').classList.remove('show');
    }

    navItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const secId = item.getAttribute('data-section');
        cambiarSeccion(secId);
      });
    });

    // Toggle Sidebar Móvil
    const btnToggle = document.getElementById('btnToggleSidebar');
    if (btnToggle) {
      btnToggle.addEventListener('click', () => {
        document.getElementById('adminSidebar').classList.toggle('show');
      });
    }

    // ==========================================
    // 1. CRUD & FILTRADO REACTIVO DE CLIENTES (PAGINACIÓN 5 POR PÁGINA)
    // ==========================================
    let listaClientesCache = [];
    let clientListaFiltrada = [];
    let clientCurrentPage = 1;
    const clientPageSize = 5;

    async function cargarClientesAdmin(busqueda = '', mantenerPagina = false) {
      try {
        const res = await fetch(`api.php?action=client_list&q=${encodeURIComponent(busqueda)}`);
        const json = await res.json();
        if (json.success) {
          listaClientesCache = json.data;
          renderTablaClientes(listaClientesCache, mantenerPagina ? clientCurrentPage : 1);
        }
      } catch (e) {
        console.error('Error cargando clientes:', e);
      }
    }

    function renderTablaClientes(clientes, page = 1) {
      clientListaFiltrada = clientes || [];
      const tbody = document.getElementById('tbodyClientesAdmin');
      const emptyNotice = document.getElementById('emptyClientesNotice');
      const paginationContainer = document.getElementById('clientPaginationContainer');
      const paginationInfo = document.getElementById('clientPaginationInfo');
      const paginationList = document.getElementById('clientPaginationList');
      
      if (!clientListaFiltrada || clientListaFiltrada.length === 0) {
        tbody.innerHTML = '';
        emptyNotice.style.display = 'block';
        if (paginationContainer) paginationContainer.style.display = 'none';
        return;
      }

      emptyNotice.style.display = 'none';
      if (paginationContainer) paginationContainer.style.display = 'flex';

      const totalItems = clientListaFiltrada.length;
      const totalPages = Math.ceil(totalItems / clientPageSize);
      clientCurrentPage = Math.max(1, Math.min(page, totalPages));

      const startIndex = (clientCurrentPage - 1) * clientPageSize;
      const endIndex = Math.min(startIndex + clientPageSize, totalItems);
      const pageItems = clientListaFiltrada.slice(startIndex, endIndex);

      let html = '';
      pageItems.forEach(c => {
        html += `
          <tr>
            <td class="text-muted fw-bold">#${c.id}</td>
            <td class="fw-bold text-dark"><i class="bi bi-person-vcard text-primary me-1"></i> ${c.documento}</td>
            <td><strong>${c.nombres} ${c.apellidos}</strong></td>
            <td><a href="mailto:${c.correo}" class="text-decoration-none text-secondary">${c.correo}</a></td>
            <td>${c.telefono}</td>
            <td class="small text-muted">${c.direccion} (${c.ciudad})</td>
            <td class="small text-muted">${c.fecha_registro ? c.fecha_registro.substring(0, 10) : ''}</td>
            <td class="text-end text-nowrap">
              <button class="btn btn-sm btn-outline-primary rounded-pill me-1" onclick="editarCliente(${c.id})" title="Editar Cliente">
                <i class="bi bi-pencil-fill"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="abrirEliminarCliente(${c.id})" title="Eliminar Cliente">
                <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        `;
      });
      tbody.innerHTML = html;

      // Actualizar información de paginación
      if (paginationInfo) {
        paginationInfo.innerHTML = `Mostrando <strong>${startIndex + 1}</strong> a <strong>${endIndex}</strong> de <strong>${totalItems}</strong> clientes (5 por página)`;
      }

      // Renderizar botones de paginación
      if (paginationList) {
        let pagHtml = '';
        
        // Botón Anterior
        pagHtml += `
          <li class="page-item ${clientCurrentPage === 1 ? 'disabled' : ''}">
            <a class="page-link shadow-none" href="#" onclick="cambiarPaginaCliente(${clientCurrentPage - 1}); return false;" aria-label="Anterior">
              &laquo;
            </a>
          </li>
        `;

        // Números de Página
        for (let i = 1; i <= totalPages; i++) {
          pagHtml += `
            <li class="page-item ${i === clientCurrentPage ? 'active' : ''}">
              <a class="page-link shadow-none" href="#" onclick="cambiarPaginaCliente(${i}); return false;">${i}</a>
            </li>
          `;
        }

        // Botón Siguiente
        pagHtml += `
          <li class="page-item ${clientCurrentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link shadow-none" href="#" onclick="cambiarPaginaCliente(${clientCurrentPage + 1}); return false;" aria-label="Siguiente">
              &raquo;
            </a>
          </li>
        `;

        paginationList.innerHTML = pagHtml;
      }
    }

    function cambiarPaginaCliente(nuevaPagina) {
      renderTablaClientes(clientListaFiltrada, nuevaPagina);
    }

    // Filtrado reactivo en tiempo real al escribir en el input de clientes
    const clientSearchInput = document.getElementById('clientLiveSearch');
    if (clientSearchInput) {
      clientSearchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        if (!query) {
          renderTablaClientes(listaClientesCache, 1);
        } else {
          const filtrados = listaClientesCache.filter(c => 
            (c.documento && c.documento.toLowerCase().includes(query)) ||
            (c.nombres && c.nombres.toLowerCase().includes(query)) ||
            (c.apellidos && c.apellidos.toLowerCase().includes(query)) ||
            (c.correo && c.correo.toLowerCase().includes(query)) ||
            (c.telefono && c.telefono.toLowerCase().includes(query))
          );
          renderTablaClientes(filtrados, 1);
        }
      });
    }

    function abrirModalNuevoCliente() {
      document.getElementById('formClienteCRUD').reset();
      document.getElementById('clienteId').value = '';
      document.getElementById('modalClienteTitle').textContent = 'Nuevo Cliente';
      const modal = new bootstrap.Modal(document.getElementById('modalClienteForm'));
      modal.show();
    }

    function editarCliente(id) {
      const c = listaClientesCache.find(item => item.id == id);
      if (!c) return;
      document.getElementById('clienteId').value = c.id;
      document.getElementById('clienteDocumento').value = c.documento;
      document.getElementById('clienteNombres').value = c.nombres;
      document.getElementById('clienteApellidos').value = c.apellidos;
      document.getElementById('clienteCorreo').value = c.correo;
      document.getElementById('clienteTelefono').value = c.telefono;
      document.getElementById('clienteDireccion').value = c.direccion;
      document.getElementById('clienteCiudad').value = c.ciudad;
      document.getElementById('modalClienteTitle').textContent = 'Editar Cliente #' + c.id;
      const modal = new bootstrap.Modal(document.getElementById('modalClienteForm'));
      modal.show();
    }

    // Enviar Guardar / Actualizar Cliente
    document.getElementById('formClienteCRUD').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      formData.append('action', 'client_save');

      try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const json = await res.json();
        if (json.success) {
          bootstrap.Modal.getInstance(document.getElementById('modalClienteForm')).hide();
          cargarClientesAdmin('', true);
          alert(json.message);
        } else {
          alert(json.message || 'Error al guardar cliente.');
        }
      } catch (err) {
        alert('Error de conexión.');
      }
    });

    function abrirEliminarCliente(id) {
      document.getElementById('deleteClienteId').value = id;
      const modal = new bootstrap.Modal(document.getElementById('modalDeleteCliente'));
      modal.show();
    }

    async function confirmarEliminarCliente() {
      const id = document.getElementById('deleteClienteId').value;
      const formData = new FormData();
      formData.append('action', 'client_delete');
      formData.append('id', id);

      try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const json = await res.json();
        if (json.success) {
          bootstrap.Modal.getInstance(document.getElementById('modalDeleteCliente')).hide();
          cargarClientesAdmin('', true);
          alert('Cliente eliminado.');
        } else {
          alert(json.message || 'No se pudo eliminar el cliente.');
        }
      } catch (err) {
        alert('Error al conectar.');
      }
    }

    // ==========================================
    // 2. GESTIÓN DE PASAPORTES VENDIDOS & FILTROS
    // ==========================================
    let listaVentasCache = [];

    async function cargarVentasAdmin() {
      const metodo = document.getElementById('salesFilterMetodo').value;
      const estado = document.getElementById('salesFilterEstado').value;
      const q = document.getElementById('salesLiveSearch').value;

      try {
        const url = `api.php?action=sales_list&metodo=${encodeURIComponent(metodo)}&estado=${encodeURIComponent(estado)}&q=${encodeURIComponent(q)}`;
        const res = await fetch(url);
        const json = await res.json();
        if (json.success) {
          listaVentasCache = json.data;
          renderTablaVentas(listaVentasCache);
        }
      } catch (e) {
        console.error('Error cargando ventas:', e);
      }
    }

    function renderTablaVentas(ventas) {
      const tbody = document.getElementById('tbodyVentasAdmin');
      const emptyNotice = document.getElementById('emptyVentasNotice');

      if (!ventas || ventas.length === 0) {
        tbody.innerHTML = '';
        emptyNotice.style.display = 'block';
        return;
      }

      emptyNotice.style.display = 'none';
      let html = '';
      ventas.forEach(v => {
        let badgePayment = 'bg-secondary';
        if (v.metodo_pago === 'Efectivo') badgePayment = 'bg-success';
        if (v.metodo_pago === 'Tarjeta Débito') badgePayment = 'bg-info text-dark';
        if (v.metodo_pago === 'Tarjeta Crédito') badgePayment = 'bg-primary';

        html += `
          <tr>
            <td><strong class="text-danger">${v.codigo_orden}</strong></td>
            <td>
              <strong>${v.nombres} ${v.apellidos}</strong><br>
              <span class="text-muted small">C.C: ${v.documento}</span>
            </td>
            <td>
              <span class="small fw-semibold text-dark">${v.descripcion_items || 'Pasaporte City-Park'}</span>
            </td>
            <td><strong class="text-success fs-6">${v.total_formateado}</strong></td>
            <td><span class="badge ${badgePayment}">${v.metodo_pago}</span></td>
            <td>
              <select class="form-select form-select-sm" style="width: 110px;" onchange="cambiarEstadoVenta(${v.id}, this.value)">
                <option value="Aprobado" ${v.estado === 'Aprobado' ? 'selected' : ''}>Aprobado</option>
                <option value="Pendiente" ${v.estado === 'Pendiente' ? 'selected' : ''}>Pendiente</option>
                <option value="Cancelado" ${v.estado === 'Cancelado' ? 'selected' : ''}>Cancelado</option>
              </select>
            </td>
            <td class="small text-muted">${v.fecha_formateada}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-dark rounded-pill" onclick="verDetalleVenta(${v.id})" title="Ver e Imprimir Ticket">
                <i class="bi bi-receipt"></i> Ticket
              </button>
            </td>
          </tr>
        `;
      });
      tbody.innerHTML = html;
    }

    // Filtro reactivo en vivo de ventas
    const salesSearchInput = document.getElementById('salesLiveSearch');
    if (salesSearchInput) {
      salesSearchInput.addEventListener('input', () => {
        cargarVentasAdmin();
      });
    }

    async function cambiarEstadoVenta(id, estado) {
      const formData = new FormData();
      formData.append('action', 'sale_update_status');
      formData.append('id', id);
      formData.append('estado', estado);
      try {
        await fetch('api.php', { method: 'POST', body: formData });
      } catch (e) {}
    }

    async function verDetalleVenta(id) {
      try {
        const res = await fetch(`api.php?action=sale_detail&id=${id}`);
        const json = await res.json();
        if (json.success) {
          const v = json.data;
          document.getElementById('admTicketOrder').textContent = v.codigo_orden;
          document.getElementById('admTicketDate').textContent = v.fecha_formateada;
          document.getElementById('admTicketClient').textContent = `${v.nombres} ${v.apellidos} (Doc: ${v.documento})`;
          document.getElementById('admTicketPayment').textContent = v.metodo_pago;
          document.getElementById('admTicketTotal').textContent = v.total_formateado;

          const itemsUl = document.getElementById('admTicketItems');
          let html = '';
          v.detalles.forEach(d => {
            html += `
              <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                <div>
                  <strong>${d.item_nombre}</strong>
                  <span class="text-muted small d-block">${d.cantidad} x ${d.precio_formateado}</span>
                </div>
                <span class="fw-bold">${d.subtotal_formateado}</span>
              </li>
            `;
          });
          itemsUl.innerHTML = html;

          const modal = new bootstrap.Modal(document.getElementById('modalAdminTicket'));
          modal.show();
        }
      } catch (e) {
        alert('Error al obtener detalle de la venta.');
      }
    }

    // ==========================================
    // 3. GESTIÓN DE MENSAJES DE CONTACTO
    // ==========================================
    async function cargarMensajesAdmin() {
      try {
        const res = await fetch('api.php?action=messages_list');
        const json = await res.json();
        if (json.success) {
          renderTablaMensajes(json.data);
        }
      } catch (e) {
        console.error('Error cargando mensajes:', e);
      }
    }

    function renderTablaMensajes(mensajes) {
      const tbody = document.getElementById('tbodyMensajesAdmin');
      const emptyNotice = document.getElementById('emptyMensajesNotice');

      if (!mensajes || mensajes.length === 0) {
        tbody.innerHTML = '';
        emptyNotice.style.display = 'block';
        return;
      }

      emptyNotice.style.display = 'none';
      let html = '';
      let unreadCount = 0;

      mensajes.forEach(m => {
        if (m.leido == 0) unreadCount++;
        html += `
          <tr class="${m.leido == 0 ? 'table-warning-subtle fw-semibold' : ''}">
            <td>
              ${m.leido == 0 
                ? '<span class="badge bg-danger">Nuevo</span>' 
                : '<span class="badge bg-secondary">Leído</span>'}
            </td>
            <td><strong>${m.nombre}</strong></td>
            <td>
              <a href="mailto:${m.correo}" class="d-block small text-decoration-none">${m.correo}</a>
              <span class="text-muted small">${m.telefono || 'Sin tel'}</span>
            </td>
            <td><span class="badge bg-primary-subtle text-primary">${m.asunto}</span></td>
            <td class="small" style="max-width: 300px;">${m.mensaje}</td>
            <td class="small text-muted">${m.fecha_formateada}</td>
            <td class="text-end text-nowrap">
              ${m.leido == 0 ? `
                <button class="btn btn-sm btn-outline-success rounded-pill me-1" onclick="marcarMensajeLeido(${m.id})" title="Marcar como Leído">
                  <i class="bi bi-check2-all"></i>
                </button>
              ` : ''}
              <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminarMensaje(${m.id})" title="Eliminar">
                <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        `;
      });
      tbody.innerHTML = html;
      document.getElementById('navBadgeMensajes').textContent = unreadCount;
    }

    async function marcarMensajeLeido(id) {
      const formData = new FormData();
      formData.append('action', 'message_mark_read');
      formData.append('id', id);
      try {
        await fetch('api.php', { method: 'POST', body: formData });
        cargarMensajesAdmin();
      } catch (e) {}
    }

    async function eliminarMensaje(id) {
      if (!confirm('¿Seguro que deseas eliminar este mensaje?')) return;
      const formData = new FormData();
      formData.append('action', 'message_delete');
      formData.append('id', id);
      try {
        await fetch('api.php', { method: 'POST', body: formData });
        cargarMensajesAdmin();
      } catch (e) {}
    }
  </script>
<?php endif; ?>
</body>
</html>
