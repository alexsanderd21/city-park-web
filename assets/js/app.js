/**
 * City-Park - Frontend JavaScript
 * Lógica del Carrito, Filtros Reactivos de Atracciones, Checkout y Contacto
 */

// Formateador de moneda en Pesos Colombianos
function formatCOP(num) {
  return '$' + Number(num).toLocaleString('es-CO');
}

// Clase / Objeto Carrito
const CityParkCart = {
  items: [],

  init() {
    try {
      const stored = localStorage.getItem('citypark_cart');
      this.items = stored ? JSON.parse(stored) : [];
    } catch (e) {
      this.items = [];
    }
    this.render();
  },

  save() {
    localStorage.setItem('citypark_cart', JSON.stringify(this.items));
    this.render();
  },

  addItem(id, nombre, precio, tipo = 'Pasaporte', icono = '🎟️') {
    const existingIndex = this.items.findIndex(item => item.id === id);
    if (existingIndex > -1) {
      this.items[existingIndex].cantidad += 1;
    } else {
      this.items.push({
        id,
        nombre,
        precio: parseFloat(precio),
        tipo,
        icono,
        cantidad: 1
      });
    }
    this.save();
    this.showToast(`¡"${nombre}" agregado a tus entradas!`);
    
    // Abrir drawer del carrito
    const cartDrawerEl = document.getElementById('cartDrawer');
    if (cartDrawerEl && typeof bootstrap !== 'undefined') {
      const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(cartDrawerEl);
      bsOffcanvas.show();
    }
  },

  updateQty(id, delta) {
    const item = this.items.find(i => i.id === id);
    if (!item) return;
    item.cantidad += delta;
    if (item.cantidad <= 0) {
      this.items = this.items.filter(i => i.id !== id);
    }
    this.save();
  },

  removeItem(id) {
    this.items = this.items.filter(i => i.id !== id);
    this.save();
  },

  clear() {
    this.items = [];
    this.save();
  },

  getTotalCount() {
    return this.items.reduce((acc, curr) => acc + curr.cantidad, 0);
  },

  getTotalAmount() {
    return this.items.reduce((acc, curr) => acc + (curr.precio * curr.cantidad), 0);
  },

  render() {
    const badgeElements = document.querySelectorAll('.cart-count-badge');
    const totalCount = this.getTotalCount();
    const totalAmount = this.getTotalAmount();

    badgeElements.forEach(badge => {
      badge.textContent = totalCount;
    });

    const itemsContainer = document.getElementById('cartItemsList');
    const subtotalEl = document.getElementById('cartSubtotal');
    const totalEl = document.getElementById('cartTotal');
    const emptyStateEl = document.getElementById('cartEmptyState');
    const formSectionEl = document.getElementById('cartFormSection');

    if (itemsContainer) {
      if (this.items.length === 0) {
        itemsContainer.innerHTML = '';
        if (emptyStateEl) emptyStateEl.style.display = 'block';
        if (formSectionEl) formSectionEl.style.display = 'none';
      } else {
        if (emptyStateEl) emptyStateEl.style.display = 'none';
        if (formSectionEl) formSectionEl.style.display = 'block';

        let html = '';
        this.items.forEach(item => {
          const itemSubtotal = item.precio * item.cantidad;
          html += `
            <div class="cart-item-row">
              <div class="d-flex align-items-center gap-2 flex-grow-1">
                <span class="fs-4">${item.icono || '🎟️'}</span>
                <div>
                  <h6 class="mb-0 fw-bold text-dark fs-6">${item.nombre}</h6>
                  <span class="text-muted small">${formatCOP(item.precio)} c/u</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center bg-light rounded p-1 border">
                  <button type="button" class="cart-qty-btn" onclick="CityParkCart.updateQty('${item.id}', -1)">-</button>
                  <span class="px-2 fw-bold text-dark">${item.cantidad}</span>
                  <button type="button" class="cart-qty-btn" onclick="CityParkCart.updateQty('${item.id}', 1)">+</button>
                </div>
                <div class="text-end" style="min-width: 85px;">
                  <span class="fw-bold text-primary">${formatCOP(itemSubtotal)}</span>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="CityParkCart.removeItem('${item.id}')" title="Eliminar">
                  ✕
                </button>
              </div>
            </div>
          `;
        });
        itemsContainer.innerHTML = html;
      }
    }

    if (subtotalEl) subtotalEl.textContent = formatCOP(totalAmount);
    if (totalEl) totalEl.textContent = formatCOP(totalAmount);
  },

  showToast(message) {
    let toastContainer = document.getElementById('cityparkToastContainer');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'cityparkToastContainer';
      toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      toastContainer.style.zIndex = '1090';
      document.body.appendChild(toastContainer);
    }

    const toastId = 'toast_' + Date.now();
    const toastEl = document.createElement('div');
    toastEl.id = toastId;
    toastEl.className = 'toast align-items-center text-white bg-dark border-0 shadow-lg';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body fw-semibold">
          ✨ ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;
    toastContainer.appendChild(toastEl);
    const bsToast = new bootstrap.Toast(toastEl, { delay: 3500 });
    bsToast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
  }
};

// ==========================================
// FILTRADO REACTIVO DEL CATÁLOGO DE ATRACCIONES
// ==========================================
function initCatalogFilter() {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const searchInput = document.getElementById('catalogSearchInput');
  const attractionCards = document.querySelectorAll('.attraction-item-col');
  const emptyResultsEl = document.getElementById('catalogEmptyNotice');

  let currentCategory = 'all';
  let currentSearch = '';

  function applyFilter() {
    let visibleCount = 0;

    attractionCards.forEach(card => {
      const category = card.getAttribute('data-category') || '';
      const title = (card.getAttribute('data-title') || '').toLowerCase();
      const desc = (card.getAttribute('data-desc') || '').toLowerCase();

      const matchesCategory = (currentCategory === 'all' || category === currentCategory);
      const matchesSearch = (currentSearch === '' || title.includes(currentSearch) || desc.includes(currentSearch));

      if (matchesCategory && matchesSearch) {
        card.style.display = 'block';
        card.classList.add('fade-in');
        visibleCount++;
      } else {
        card.style.display = 'none';
        card.classList.remove('fade-in');
      }
    });

    if (emptyResultsEl) {
      emptyResultsEl.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  }

  filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.getAttribute('data-filter') || 'all';
      applyFilter();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      currentSearch = e.target.value.toLowerCase().trim();
      applyFilter();
    });
  }
}

// ==========================================
// PROCESAMIENTO DE CHECKOUT
// ==========================================
function initCheckoutForm() {
  const form = document.getElementById('checkoutForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (CityParkCart.items.length === 0) {
      alert('Tu carrito está vacío. Agrega al menos un pasaporte para continuar.');
      return;
    }

    const btnSubmit = form.querySelector('button[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Procesando...`;

    const formData = new FormData(form);
    formData.append('action', 'checkout');
    formData.append('items', JSON.stringify(CityParkCart.items));

    try {
      const response = await fetch('api.php', {
        method: 'POST',
        body: formData
      });

      const res = await response.json();

      if (res.success) {
        // Cerrar carrito
        const cartDrawerEl = document.getElementById('cartDrawer');
        if (cartDrawerEl && typeof bootstrap !== 'undefined') {
          const bsOffcanvas = bootstrap.Offcanvas.getInstance(cartDrawerEl);
          if (bsOffcanvas) bsOffcanvas.hide();
        }

        // Vaciar carrito
        CityParkCart.clear();
        form.reset();

        // Mostrar Modal de Ticket / Comprobante
        showTicketModal(res.data);
      } else {
        alert(res.message || 'Error al procesar la reserva.');
      }
    } catch (err) {
      console.error(err);
      alert('Ocurrió un error al conectar con el servidor.');
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = originalText;
    }
  });
}

// Mostrar Ticket de Compra
function showTicketModal(data) {
  const modalEl = document.getElementById('ticketConfirmModal');
  if (!modalEl) return;

  document.getElementById('ticketOrderCode').textContent = data.codigo_orden;
  document.getElementById('ticketClientName').textContent = data.cliente.nombre_completo;
  document.getElementById('ticketClientDoc').textContent = data.cliente.documento;
  document.getElementById('ticketClientEmail').textContent = data.cliente.correo;
  document.getElementById('ticketClientPhone').textContent = data.cliente.telefono;
  document.getElementById('ticketPaymentMethod').textContent = data.metodo_pago;
  document.getElementById('ticketTotal').textContent = data.total_formateado;
  document.getElementById('ticketDate').textContent = data.fecha;

  const itemsListEl = document.getElementById('ticketItemsList');
  if (itemsListEl) {
    let html = '';
    data.items.forEach(it => {
      html += `
        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
          <div>
            <span class="fw-bold">${it.nombre}</span>
            <span class="text-muted small d-block">Cantidad: ${it.cantidad} x ${formatCOP(it.precio)}</span>
          </div>
          <span class="fw-bold text-dark">${formatCOP(it.precio * it.cantidad)}</span>
        </li>
      `;
    });
    itemsListEl.innerHTML = html;
  }

  const bsModal = new bootstrap.Modal(modalEl);
  bsModal.show();
}

// ==========================================
// FORMULARIO DE CONTACTO
// ==========================================
function initContactForm() {
  const contactForm = document.getElementById('contactForm');
  if (!contactForm) return;

  contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = contactForm.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Enviando...`;

    const formData = new FormData(contactForm);
    formData.append('action', 'contacto');

    try {
      const response = await fetch('api.php', {
        method: 'POST',
        body: formData
      });
      const res = await response.json();

      if (res.success) {
        CityParkCart.showToast(res.message);
        contactForm.reset();
      } else {
        alert(res.message || 'No fue posible enviar el mensaje.');
      }
    } catch (err) {
      console.error(err);
      alert('Error al enviar el mensaje.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  });
}

// ==========================================
// BÚSQUEDA REACTIVA DE CLIENTE EN CHECKOUT
// ==========================================
function initClientSearchInCheckout() {
  const docInput = document.getElementById('checkoutDoc');
  const statusEl = document.getElementById('clientSearchStatus');
  const nombreInput = document.getElementById('checkoutNombre');
  const telInput = document.getElementById('checkoutTel');
  const emailInput = document.getElementById('checkoutEmail');
  const dirInput = document.getElementById('checkoutDir');

  if (!docInput) return;

  let debounceTimer = null;

  async function buscarCliente(doc) {
    if (!doc || doc.length < 3) {
      if (statusEl) statusEl.style.display = 'none';
      return;
    }

    if (statusEl) {
      statusEl.style.display = 'block';
      statusEl.innerHTML = `<span class="text-muted"><span class="spinner-border spinner-border-sm me-1" style="width: 12px; height: 12px;"></span> Verificando cliente...</span>`;
    }

    try {
      const res = await fetch(`api.php?action=find_client&documento=${encodeURIComponent(doc)}`);
      const json = await res.json();

      if (json.success && json.data) {
        const c = json.data;
        if (nombreInput) nombreInput.value = c.nombre_completo || (c.nombres + ' ' + (c.apellidos || '')).trim();
        if (telInput) telInput.value = c.telefono || '';
        if (emailInput) emailInput.value = c.correo || '';
        if (dirInput) dirInput.value = c.direccion || '';

        if (statusEl) {
          statusEl.style.display = 'block';
          statusEl.innerHTML = `<div class="p-1 px-2 rounded bg-success-subtle text-success border border-success-subtle fw-semibold small"><i class="bi bi-check-circle-fill me-1"></i> ¡Cliente registrado encontrado! Datos autocompletados.</div>`;
        }
      } else {
        if (statusEl) {
          statusEl.style.display = 'block';
          statusEl.innerHTML = `<div class="p-1 px-2 rounded bg-primary-subtle text-primary border border-primary-subtle fw-semibold small"><i class="bi bi-person-plus-fill me-1"></i> Cliente nuevo: Ingresa tus datos para registrarte.</div>`;
        }
      }
    } catch (e) {
      if (statusEl) statusEl.style.display = 'none';
    }
  }

  docInput.addEventListener('input', (e) => {
    clearTimeout(debounceTimer);
    const val = e.target.value.trim();
    debounceTimer = setTimeout(() => {
      buscarCliente(val);
    }, 350);
  });

  docInput.addEventListener('blur', (e) => {
    const val = e.target.value.trim();
    buscarCliente(val);
  });
}

// Auto inicio al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
  CityParkCart.init();
  initCatalogFilter();
  initCheckoutForm();
  initContactForm();
  initClientSearchInCheckout();

  // Cerrar navbar en móviles al hacer clic en un link
  const navLinks = document.querySelectorAll('.navbar-collapse .nav-link');
  const navCollapse = document.getElementById('navbarCityPark');
  if (navCollapse && navLinks) {
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
          const bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
          if (bsCollapse) bsCollapse.hide();
        }
      });
    });
  }
});
