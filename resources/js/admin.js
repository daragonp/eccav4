// Importa el nuevo archivo de mejoras para modales
import './modal-enhancements.js';

// --- El resto de tu código existente en admin.js ---

// JS por delegación para Sidebar, Menú usuario, modales y alertas
document.addEventListener('click', (e) => {
  const t = e.target;

  // --- Sidebar ---
  if (t.closest('#toggleSidebar')) {
    document.querySelector('.panel-sidebar')?.classList.toggle('hidden');
  }

  // --- User menu ---
  if (t.closest('.panel-topbar .btn.btn-ghost') && !t.closest('#themeToggle')) {
    document.getElementById('userMenu')?.classList.toggle('hidden');
  }

  // --- Tailwind modal open ---
  const openBtn = t.closest('[data-modal-open]');
  if (openBtn) {
    e.preventDefault();
    e.stopPropagation();

    const id = openBtn.getAttribute('data-modal-open');
    const modal = document.getElementById(id);

    if (modal) {
      document.body.appendChild(modal);
      document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
      document.getElementById('backdrop')?.classList.add('open');
      modal.classList.add('open');
    }
  }

  // --- Logout button ---
  if (t.closest('#logoutBtn')) {
    e.preventDefault();
    e.stopPropagation();
    const modal = document.getElementById('logoutModal');
    if (modal) {
      document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
      document.getElementById('backdrop')?.classList.add('open');
      modal.classList.add('open');
    }
  }

  // --- Tailwind modal close ---
  if (t.id === 'backdrop' || t.closest('[data-modal-close]')) {
    document.getElementById('backdrop')?.classList.remove('open');
    document.querySelectorAll('.tw-modal').forEach(m => m.classList.remove('open'));
    document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
  }

  // === Bootstrap-like (sin Bootstrap JS) ===
  const bsOpenBtn = t.closest('[data-bs-toggle="modal"]');
  if (bsOpenBtn) {
    e.preventDefault();
    e.stopPropagation();
    const targetSel = bsOpenBtn.getAttribute('data-bs-target');
    if (targetSel) {
      document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
      document.getElementById('backdrop')?.classList.add('open');
      document.querySelector(targetSel)?.classList.add('bs-open');
    }
  }

  if (
    t.closest('[data-bs-dismiss="modal"]') ||
    t.closest('[data-dismiss="modal"]') ||
    t.closest('.modal .close') ||
    t.closest('.modal .btn-close')
  ) {
    document.getElementById('backdrop')?.classList.remove('open');
    document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
  }

  // --- Cerrar alertas ---
  if (t.closest('[data-close]')) {
    const alert = t.closest('.alert');
    if (alert) alert.remove();
  }
});

// === Dark / Light mode (VERSIÓN MEJORADA) ===
(function themeInit(){
  const root = document.documentElement;
  const themePreference = window.matchMedia('(prefers-color-scheme: dark)');
  
  // Función para aplicar el tema
  function applyTheme(theme) {
    if (theme === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    updateThemeIcon();
  }
  
  // Función para actualizar el icono del tema
  function updateThemeIcon() {
    const btn = document.getElementById('themeToggle');
    if (!btn) return;
    
    // El icono se maneja ahora con clases en el HTML
    // No necesitamos manipularlo con JS
  }
  
  // Detectar preferencia del sistema si no hay tema guardado
  const saved = localStorage.getItem('theme');
  if (saved) {
    applyTheme(saved);
  } else if (themePreference.matches) {
    applyTheme('dark');
  } else {
    applyTheme('light');
  }
  
  // Escuchar cambios en la preferencia del sistema
  themePreference.addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });
  
  // Manejar clic en el botón de tema
  const btn = document.getElementById('themeToggle');
  if (btn) {
    btn.addEventListener('click', () => {
      const isDark = root.classList.contains('dark');
      applyTheme(isDark ? 'light' : 'dark');
      localStorage.setItem('theme', isDark ? 'light' : 'dark');
    }, { passive: true });
  }
})();

// === Inicialización de DataTables (CORREGIDO) ===
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar todas las tablas generadas por Laravel DataTables
  const tables = document.querySelectorAll('table[id$="-table"]');
  tables.forEach(table => {
    const tableId = table.id;
    
    // Verificar si ya está inicializada
    if ($.fn.DataTable.isDataTable(table)) {
      // Si ya está inicializada, solo recargar los datos
      $(table).DataTable().ajax.reload();
    } else {
      // Inicializar con configuración básica
      $(table).DataTable({
        responsive: true,
        language: {
          url: asset('js/datatables-es.json')
        },
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 10,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        initComplete: function() {
          // Aplicar estilos a los controles de DataTables
          $("#" + tableId + "_length select, #" + tableId + "_filter input").addClass("form-control");
        },
        error: function(xhr, error, code) {
          console.error('Error en DataTable:', error, code);
          console.error('Respuesta del servidor:', xhr.responseText);
          
          // Mostrar un mensaje de error más detallado
          const errorMsg = xhr.responseJSON?.message || 'Error al cargar los datos';
          showAlert('Error: ' + errorMsg, 'danger');
        }
      });
    }
  });
});

// === Manejo de formularios AJAX ===
document.addEventListener('submit', function(e) {
  const form = e.target;
  
  // Verificar si el formulario tiene el atributo data-ajax
  if (form.hasAttribute('data-ajax')) {
    e.preventDefault();
    
    const url = form.getAttribute('action');
    const method = form.getAttribute('method') || 'POST';
    const formData = new FormData(form);
    
    fetch(url, {
      method: method,
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Mostrar mensaje de éxito
        showAlert(data.message || 'Operación realizada correctamente', 'success');
        
        // Cerrar modal si está abierto
        const modal = form.closest('.tw-modal');
        if (modal) {
          modal.classList.remove('open');
          document.getElementById('backdrop')?.classList.remove('open');
        }
        
        // Recargar la tabla si existe
        const tableId = form.getAttribute('data-table');
        if (tableId) {
          const table = document.getElementById(tableId);
          if (table && $.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().ajax.reload();
          }
        }
      } else {
        // Mostrar mensaje de error
        showAlert(data.message || 'Ha ocurrido un error', 'danger');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showAlert('Ha ocurrido un error en la solicitud', 'danger');
    });
  }
});

// === Función para mostrar alertas ===
function showAlert(message, type = 'info') {
  const alertContainer = document.querySelector('.p-4.lg\\:p-6.space-y-4');
  if (!alertContainer) return;
  
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.innerHTML = `
    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
    ${message}
  `;
  
  // Insertar al principio del contenedor
  alertContainer.insertBefore(alert, alertContainer.firstChild);
  
  // Auto-cerrar después de 5 segundos
  setTimeout(() => {
    if (alert.parentNode) {
      alert.remove();
    }
  }, 5000);
}

// === Función para confirmar acciones ===
function confirmAction(message, callback) {
  if (confirm(message)) {
    callback();
  }
}

// === Manejo de pestañas ===
document.addEventListener('click', function(e) {
  const tabButton = e.target.closest('[data-tab]');
  if (tabButton) {
    const tabId = tabButton.getAttribute('data-tab');
    const tabContent = document.getElementById(tabId);
    
    if (tabContent) {
      // Desactivar todas las pestañas
      document.querySelectorAll('[data-tab]').forEach(btn => {
        btn.classList.remove('active');
      });
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      
      // Activar la pestaña seleccionada
      tabButton.classList.add('active');
      tabContent.classList.add('active');
    }
  }
});

// === Manejo de tooltips ===
document.addEventListener('DOMContentLoaded', function() {
  const tooltipElements = document.querySelectorAll('[data-tooltip]');
  tooltipElements.forEach(element => {
    element.addEventListener('mouseenter', function() {
      const tooltipText = this.getAttribute('data-tooltip');
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = tooltipText;
      
      document.body.appendChild(tooltip);
      
      const rect = this.getBoundingClientRect();
      tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
      tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
      
      this.tooltip = tooltip;
    });
    
    element.addEventListener('mouseleave', function() {
      if (this.tooltip) {
        this.tooltip.remove();
        this.tooltip = null;
      }
    });
  });
});

// === Manejo de notificaciones ===
function showNotification(message, type = 'info', duration = 5000) {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;
  
  document.body.appendChild(notification);
  
  // Animación de entrada
  setTimeout(() => {
    notification.classList.add('show');
  }, 10);
  
  // Auto-cerrar
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 300);
  }, duration);
}

// === Inicialización cuando el DOM está listo ===
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar componentes aquí si es necesario
  console.log('Dashboard inicializado correctamente');
});

// Funcionalidad para el menú lateral
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const sidebarCollapsed = document.getElementById('sidebar-collapsed');
  const toggleSidebarBtn = document.getElementById('toggleSidebar');
  const userMenuBtn = document.getElementById('userMenuBtn');
  const userMenu = document.getElementById('userMenu');
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogout = document.getElementById('cancelLogout');
  const backdrop = document.getElementById('backdrop');
  
  let sidebarOpen = true;
  let collapsedOpen = false;
  
  // Toggle sidebar
  toggleSidebarBtn.addEventListener('click', function() {
    sidebarOpen = !sidebarOpen;
    
    if (window.innerWidth < 768) {
      // Para pantallas pequeñas: mostrar/ocultar sidebar completo
      if (sidebarOpen) {
        sidebar.classList.add('open');
        sidebarCollapsed.classList.add('-translate-x-full');
      } else {
        sidebar.classList.remove('open');
      }
    } else {
      // Para pantallas grandes: colapsar/expandir sidebar
      if (sidebarOpen) {
        sidebar.classList.remove('collapsed');
        sidebarCollapsed.classList.add('-translate-x-full');
        collapsedOpen = false;
      } else {
        sidebar.classList.add('collapsed');
        sidebarCollapsed.classList.remove('-translate-x-full');
        collapsedOpen = true;
      }
    }
  });
  
  // Toggle user menu
  userMenuBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    // Si el sidebar está colapsado, también abrir el menú de usuario
    if (!sidebarOpen && window.innerWidth >= 768) {
      userMenu.classList.toggle('hidden');
    } else {
      userMenu.classList.toggle('hidden');
    }
  });
  
  // Close user menu when clicking outside
  document.addEventListener('click', function() {
    userMenu.classList.add('hidden');
  });
  
  // Logout functionality
  logoutBtn.addEventListener('click', function(e) {
    e.preventDefault();
    userMenu.classList.add('hidden');
    logoutModal.classList.add('open');
    backdrop.classList.add('open');
  });
  
  cancelLogout.addEventListener('click', function() {
    logoutModal.classList.remove('open');
    backdrop.classList.remove('open');
  });
  
  // Close modals when clicking backdrop
  backdrop.addEventListener('click', function() {
    logoutModal.classList.remove('open');
    backdrop.classList.remove('open');
  });
  
  // Handle responsive sidebar
  function handleResponsiveSidebar() {
    if (window.innerWidth < 768) {
      // Pantallas pequeñas
      sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-40', 'transform', '-translate-x-full');
      sidebar.classList.remove('collapsed');
      sidebarCollapsed.classList.add('hidden');
      
      if (sidebarOpen) {
        sidebar.classList.add('open');
      }
    } else {
      // Pantallas grandes
      sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-40', 'transform', '-translate-x-full', 'open');
      sidebarCollapsed.classList.remove('hidden');
      
      if (!sidebarOpen) {
        sidebar.classList.add('collapsed');
        sidebarCollapsed.classList.remove('-translate-x-full');
        collapsedOpen = true;
      } else {
        sidebar.classList.remove('collapsed');
        sidebarCollapsed.classList.add('-translate-x-full');
        collapsedOpen = false;
      }
    }
  }
  
  // Initial check and add resize listener
  handleResponsiveSidebar();
  window.addEventListener('resize', handleResponsiveSidebar);
});

// Solución para errores de DataTables (CORREGIDO)
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las tablas con clase 'datatable' o con ID que termina en '-table'
    const tables = document.querySelectorAll('.datatable, table[id$="-table"]');
    tables.forEach(table => {
        if ($.fn.DataTable.isDataTable(table)) {
            // Si ya está inicializada, solo recargar los datos
            $(table).DataTable().ajax.reload();
        } else {
            // Inicializar con configuración básica
            $(table).DataTable({
                responsive: true,
                language: {
                    url: asset('js/datatables-es.json')
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                initComplete: function() {
                    // Aplicar estilos a los controles de DataTables
                    const tableId = table.id;
                    $("#" + tableId + "_length select, #" + tableId + "_filter input").addClass("form-control");
                },
                error: function(xhr, error, code) {
                    console.error('Error en DataTable:', error, code);
                    console.error('Respuesta del servidor:', xhr.responseText);
                    
                    // Mostrar un mensaje de error más detallado
                    const errorMsg = xhr.responseJSON?.message || 'Error al cargar los datos';
                    showAlert('Error: ' + errorMsg, 'danger');
                }
            });
        }
    });
});