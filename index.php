<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>City-Park | Parque Temático Infantil & Educativo en Armenia</title>
  
  <!-- Favicon / Brand -->
  <link rel="icon" type="image/jpeg" href="imagenes/logo1.jpeg">

  <!-- Google Fonts & Bootstrap 5 & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>

  <!-- ===================================================
       1. NAVBAR PRINCIPAL CON MENÚ HAMBURGUESA RESPONSIVE
       =================================================== -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-citypark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#inicio">
        <img src="imagenes/logo1.jpeg" alt="Logo City-Park" class="img-fluid">
        <span class="fw-bold fs-4 text-primary d-none d-sm-inline">City<span class="text-warning">-Park</span></span>
      </a>

      <!-- Botón de Carrito Móvil y Hamburguesa -->
      <div class="d-flex align-items-center gap-2 order-lg-3">
        <button class="btn btn-cart-nav" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-controls="cartDrawer">
          <img src="imagenes/iconcarrito.png" alt="Carrito">
          <span class="d-none d-md-inline">Entradas</span>
          <span class="cart-badge cart-count-badge">0</span>
        </button>
        <a href="admin.php" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold d-none d-xl-inline-flex align-items-center gap-1">
          <i class="bi bi-shield-lock-fill"></i> Admin
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCityPark" aria-controls="navbarCityPark" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- Menú Enlaces de Navegación -->
      <div class="collapse navbar-collapse order-lg-2 justify-content-center" id="navbarCityPark">
        <ul class="navbar-nav mb-2 mb-lg-0 py-2 py-lg-0 text-center">
          <li class="nav-item">
            <a class="nav-link active" href="#inicio">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pasaportes">Pasaportes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#catalogo">Atracciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#experiencias">Experiencias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#nosotros">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contacto">Contacto</a>
          </li>
          <li class="nav-item d-xl-none mt-2">
            <a href="admin.php" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
              <i class="bi bi-shield-lock-fill"></i> Panel Administrativo
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ===================================================
       2. HERO SECTION / INICIO
       =================================================== -->
  <section id="inicio" class="hero-section mt-5 pt-5">
    <div class="hero-overlay"></div>
    <div class="container position-relative py-lg-5">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <div class="d-inline-flex align-items-center gap-2 mb-3">
            <span class="badge-tag">
              <i class="bi bi-star-fill text-warning"></i> Parque Temático Infantil Pionero en el Eje Cafetero
            </span>
          </div>
          <h1 class="hero-title mb-3">
            Donde los niños exploran sus sueños y aprenden <span class="text-warning">jugando a ser adultos</span>
          </h1>
          <p class="hero-subtitle mb-4">
            City-Park es el entorno interactivo más seguro y divertido de Armenia, Quindío. Diseñado para que los pequeños descubran sus vocaciones en mini-ciudades llenas de magia y aprendizaje ciudadano.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="#pasaportes" class="btn btn-warning btn-lg fw-bold rounded-pill px-4 shadow-lg text-dark">
              <i class="bi bi-ticket-perforated-fill me-1"></i> Comprar Pasaportes
            </a>
            <a href="#catalogo" class="btn btn-outline-light btn-lg fw-bold rounded-pill px-4">
              <i class="bi bi-compass-fill me-1"></i> Explorar Atracciones
            </a>
          </div>
          <div class="row mt-4 pt-3 text-white-50 border-top border-white-25 g-3">
            <div class="col-auto d-flex align-items-center gap-2">
              <i class="bi bi-shield-check text-warning fs-5"></i>
              <span class="text-white small fw-semibold">100% Seguro y Supervisado</span>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
              <i class="bi bi-award-fill text-warning fs-5"></i>
              <span class="text-white small fw-semibold">+10 Profesiones Reales</span>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
              <i class="bi bi-coin text-warning fs-5"></i>
              <span class="text-white small fw-semibold">Economía Lúdica City-Coins</span>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="hero-img-box">
            <img src="imagenes/ninosjugando.jpeg" alt="Niños jugando en City-Park" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       3. SECCIÓN DE PASAPORTES OFICIALES
       =================================================== -->
  <section id="pasaportes" class="py-5 bg-light">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Entradas Oficiales</span>
        <h2 class="section-title">Elige el Pasaporte Ideal</h2>
        <p class="section-desc">Pases diseñados para que cada niño disfrute al máximo su día de diversión, vocaciones y aventura en City-Park.</p>
      </div>

      <div class="row g-4 align-items-stretch">
        <!-- 1. Pasaporte Junior -->
        <div class="col-md-6 col-lg-3">
          <div class="passport-card">
            <div class="passport-icon">🎟️</div>
            <h3 class="passport-name">Pasaporte Junior</h3>
            <p class="passport-desc">Ideal para primeras visitas y explorar vocaciones favoritas.</p>
            
            <div class="passport-price-box">
              <div class="passport-price">$35.000</div>
              <div class="passport-period">Por niño / Visita de 2 horas</div>
            </div>

            <ul class="passport-features">
              <li><span class="feature-check">✓</span> Acceso a 4 estaciones de profesiones</li>
              <li><span class="feature-check">✓</span> Carnet de ciudadano City-Park</li>
              <li><span class="feature-check">✓</span> 50 City-Coins de bienvenida</li>
              <li><span class="feature-check">✓</span> Guías y capacitadores lúdicos</li>
              <li><span class="feature-cross">✕</span> Refrigerio Kids incluido</li>
              <li><span class="feature-cross">✕</span> Simulador de aviación VIP</li>
            </ul>

            <button class="btn btn-outline-primary btn-passport-buy" onclick="CityParkCart.addItem('pas_junior', 'Pasaporte Junior', 35000, 'Pasaporte', '🎟️')">
              ➕ Comprar Junior
            </button>
          </div>
        </div>

        <!-- 2. Pasaporte Estrella (Recomendado) -->
        <div class="col-md-6 col-lg-3">
          <div class="passport-card featured">
            <span class="passport-badge">⭐ Recomendado</span>
            <div class="passport-icon">🌟</div>
            <h3 class="passport-name">Pasaporte Estrella</h3>
            <p class="passport-desc">La experiencia más completa de aprendizaje y recreación.</p>
            
            <div class="passport-price-box bg-warning-subtle border-warning-subtle">
              <div class="passport-price text-dark">$48.000</div>
              <div class="passport-period">Por niño / Visita de 3.5 horas</div>
            </div>

            <ul class="passport-features">
              <li><span class="feature-check">✓</span> Acceso a 8 estaciones de profesiones</li>
              <li><span class="feature-check">✓</span> Carnet plastificado oficial</li>
              <li><span class="feature-check">✓</span> 100 City-Coins para compras lúdicas</li>
              <li><span class="feature-check">✓</span> Refrigerio Kids (Jugo natural + Snack)</li>
              <li><span class="feature-check">✓</span> Diploma de honor junior</li>
              <li><span class="feature-cross">✕</span> Simulador de aviación VIP</li>
            </ul>

            <button class="btn btn-warning btn-passport-buy text-dark fw-bold shadow-sm" onclick="CityParkCart.addItem('pas_estrella', 'Pasaporte Estrella', 48000, 'Pasaporte', '🌟')">
              ➕ Comprar Estrella
            </button>
          </div>
        </div>

        <!-- 3. Pasaporte Total VIP -->
        <div class="col-md-6 col-lg-3">
          <div class="passport-card">
            <span class="passport-badge vip">👑 Acceso Total VIP</span>
            <div class="passport-icon">👑</div>
            <h3 class="passport-name">Pasaporte Total VIP</h3>
            <p class="passport-desc">Pase libre a todas las atracciones sin límites de estaciones.</p>
            
            <div class="passport-price-box">
              <div class="passport-price text-indigo" style="color: #4f46e5;">$65.000</div>
              <div class="passport-period">Por niño / Pase ilimitado 4 horas</div>
            </div>

            <ul class="passport-features">
              <li><span class="feature-check">✓</span> Acceso Ilimitado a todas las profesiones</li>
              <li><span class="feature-check">✓</span> Simulador de Vuelo y Pilotos de Avión</li>
              <li><span class="feature-check">✓</span> Zona de Mascotas & Clínica Veterinaria</li>
              <li><span class="feature-check">✓</span> Combo Restaurante Kids Gourmet</li>
              <li><span class="feature-check">✓</span> Carnet Dorado VIP + Gorro temático</li>
              <li><span class="feature-check">✓</span> 150 City-Coins de bienvenida</li>
            </ul>

            <button class="btn btn-primary btn-passport-buy" onclick="CityParkCart.addItem('pas_vip', 'Pasaporte Total VIP', 65000, 'Pasaporte', '👑')">
              🎟️ Comprar VIP Ilimitado
            </button>
          </div>
        </div>

        <!-- 4. Combo Familiar -->
        <div class="col-md-6 col-lg-3">
          <div class="passport-card">
            <span class="passport-badge family">👨‍👩‍👧‍👦 Ahorro Familiar</span>
            <div class="passport-icon">🎪</div>
            <h3 class="passport-name">Combo Familiar</h3>
            <p class="passport-desc">El plan perfecto para 2 niños y 2 adultos acompañantes.</p>
            
            <div class="passport-price-box">
              <div class="passport-price text-success">$110.000</div>
              <div class="passport-period">Pase familiar completo / 4 horas</div>
            </div>

            <ul class="passport-features">
              <li><span class="feature-check">✓</span> Pases para 2 Niños con acceso total</li>
              <li><span class="feature-check">✓</span> Entrada para 2 Adultos acompañantes</li>
              <li><span class="feature-check">✓</span> 2 Combos Restaurante Kids Gourmet</li>
              <li><span class="feature-check">✓</span> 200 City-Coins compartidas</li>
              <li><span class="feature-check">✓</span> Zona de descanso preferencial con Wi-Fi</li>
            </ul>

            <button class="btn btn-success btn-passport-buy" onclick="CityParkCart.addItem('combo_familiar', 'Combo Familiar (2 Niños + 2 Adultos)', 110000, 'Combo', '🎪')">
              ➕ Comprar Familiar
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       4. CATÁLOGO DE PASES Y ATRACCIONES CON FILTRO REACTIVO
       =================================================== -->
  <section id="catalogo" class="py-5">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Nuestras Experiencias</span>
        <h2 class="section-title">Catálogo de Pases y Atracciones</h2>
        <p class="section-desc">Filtra por categorías o escribe el nombre de la actividad para encontrar la diversión perfecta.</p>
      </div>

      <!-- Barra de búsqueda reactiva 🔍 -->
      <div class="catalog-search-bar">
        <span class="fs-5 me-2 text-muted">🔍</span>
        <input type="text" id="catalogSearchInput" placeholder="Escribe el nombre de la atracción o profesión..." aria-label="Buscar atracción">
      </div>

      <!-- Pestañas de categorías reactivas -->
      <div class="filter-pills">
        <button class="filter-btn active" data-filter="all">✨ Todas</button>
        <button class="filter-btn" data-filter="pasaportes">🛂 Pasaportes Oficiales</button>
        <button class="filter-btn" data-filter="profesiones">👮 Profesiones</button>
        <button class="filter-btn" data-filter="comidas">🍔 Zona de Comidas</button>
        <button class="filter-btn" data-filter="fiestas">🎉 Fiestas Infantiles</button>
        <button class="filter-btn" data-filter="mascotas">🐾 Zona de Mascotas</button>
      </div>

      <!-- Grilla de Atracciones -->
      <div class="row g-4" id="attractionsGrid">
        
        <!-- Bombero -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Estación de Bomberos Kids" data-desc="Simulador de emergencias, camión interactivo, rescates y trabajo en equipo.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen3bombero.jpeg" alt="Bombero Kids">
              <span class="attraction-badge">👮 Profesiones</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Estación de Bomberos Kids</h4>
              <p class="attraction-desc">Simulador de rescate y emergencias. Los niños visten uniforme oficial, abordan el camión de bomberos y apagan incendios interactivos.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$20.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_bombero', 'Estación de Bomberos Kids', 20000, 'Atracción', '🚒')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Mini Chef -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones comidas" data-title="Mini Chef Gourmet" data-desc="Taller de cocina divertida, preparación de pizzas, cupcakes y recetas saludables.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen1chef.jpeg" alt="Mini Chef">
              <span class="attraction-badge">👨‍🍳 Gastronomía</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Mini Chef Gourmet</h4>
              <p class="attraction-desc">Laboratorio gastronómico infantil donde aprenden técnicas culinarias, higiene alimentaria y preparan deliciosas recetas.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$22.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_chef', 'Mini Chef Gourmet', 22000, 'Atracción', '👨‍🍳')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Médico & Hospital -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Hospital & Urgencias Médicas" data-desc="Clínica infantil, primeros auxilios, anatomía divertida y cuidado de pacientes.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen2medico.jpeg" alt="Médico Infantil">
              <span class="attraction-badge">🩺 Salud</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Hospital & Urgencias Médicas</h4>
              <p class="attraction-desc">Experiencia médica interactiva: sala de cirugía lúdica, diagnóstico con rayos X y consultorio pediátrico.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$20.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_medico', 'Hospital & Urgencias Médicas', 20000, 'Atracción', '🩺')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Policía -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Academia de Policía & Seguridad" data-desc="Entrenamiento cívico, resolución de misterios y patrullaje de la ciudad.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen4policia.jpeg" alt="Policía">
              <span class="attraction-badge">👮 Seguridad</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Academia de Policía & Seguridad</h4>
              <p class="attraction-desc">Los niños aprenden valores de convivencia, normas viales y resuelven divertidas misiones de detectives.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$18.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_policia', 'Academia de Policía & Seguridad', 18000, 'Atracción', '👮')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Banquero & City Coins -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Banco Central City-Coins" data-desc="Educación financiera lúdica, gestión de ahorros, cajeros y cheques de ciudad.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen5banquero.jpeg" alt="Banquero">
              <span class="attraction-badge">💰 Finanzas Kids</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Banco Central City-Coins</h4>
              <p class="attraction-desc">Aprender a ahorrar, invertir y administrar el dinero mientras manejan cajas registradoras y bóvedas de seguridad.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$18.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_banco', 'Banco Central City-Coins', 18000, 'Atracción', '💰')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Panadero -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones comidas" data-title="Panadería & Pastelería Mágica" data-desc="Amasado artesanal, horneado seguro de galletas y decoración con toppings.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen6panadero.jpeg" alt="Panadero">
              <span class="attraction-badge">🥐 Panadería</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Panadería & Pastelería Mágica</h4>
              <p class="attraction-desc">Masa, harina y creatividad. Cada niño hornea sus propios panes y galletitas que luego pueden degustar en familia.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$20.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_panadero', 'Panadería & Pastelería Mágica', 20000, 'Atracción', '🥐')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Piloto de Avión VIP -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Simulador de Vuelo & Pilotos VIP" data-desc="Cabina de vuelo realista con controles de mando, despegue y aterrizaje en 3D.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen7piloto.jpeg" alt="Piloto">
              <span class="attraction-badge">✈️ Aviación VIP</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Simulador de Vuelo & Pilotos VIP</h4>
              <p class="attraction-desc">Una experiencia de alta tecnología donde toman el timón de un avión comercial y vuelan sobre paisajes virtuales.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$25.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_piloto', 'Simulador de Vuelo VIP', 25000, 'Atracción', '✈️')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Fiestas Infantiles -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="fiestas" data-title="Paquete Cumpleaños VIP City-Park" data-desc="Salón privado temático, pastel, animador, pases ilimitados y show de bienvenida.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/eventoseducativos.jpeg" alt="Fiestas Infantiles">
              <span class="attraction-badge">🎉 Fiestas VIP</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Paquete Cumpleaños VIP City-Park</h4>
              <p class="attraction-desc">Celebración inolvidable para hasta 15 niños con decoración temática exclusiva, anfitrión de actividades y pastel.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$380.000 <small class="text-muted fs-6">/ paquete</small></span>
                <button class="btn btn-sm btn-success rounded-pill px-3" onclick="CityParkCart.addItem('pkg_cumpleanos', 'Paquete Cumpleaños VIP (15 Niños)', 380000, 'Fiestas', '🎉')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Zona de Mascotas & Veterinaria -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="mascotas profesiones" data-title="Clínica Veterinaria & Mascotas" data-desc="Cuidado responsable, chequeos médicos caninos y peluquería lúdica de mascotas.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/arteycreatividad.jpeg" alt="Veterinaria">
              <span class="attraction-badge">🐾 Mascotas</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Clínica Veterinaria & Mascotas</h4>
              <p class="attraction-desc">Fomenta la empatía animal, chequeo de signos vitales en peluches interactivos y adopción simbólica.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$20.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_veterinaria', 'Clínica Veterinaria & Mascotas', 20000, 'Atracción', '🐾')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pasaporte Oficial Directo -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="pasaportes" data-title="Pasaporte Oficial Estrella" data-desc="Acceso a 8 estaciones con refrigerio kids y 100 City-Coins.">
          <div class="attraction-card border-warning">
            <div class="attraction-img-wrap">
              <img src="imagenes/diadelasprofeciones.jpeg" alt="Día de Profesiones">
              <span class="attraction-badge bg-warning text-dark">🛂 Pasaporte Oficial</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Pasaporte Oficial Estrella</h4>
              <p class="attraction-desc">Pase de 3.5 horas con acceso a 8 estaciones, carnet plastificado y refrigerio saludable.</p>
              <div class="attraction-footer">
                <span class="attraction-price text-dark">$48.000</span>
                <button class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3" onclick="CityParkCart.addItem('pas_estrella', 'Pasaporte Estrella', 48000, 'Pasaporte', '🌟')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Combo Gourmet Restaurante -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="comidas" data-title="Combo Restaurante Kids Gourmet" data-desc="Almuerzo infantil saludable: mini hamburguesa artesanal o nuggets con jugo natural.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/imagen1chef.jpeg" alt="Combo Comida">
              <span class="attraction-badge">🍔 Restaurante</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Combo Restaurante Kids Gourmet</h4>
              <p class="attraction-desc">Delicioso menú balanceado preparado en el restaurante temático para reponer energías.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$18.000</span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('comida_kids', 'Combo Restaurante Kids Gourmet', 18000, 'Alimentos', '🍔')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Taller de Pequeños Científicos -->
        <div class="col-md-6 col-lg-4 attraction-item-col" data-category="profesiones" data-title="Laboratorio de Pequeños Científicos" data-desc="Experimentos químicos inofensivos, microscopios y robótica divertida.">
          <div class="attraction-card">
            <div class="attraction-img-wrap">
              <img src="imagenes/pequenoscientificos.jpeg" alt="Pequeños Científicos">
              <span class="attraction-badge">🔬 Ciencia</span>
            </div>
            <div class="attraction-body">
              <h4 class="attraction-title">Laboratorio de Pequeños Científicos</h4>
              <p class="attraction-desc">Descubrimiento guiado por instructores lúdicos para despertar el ingenio y el método científico.</p>
              <div class="attraction-footer">
                <span class="attraction-price">$20.000 <small class="text-muted fs-6">/ pase individual</small></span>
                <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="CityParkCart.addItem('atr_ciencia', 'Laboratorio de Pequeños Científicos', 20000, 'Atracción', '🔬')">
                  + Agregar
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Notificación si la búsqueda reactiva no encuentra resultados -->
      <div id="catalogEmptyNotice" class="text-center py-5" style="display: none;">
        <i class="bi bi-search fs-1 text-muted"></i>
        <h4 class="mt-3 fw-bold">No se encontraron atracciones</h4>
        <p class="text-muted">Intenta buscar con otra palabra clave o selecciona otra categoría.</p>
      </div>
    </div>
  </section>

  <!-- ===================================================
       5. SECCIÓN DE NUESTRAS EXPERIENCIAS (GALERÍA)
       =================================================== -->
  <section id="experiencias" class="py-5 bg-light">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Galería de Momentos</span>
        <h2 class="section-title">Nuestras Experiencias</h2>
        <p class="section-desc">Un vistazo a las actividades cotidianas, talleres creativos y la alegría que se vive a diario en las calles de City-Park.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/arteycreatividad.jpeg" alt="Arte y Creatividad">
            <div class="gallery-overlay">
              <h5 class="gallery-title">🎨 Arte y Creatividad Sin Límites</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/pequenoscientificos.jpeg" alt="Pequeños Científicos">
            <div class="gallery-overlay">
              <h5 class="gallery-title">🔬 Curiosidad y Experimentos Lúdicos</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/diadelasprofeciones.jpeg" alt="Día de las Profesiones">
            <div class="gallery-overlay">
              <h5 class="gallery-title">👔 Día Oficial de las Profesiones</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/equipotrabajando.jpeg" alt="Equipo Trabajando">
            <div class="gallery-overlay">
              <h5 class="gallery-title">🤝 Guías y Monitores Especializados</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/eventoseducativos.jpeg" alt="Eventos Educativos">
            <div class="gallery-overlay">
              <h5 class="gallery-title">🎓 Visitas Pedagógicas y Colegios</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="gallery-card">
            <img src="imagenes/trabajoenequipo.jpeg" alt="Trabajo en Equipo">
            <div class="gallery-overlay">
              <h5 class="gallery-title">🌟 Formando Ciudadanos del Mañana</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       6. EXPERIENCIAS REALES / TESTIMONIOS
       =================================================== -->
  <section class="py-5">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Experiencias Reales</span>
        <h2 class="section-title">Lo que Opinan las Familias</h2>
        <p class="section-desc">Cientos de padres y niños comparten sus momentos mágicos vividos en City-Park.</p>
      </div>

      <div class="row g-4">
        <!-- Carlos P. -->
        <div class="col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-quote">
              "A mi hijo le fascinó ser bombero por un día. El realismo del camión y los uniformes es increíble. Es un lugar educativo, seguro y supremamente divertido."
            </p>
            <div class="testimonial-author">
              <img src="imagenes/testimonio1.jpeg" alt="Carlos P." class="testimonial-avatar">
              <div>
                <h5 class="author-name">Carlos P.</h5>
                <p class="author-role">Padre de Familia</p>
              </div>
            </div>
          </div>
        </div>

        <!-- María G. -->
        <div class="col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-quote">
              "Una experiencia fantástica. Mis dos niñas pasaron por medicina, mini chef y la panadería. Salieron felices y con ganas de volver cada fin de semana."
            </p>
            <div class="testimonial-author">
              <img src="imagenes/testimonio2.jpeg" alt="María G." class="testimonial-avatar">
              <div>
                <h5 class="author-name">María G.</h5>
                <p class="author-role">Visitante Frecuente</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Juan L. -->
        <div class="col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-quote">
              "El mejor plan familiar en Armenia. Todo está impecablemente organizado y los guías tienen una paciencia y cariño maravilloso con los niños."
            </p>
            <div class="testimonial-author">
              <img src="imagenes/testimonio3.jpeg" alt="Juan L." class="testimonial-avatar">
              <div>
                <h5 class="author-name">Juan L.</h5>
                <p class="author-role">Cliente Habitual</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       7. DUDAS Y CONSULTAS / PREGUNTAS FRECUENTES
       =================================================== -->
  <section class="py-5 bg-light">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Dudas y Consultas</span>
        <h2 class="section-title">Preguntas Frecuentes</h2>
        <p class="section-desc">Todo lo que necesitas saber antes de planear tu visita a City-Park.</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div class="accordion accordion-citypark" id="accordionFAQ">
            
            <!-- FAQ 1 -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  ¿Para qué edades está recomendado City-Park?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                <div class="accordion-body">
                  Nuestras actividades y profesiones están diseñadas especialmente para niños de 3 a 14 años. También contamos con zonas blandas de estimulación para los más pequeños acompañados de sus padres.
                </div>
              </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  ¿Los adultos deben pagar entrada?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                <div class="accordion-body">
                  Los adultos acompañantes pagan una tarifa preferencial mínima de ingreso que incluye acceso a zonas de descanso con Wi-Fi, cafetería y visualización de todas las atracciones.
                </div>
              </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  ¿Cómo funciona la compra y reserva de boletos online?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                <div class="accordion-body">
                  Puedes agregar las entradas deseadas al carrito interactivo, ingresar tus datos en el formulario de checkout y confirmar la compra. Al llegar a taquilla sólo debes presentar tu número de cédula o pedido.
                </div>
              </div>
            </div>

            <!-- FAQ 4 -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  ¿Se pueden celebrar fiestas de cumpleaños en City-Park?
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                <div class="accordion-body">
                  ¡Claro que sí! Ofrecemos paquetes VIP con salón privado decorado, anfitrión, pastel temático, pases ilimitados y actividades exclusivas. Puedes cotizar en la sección de Contacto o añadir el paquete de fiesta directamente al carrito.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       8. SECCIÓN QUIÉNES SOMOS & EQUIPO FUNDADOR
       =================================================== -->
  <section id="nosotros" class="py-5">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Nuestra Identidad</span>
        <h2 class="section-title">Construyendo Sueños en Miniatura</h2>
        <p class="section-desc">Somos el parque temático educativo pionero en el Eje Cafetero, donde los niños juegan a ser adultos a través de profesiones interactivas. Fomentamos la creatividad, el trabajo en equipo y los valores ciudadanos en un entorno seguro y mágico.</p>
      </div>

      <div class="row g-4 mb-5">
        <!-- Misión -->
        <div class="col-md-6">
          <div class="about-feature-box">
            <h3 class="fw-bold text-primary mb-3">🎯 Nuestra Misión</h3>
            <p class="text-secondary">
              Nuestra misión es brindar a los niños un espacio donde puedan aprender y divertirse al mismo tiempo, explorando diferentes profesiones a través del juego constructivo.
            </p>
            <p class="text-secondary mb-0">
              Buscamos estimular su creatividad, curiosidad y desarrollo personal mediante experiencias interactivas que les permitan descubrir sus vocaciones, habilidades y talentos innatos desde temprana edad.
            </p>
          </div>
        </div>

        <!-- Visión -->
        <div class="col-md-6">
          <div class="about-feature-box orange">
            <h3 class="fw-bold text-danger mb-3">🚀 Nuestra Visión</h3>
            <p class="text-secondary">
              Aspiramos a ser el máximo referente nacional en educación lúdica y entretenimiento infantil, expandiendo nuestras ciudades interactivas para inspirar a más de 500.000 niños cada año.
            </p>
            <p class="text-secondary mb-0">
              Nos proyectamos como un ecosistema seguro, innovador e inclusivo que prepare a las nuevas generaciones para construir un futuro con liderazgo, empatía y compromiso social.
            </p>
          </div>
        </div>
      </div>

      <!-- Historia -->
      <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
          <div class="pe-lg-3">
            <span class="section-tag">Nuestras Raíces</span>
            <h3 class="fw-bold text-dark mb-3">Nuestra Historia</h3>
            <p class="text-secondary">
              Nuestros inicios nacieron de un sueño compartido: crear un espacio donde el juego infantil fuera una herramienta transformadora de aprendizaje. Al abrir nuestras puertas, la emoción de ver a los niños vistiendo trajes de bomberos, médicos, pilotos y chefs confirmó que este proyecto tenía un impacto real en sus vidas.
            </p>
            <p class="text-secondary mb-0">
              Con el apoyo de las familias y colegios de Armenia y la región, expandimos nuestras instalaciones sumando la zona gastronómica, salones temáticos para fiestas y la clínica veterinaria para mascotas.
            </p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="rounded-4 overflow-hidden shadow-lg border border-3 border-white">
            <img src="imagenes/fundadores.jpeg" alt="Fundadores de City-Park" class="img-fluid w-100" style="max-height: 350px; object-fit: cover;">
          </div>
        </div>
      </div>

      <!-- Equipo Fundador -->
      <div class="section-header mt-5">
        <span class="section-tag">Liderazgo y Pasión</span>
        <h3 class="section-title fs-2">Equipo Fundador</h3>
        <p class="section-desc">Las personas detrás de la visión y crecimiento de City-Park.</p>
      </div>

      <div class="row g-4">
        <!-- Kevin Gómez -->
        <div class="col-md-4">
          <div class="team-card">
            <div class="team-img-wrap">
              <img src="imagenes/quienessomoskevin.jpeg" alt="Kevin Gómez">
            </div>
            <div class="team-body">
              <h4 class="team-name">Kevin Gómez</h4>
              <p class="team-role">Co-Fundador & Director Digital</p>
              <p class="team-desc">Lidera la estrategia tecnológica, desarrollo de la plataforma web y la proyección de marca de City-Park.</p>
            </div>
          </div>
        </div>

        <!-- Sara Echeverry -->
        <div class="col-md-4">
          <div class="team-card">
            <div class="team-img-wrap">
              <img src="imagenes/quinessomossara.jpeg" alt="Sara Echeverry">
            </div>
            <div class="team-body">
              <h4 class="team-name">Sara Echeverry</h4>
              <p class="team-role">Co-Fundadora & Directora Financiera</p>
              <p class="team-desc">Encargada de la sostenibilidad económica, administración y alianzas estratégicas del parque.</p>
            </div>
          </div>
        </div>

        <!-- Yeison González -->
        <div class="col-md-4">
          <div class="team-card">
            <div class="team-img-wrap">
              <img src="imagenes/quienessomosyeison.jpeg" alt="Yeison González">
            </div>
            <div class="team-body">
              <h4 class="team-name">Yeison González</h4>
              <p class="team-role">Co-Fundador & Director de Operaciones</p>
              <p class="team-desc">Responsable de la logística integral, seguridad de las atracciones y excelencia en la experiencia de visita.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ===================================================
       9. SECCIÓN DE CONTACTO & UBICACIÓN
       =================================================== -->
  <section id="contacto" class="py-5 bg-light">
    <div class="container py-lg-4">
      <div class="section-header">
        <span class="section-tag">Contáctanos</span>
        <h2 class="section-title">Estamos Listos para Atenderte</h2>
        <p class="section-desc">Escríbenos para reservas corporativas, fiestas infantiles, visitas escolares o dudas sobre tus pasaportes.</p>
      </div>

      <div class="row g-5">
        <!-- Información de Contacto -->
        <div class="col-lg-5">
          <div class="contact-info-card">
            <h4 class="fw-bold text-dark mb-4">Información del Parque</h4>
            
            <div class="contact-item">
              <div class="contact-icon">📍</div>
              <div>
                <h6 class="fw-bold mb-1">Visítanos</h6>
                <p class="text-muted small mb-0">Av. Centenario, Carrera 6 # 20-15<br>Armenia, Quindío, Colombia</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">⏰</div>
              <div>
                <h6 class="fw-bold mb-1">Horarios de Atención</h6>
                <p class="text-muted small mb-0">Mar - Dom: 9:00 am - 6:00 pm<br><span class="text-warning fw-semibold">Lunes: Mantenimiento</span></p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">📞</div>
              <div>
                <h6 class="fw-bold mb-1">Líneas Telefónicas</h6>
                <p class="text-muted small mb-0">+57 311 685 9356<br>+57 304 676 7956</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">✉️</div>
              <div>
                <h6 class="fw-bold mb-1">Correos Electrónicos</h6>
                <p class="text-muted small mb-0">contacto@citypark.com<br>reservas@citypark.com</p>
              </div>
            </div>

            <h6 class="fw-bold mt-4 mb-2">Síguenos en Redes Sociales</h6>
            <div class="social-links">
              <a href="https://facebook.com" target="_blank" class="social-btn" title="Facebook">
                <img src="imagenes/facebookimagen.jpeg" alt="Facebook">
              </a>
              <a href="https://instagram.com" target="_blank" class="social-btn" title="Instagram">
                <img src="imagenes/instagramimagen.jpeg" alt="Instagram">
              </a>
              <a href="https://wa.me/573116859356" target="_blank" class="social-btn" title="WhatsApp">
                <img src="imagenes/whassapimagen.jpeg" alt="WhatsApp">
              </a>
            </div>
          </div>
        </div>

        <!-- Formulario de Contacto en BD -->
        <div class="col-lg-7">
          <div class="bg-white p-4 p-md-5 rounded-4 shadow border">
            <h4 class="fw-bold text-dark mb-3">Envíanos un Mensaje</h4>
            <p class="text-muted small mb-4">Los mensajes se registran directamente en el panel administrativo de City-Park.</p>
            
            <form id="contactForm">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold">Nombre Completo *</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Ej: Marcela Castro" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold">Correo Electrónico *</label>
                  <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold">Teléfono / WhatsApp</label>
                  <input type="tel" name="telefono" class="form-control" placeholder="Ej: 311 123 4567">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold">Asunto *</label>
                  <select name="asunto" class="form-select" required>
                    <option value="Consulta General">Consulta General</option>
                    <option value="Cotización Fiesta Cumpleaños">Cotización Fiesta de Cumpleaños</option>
                    <option value="Visita Escolar / Colegio">Visita Escolar o Colegio</option>
                    <option value="Soporte de Pasaportes">Soporte con Pasaportes</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label small fw-bold">Mensaje *</label>
                  <textarea name="mensaje" rows="4" class="form-control" placeholder="Escribe aquí los detalles de tu consulta..." required></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                    <i class="bi bi-send-fill me-2"></i> Enviar Mensaje
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       10. FOOTER
       =================================================== -->
  <footer class="footer-citypark">
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="imagenes/logo1.jpeg" alt="Logo" style="height: 48px; border-radius: 8px;">
            <h4 class="text-white fw-bold mb-0">City<span class="text-warning">-Park</span></h4>
          </div>
          <p class="small text-secondary">
            El parque temático infantil interactivo líder en entretenimiento y educación. Donde los niños exploran sus sueños, vocaciones y aprenden jugando a ser adultos en un entorno mágico y 100% seguro.
          </p>
        </div>

        <div class="col-6 col-lg-2">
          <h5>Explorar</h5>
          <ul class="footer-links">
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#pasaportes">Pasaportes Oficiales</a></li>
            <li><a href="#catalogo">Atracciones</a></li>
            <li><a href="#experiencias">Experiencias Especiales</a></li>
            <li><a href="#nosotros">Quiénes Somos</a></li>
            <li><a href="#contacto">Contáctanos</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-3">
          <h5>Visítanos</h5>
          <p class="small mb-1 text-light">📍 <strong>Dirección:</strong></p>
          <p class="small text-secondary mb-3">Av. Centenario, Carrera 6 # 20-15<br>Armenia, Quindío, Colombia</p>
          <p class="small mb-1 text-light">⏰ <strong>Horarios:</strong></p>
          <p class="small text-secondary mb-0">Mar - Dom: 9:00 am - 6:00 pm<br>Lunes: Mantenimiento</p>
        </div>

        <div class="col-lg-3">
          <h5>Contacto</h5>
          <p class="small mb-1">📞 +57 311 685 9356</p>
          <p class="small mb-1">📱 +57 304 676 7956</p>
          <p class="small mb-1">✉️ contacto@citypark.com</p>
          <p class="small mb-3">🎟️ reservas@citypark.com</p>
          <div class="d-flex gap-2">
            <a href="admin.php" class="btn btn-sm btn-outline-secondary rounded-pill">
              <i class="bi bi-shield-lock me-1"></i> Acceso Admin
            </a>
          </div>
        </div>
      </div>

      <div class="pt-4 border-top border-secondary text-center d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="small mb-0">© 2026 City-Park. Todos los derechos reservados. Desarrollado con pasión educativa.</p>
        <div class="d-flex gap-3">
          <a href="#" class="small text-secondary text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">Política de Privacidad</a>
          <a href="#" class="small text-secondary text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">Términos del Servicio</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ===================================================
       11. OFFCANVAS / DRAWER DEL CARRITO DE COMPRAS & CHECKOUT
       =================================================== -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel" style="width: 440px; max-width: 95vw;">
    <div class="offcanvas-header bg-primary text-white">
      <h5 class="offcanvas-title fw-bold" id="cartDrawerLabel">
        🎟️ Tus Entradas (<span class="cart-count-badge">0</span>)
      </h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-direction-column flex-column">
      
      <!-- Lista de Ítems -->
      <div id="cartItemsList" class="flex-grow-0 mb-3"></div>

      <!-- Estado Vacío -->
      <div id="cartEmptyState" class="text-center py-5">
        <div class="fs-1 mb-2">🎟️</div>
        <h5 class="fw-bold">Tu carrito está vacío</h5>
        <p class="text-muted small">Selecciona pasaportes o atracciones del catálogo para comenzar tu reserva.</p>
        <a href="#pasaportes" class="btn btn-sm btn-warning rounded-pill fw-bold" data-bs-dismiss="offcanvas">Ver Pasaportes</a>
      </div>

      <!-- Sección de Checkout y Formulario de Cliente -->
      <div id="cartFormSection" style="display: none;">
        <!-- Resumen de Costos en COP -->
        <div class="bg-light p-3 rounded-3 border mb-3">
          <div class="d-flex justify-content-between text-muted small mb-1">
            <span>Subtotal</span>
            <span id="cartSubtotal" class="fw-semibold text-dark">$0</span>
          </div>
          <div class="d-flex justify-content-between text-muted small mb-2">
            <span>Servicio / Emisión Digital</span>
            <span class="text-success fw-semibold">Gratis</span>
          </div>
          <div class="d-flex justify-content-between fs-5 fw-bold text-dark pt-2 border-top">
            <span>Total a Pagar</span>
            <span id="cartTotal" class="text-primary">$0</span>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-2">⚡ Datos de Compra y Reserva</h6>
        
        <form id="checkoutForm">
          <div class="mb-2">
            <label class="form-label small fw-bold mb-1">Cédula / Documento del Titular *</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white"><i class="bi bi-person-vcard text-primary"></i></span>
              <input type="text" name="documento" id="checkoutDoc" class="form-control" placeholder="Ej: 1094928371" required autocomplete="off">
            </div>
            <div id="clientSearchStatus" class="mt-1 small" style="display: none;"></div>
          </div>
          
          <div class="mb-2">
            <label class="form-label small fw-bold mb-1">Nombre Completo *</label>
            <input type="text" name="nombre_completo" id="checkoutNombre" class="form-control form-control-sm" placeholder="Ej: Carlos Pérez" required>
          </div>

          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label small fw-bold mb-1">Teléfono *</label>
              <input type="tel" name="telefono" id="checkoutTel" class="form-control form-control-sm" placeholder="Ej: 3116859356" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold mb-1">Correo *</label>
              <input type="email" name="correo" id="checkoutEmail" class="form-control form-control-sm" placeholder="carlos@correo.com" required>
            </div>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-bold mb-1">Dirección / Ciudad *</label>
            <input type="text" name="direccion" id="checkoutDir" class="form-control form-control-sm" placeholder="Cra 14 # 18-20, Armenia" required>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold mb-1">Método de Pago *</label>
            <select name="metodo_pago" class="form-select form-select-sm" required>
              <option value="Efectivo">💵 Efectivo en Taquilla</option>
              <option value="Tarjeta Débito">💳 Tarjeta Débito</option>
              <option value="Tarjeta Crédito">💳 Tarjeta Crédito</option>
            </select>
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="acceptTerms" required checked>
            <label class="form-check-label small text-muted" for="acceptTerms">
              Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Términos y Condiciones</a>
            </label>
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow">
            Confirmar Compra
          </button>
        </form>
      </div>

    </div>
  </div>

  <!-- ===================================================
       12. MODAL DE CONFIRMACIÓN & COMPROBANTE TICKET
       =================================================== -->
  <div class="modal fade" id="ticketConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title fw-bold">🎉 ¡Compra y Reserva Exitosa!</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" id="printableTicket">
          <div class="ticket-print-box">
            <div class="ticket-header">
              <img src="imagenes/logo1.jpeg" alt="Logo" style="height: 55px; border-radius: 8px;">
              <h4 class="fw-bold text-primary mt-2 mb-0">City-Park Armenia</h4>
              <p class="text-muted small mb-0">Comprobante Oficial de Reserva & Pasaportes</p>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-sm-6">
                <span class="text-muted small d-block">Código de Orden / Reserva:</span>
                <span class="fw-bold text-danger fs-5" id="ticketOrderCode">CP-2026-0000</span>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-muted small d-block">Fecha y Hora:</span>
                <span class="fw-bold text-dark" id="ticketDate">--/--/----</span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Titular de la Entrada:</span>
                <span class="fw-bold text-dark" id="ticketClientName">---</span> (<span id="ticketClientDoc">---</span>)
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-muted small d-block">Contacto:</span>
                <span class="text-dark small" id="ticketClientEmail">---</span> | <span class="text-dark small" id="ticketClientPhone">---</span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Método de Pago:</span>
                <span class="badge bg-primary fs-6" id="ticketPaymentMethod">Efectivo</span>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-muted small d-block">Total Pagado:</span>
                <span class="fw-bold text-success fs-4" id="ticketTotal">$0</span>
              </div>
            </div>

            <h6 class="fw-bold text-dark border-top pt-3 mb-2">Desglose de Pasaportes y Actividades:</h6>
            <ul class="list-group list-group-flush mb-3" id="ticketItemsList"></ul>

            <div class="bg-light p-3 rounded text-center small text-muted">
              📌 <strong>Instrucciones para el día de visita:</strong> Presenta este código en la taquilla de City-Park (Av. Centenario, Cra 6 # 20-15) para reclamar tus brazaletes y City-Coins de bienvenida.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary rounded-pill fw-bold" onclick="window.print()">
            <i class="bi bi-printer-fill me-1"></i> Imprimir Comprobante
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ===================================================
       13. MODAL DE TÉRMINOS Y CONDICIONES
       =================================================== -->
  <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold">Términos y Condiciones</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 small text-secondary">
          <h6 class="fw-bold text-dark">1. Condiciones de Ingreso y Uso</h6>
          <p>Los pasaportes adquiridos en City-Park son válidos para la fecha acordada o hasta 30 días posteriores a la fecha de compra en caso de reprogramación avisada con al menos 24 horas de anticipación.</p>
          
          <h6 class="fw-bold text-dark">2. Acompañamiento de Menores</h6>
          <p>Todo menor de edad debe estar supervisado por al menos un adulto responsable dentro de las instalaciones del parque.</p>
          
          <h6 class="fw-bold text-dark">3. Moneda Lúdica City-Coins</h6>
          <p>Las City-Coins son fichas lúdicas sin valor monetario de curso legal fuera del parque, diseñadas exclusivamente para actividades didácticas.</p>
          
          <h6 class="fw-bold text-dark">4. Política de Cancelación y Reembolso</h6>
          <p>Se admiten cambios de fecha sin recargo. Para reembolsos aplican retenciones por costos administrativos de acuerdo con la normatividad colombiana de protección al consumidor.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Entendido</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>
</html>
