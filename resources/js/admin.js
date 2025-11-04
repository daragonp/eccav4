/* ================================================
   ADMIN.JS - Dashboard JavaScript
   ================================================ */


// Script para prevenir parpadeo del tema y configurar icono inicial
(function() {
  const savedTheme = localStorage.getItem('theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }

  if (!savedTheme) {
    localStorage.setItem('theme', theme);
  }

  const themeIcon = document.getElementById('themeIcon');
  if (themeIcon) {
    themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
  }
})();


// ============================================
// DELEGACIÓN DE EVENTOS
// ============================================
document.addEventListener('click', (e) => {
  const t = e.target.closest ? e.target : null;

  /* --- User menu --- */
  if (t && t.closest('#userMenuBtn')) {
    e.preventDefault();
    e.stopPropagation();
    toggleUserMenu();
    return;
  }

  /* --- Tailwind modal open --- */
  const openBtn = t && t.closest('[data-modal-open]');
  if (openBtn) {
    e.preventDefault();
    e.stopPropagation();

    const id = openBtn.getAttribute('data-modal-open');
    const modal = document.getElementById(id);

    if (modal) {
      document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
      document.getElementById('backdrop')?.classList.add('open');
      modal.classList.add('open');
      console.log('Modal abierto:', id);
    }
  }

  /* --- Logout button --- */
  if (t && t.closest('#logoutBtn')) {
    e.preventDefault();
    e.stopPropagation();

    const modal = document.getElementById('logoutModal');
    if (modal) {
      document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
      document.getElementById('backdrop')?.classList.add('open');
      modal.classList.add('open');
    }
  }

  /* --- Tailwind modal close --- */
  if ((t && t.id === 'backdrop') || (t && t.closest('[data-modal-close]'))) {
    document.getElementById('backdrop')?.classList.remove('open');
    document.querySelectorAll('.tw-modal.open').forEach(m => {
      m.classList.remove('open');

      // Resetear formulario al cerrar
      const form = m.querySelector('form');
      if (form) {
        form.reset();

        // Limpiar previsualizaciones si existen
        const previewLeft = m.querySelector('#preview-left');
        const previewRight = m.querySelector('#preview-right');
        const filenameLeftDiv = m.querySelector('#filename-left');
        const filenameRightDiv = m.querySelector('#filename-right');

        if (previewLeft) {
          previewLeft.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
        }
        if (previewRight) {
          previewRight.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
        }
        if (filenameLeftDiv) {
          filenameLeftDiv.classList.add('hidden');
        }
        if (filenameRightDiv) {
          filenameRightDiv.classList.add('hidden');
        }
      }
    });
    document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
  }

  /* Bootstrap-like modal (sin Bootstrap JS) */
  const bsOpenBtn = t && t.closest('[data-bs-toggle="modal"]');
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

  if (t && (t.closest('[data-bs-dismiss="modal"]') || t.closest('[data-dismiss="modal"]') || 
      t.closest('.modal .close') || t.closest('.modal .btn-close'))) {
    document.getElementById('backdrop')?.classList.remove('open');
    document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
  }

  /* --- Cerrar alertas --- */
  if (t && t.closest('[data-close]')) {
    const alert = t.closest('.alert');
    if (alert) {
      alert.remove();
    }
  }
});


/* Dark / Light mode */
function themeInit() {
  const root = document.documentElement;
  const themePreference = window.matchMedia('(prefers-color-scheme: dark)');

  function applyTheme(theme) {
    if (theme === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    updateThemeIcon();
  }

  function updateThemeIcon() {
    const themeIcon = document.getElementById('themeIcon');
    if (!themeIcon) return;

    const isDark = root.classList.contains('dark');
    themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  }

  const saved = localStorage.getItem('theme');
  if (saved) {
    applyTheme(saved);
  } else {
    applyTheme(themePreference.matches ? 'dark' : 'light');
  }

  themePreference.addEventListener('change', e => {
    if (!localStorage.getItem('theme')) {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  }, { passive: true });

  const btn = document.getElementById('themeToggle');
  if (btn) {
    btn.addEventListener('click', () => {
      const isDark = root.classList.contains('dark');
      applyTheme(isDark ? 'light' : 'dark');
      localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });
  }
}


/* Inicialización de DataTables */
document.addEventListener('DOMContentLoaded', function() {
  const tables = document.querySelectorAll('table[id$="-table"]');

  tables.forEach(table => {
    const tableId = table.id;

    if ($.fn.DataTable.isDataTable(table)) {
      $(table).DataTable().ajax.reload();
    } else {
      $(table).DataTable({
        responsive: true,
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 10,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             'rt' +
             'ip',
        initComplete: function() {
          const lengthSelect = $(`#${tableId}_length select`);
          const filterInput = $(`#${tableId}_filter input`);

          if (lengthSelect.length) {
            lengthSelect.addClass('form-control');
          }
          if (filterInput.length) {
            filterInput.addClass('form-control');
          }
        },
        error: function(xhr, error, code) {
          console.error('Error en DataTable:', error, code);
          console.error('Respuesta del servidor:', xhr.responseText);
        }
      });
    }
  });
});


/* Función para mostrar alertas */
function showAlert(message, type = 'info') {
  const alertContainer = document.querySelector('.p-4.lg\\:p-6.space-y-4');
  if (!alertContainer) return;

  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.innerHTML = `
    <button class="close-btn" data-close aria-label="Cerrar"></button>
    ${message}
  `;

  alertContainer.insertBefore(alert, alertContainer.firstChild);

  setTimeout(() => {
    if (alert.parentNode) {
      alert.remove();
    }
  }, 5000);
}


/* Función para confirmar acciones */
function confirmAction(message, callback) {
  if (confirm(message)) {
    callback();
  }
}


/* Manejo de pestañas */
document.addEventListener('click', function(e) {
  const tabButton = e.target.closest('[data-tab]');
  if (tabButton) {
    const tabId = tabButton.getAttribute('data-tab');
    const tabContent = document.getElementById(tabId);

    if (tabContent) {
      document.querySelectorAll('[data-tab]').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

      tabButton.classList.add('active');
      tabContent.classList.add('active');
    }
  }
});


/* Manejo de tooltips */
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
      tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
      tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;

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


/* Manejo de notificaciones */
function showNotification(message, type = 'info', duration = 5000) {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);

  setTimeout(() => {
    notification.classList.add('show');
  }, 10);

  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 300);
  }, duration);
}


function toggleUserMenu() {
  const userMenu = document.getElementById('userMenu');
  if (userMenu) {
    userMenu.classList.toggle('hidden');
  }
}


/* ============================================
   SIDEBAR TOGGLE - DIRECT EVENT LISTENER
   ============================================ */
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard inicializado correctamente');
  themeInit();

  // ===== SIDEBAR TOGGLE =====
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('backdrop');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      console.log('🔵 Toggle clicked! Window width:', window.innerWidth);
      console.log('📌 Sidebar before:', sidebar.classList.contains('open') ? 'OPEN' : 'CLOSED');
      
      // Solo en mobile
      if (window.innerWidth < 768) {
        sidebar.classList.toggle('open');
        if (backdrop) {
          backdrop.classList.toggle('open');
        }
        console.log('📌 Sidebar after:', sidebar.classList.contains('open') ? 'OPEN' : 'CLOSED');
      } else {
        console.log('⚠️ Desktop mode - toggle disabled');
      }
    });
    
    console.log('✅ Toggle event listener attached');
  } else {
    console.error('❌ toggleBtn or sidebar not found');
  }

  // Cerrar sidebar al hacer click en un enlace
  const sidebarLinks = document.querySelectorAll('#sidebar a');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      if (window.innerWidth < 768) {
        setTimeout(() => {
          sidebar.classList.remove('open');
          if (backdrop) backdrop.classList.remove('open');
        }, 300);
      }
    });
  });

  // Cerrar sidebar al hacer click en backdrop
  if (backdrop) {
    backdrop.addEventListener('click', function(e) {
      if (window.innerWidth < 768) {
        sidebar.classList.remove('open');
        this.classList.remove('open');
      }
    });
  }

  // ===== OTHER SIDEBAR HANDLERS =====
  // Backdrop
  if (backdrop) {
    backdrop.addEventListener('click', function() {
      if (window.innerWidth < 768) {
        if (sidebar && sidebar.classList.contains('open')) {
          sidebar.classList.remove('open');
          this.classList.remove('open');
        }
      }

      document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
      document.querySelectorAll('.modal.bs-open').forEach(m => m.classList.remove('bs-open'));
      this.classList.remove('open');
    });
  }

  // User menu button
  const userMenuBtn = document.getElementById('userMenuBtn');
  if (userMenuBtn) {
    userMenuBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      toggleUserMenu();
    });
  }

  // Cerrar user menu al hacer clic fuera
  document.addEventListener('click', function(e) {
    const userMenu = document.getElementById('userMenu');
    if (userMenu && !e.target.closest('#userMenuBtn') && !e.target.closest('#userMenu')) {
      userMenu.classList.add('hidden');
    }
  });

  // Manejo responsivo del sidebar
  function handleResponsiveSidebar() {
    if (!sidebar) return;

    if (window.innerWidth < 768) {
      // Mobile: usar fixed positioning
      sidebar.style.position = 'fixed';
    } else {
      // Desktop: resetear a posición relativa
      sidebar.style.position = 'relative';
      sidebar.classList.remove('open');
    }
  }

  handleResponsiveSidebar();
  window.addEventListener('resize', handleResponsiveSidebar);
});