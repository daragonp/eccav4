// Configuración inicial
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización con performance optimization
    if ('requestIdleCallback' in window) {
        requestIdleCallback(() => {
            initTheme();
            setupEventListeners();
            setupLogoutHandler();
        });
    } else {
        // Fallback para navegadores que no soportan requestIdleCallback
        setTimeout(() => {
            initTheme();
            setupEventListeners();
            setupLogoutHandler();
        }, 1);
    }
});


// Inicializar tema
function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    
    applyTheme(theme);
    
    // Escuchar cambios en la preferencia del sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
}


// Aplicar tema
function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    localStorage.setItem('theme', theme);
}


// Funciones para manejar modales
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');
    
    if (modal) {
        // Prevenir scroll en el body
        document.body.style.overflow = 'hidden';
        
        // Mostrar backdrop
        if (backdrop) {
            backdrop.classList.remove('hidden');
            backdrop.classList.add('open');
        }
        
        // Mostrar modal con un pequeño retraso para asegurar que la transición se aplique
        setTimeout(() => {
            modal.classList.remove('hidden');
            modal.classList.add('open');
        }, 10);
    }
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');
    
    if (modal) {
        // Ocultar modal
        modal.classList.remove('open');
        
        // Ocultar backdrop con un pequeño retraso para permitir la transición
        setTimeout(() => {
            modal.classList.add('hidden');
            
            if (backdrop) {
                backdrop.classList.remove('open');
                backdrop.classList.add('hidden');
            }
            
            // Restaurar scroll en el body
            document.body.style.overflow = '';
        }, 300);
    }
}


// Configurar event listeners
function setupEventListeners() {
    // Toggle sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapsed = document.getElementById('sidebar-collapsed');
    
    if (toggleBtn && sidebar && sidebarCollapsed) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
            sidebarCollapsed.classList.toggle('hidden');
        });
    }


    // Toggle theme
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
        });
    }


    // User menu toggle
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    
    if (userMenuBtn && userMenu) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });


        // Cerrar al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }


    // Event listeners para modales
    document.addEventListener('click', function(e) {
        // Abrir modal
        const modalTrigger = e.target.closest('[data-modal-open]');
        if (modalTrigger) {
            e.preventDefault();
            const modalId = modalTrigger.getAttribute('data-modal-open');
            openModal(modalId);
            return;
        }


        // Cerrar modal con botón de cierre
        const modalClose = e.target.closest('[data-modal-close]');
        if (modalClose) {
            e.preventDefault();
            const modal = modalClose.closest('.tw-modal');
            if (modal) {
                closeModal(modal.id);
            }
            return;
        }


        // Solo cerrar modal si el click es DIRECTAMENTE en el backdrop, no dentro del panel
        if ((e.target.id === 'backdrop' || e.target.classList.contains('tw-modal-backdrop')) && e.target === e.currentTarget) {
            const openModals = document.querySelectorAll('.tw-modal.open');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
            return;
        }
    }, true);


    // Cerrar modal con la tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.tw-modal.open');
            openModals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });


    // Quick actions
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    quickActionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            if (url) window.location.href = url;
        });
    });
}


// Cargar recursos externos de forma asíncrona
function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}


// ✅ MANEJADOR DEL LOGOUT - VERSIÓN CORREGIDA
function setupLogoutHandler() {
    const logoutButton = document.getElementById('logout-button');
    const logoutModal = document.getElementById('logoutModal');
    const cancelButton = document.getElementById('cancelLogout');
    const confirmButton = document.getElementById('confirmLogout');
    const logoutModalClose = document.getElementById('logoutModalClose');
    const logoutForm = document.getElementById('logoutForm');
    const modalContent = logoutModal?.querySelector('.tw-modal-panel');

    // Validación
    if (!logoutButton || !logoutModal || !modalContent) {
        console.warn('⚠️ Elementos de logout no encontrados');
        return;
    }

    function showModal() {
        console.log('📂 Abriendo modal...');
        logoutModal.classList.remove('hidden');
        logoutModal.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
    }

    function hideModal() {
        console.log('📁 Cerrando modal...');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            logoutModal.classList.add('hidden');
            logoutModal.setAttribute('aria-hidden', 'true');
        }, 200);
    }

    // Abrir modal
    logoutButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        showModal();
    });

    // Cerrar modal - Cancelar
    if (cancelButton) {
        cancelButton.addEventListener('click', (e) => {
            e.preventDefault();
            hideModal();
        });
    }

    // Cerrar modal - X
    if (logoutModalClose) {
        logoutModalClose.addEventListener('click', (e) => {
            e.preventDefault();
            hideModal();
        });
    }

    // ✅ ENVIAR FORMULARIO - Si hay botón de confirmación en el modal
    if (confirmButton) {
        confirmButton.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('✅ Enviando formulario de logout...');
            if (logoutForm) {
                logoutForm.submit();
            }
        });
    }
    
    // Cerrar modal al hacer click fuera
    logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
            hideModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
            hideModal();
        }
    });

    console.log('✅ setupLogoutHandler inicializado correctamente');
}


// Cargar scripts necesarios después de que la página esté lista
window.addEventListener('load', function() {
    Promise.all([
        loadScript('https://code.jquery.com/jquery-3.7.1.min.js'),
        loadScript('https://cdn.datatables.net/2.3.4/js/dataTables.min.js'),
        loadScript('https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js')
    ]).then(() => {
        if (typeof $.fn.DataTable !== 'undefined' && document.querySelector('.datatable')) {
            $('.datatable').DataTable({
                responsive: true,
                defer: true,
                language: {
                    url: '/js/datatables-es.json'
                }
            });
        }
    }).catch(console.error);
});
