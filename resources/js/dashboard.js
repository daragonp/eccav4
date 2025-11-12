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


// Funciones para manejar modales (compatibles con ambos sistemas)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');
    
    if (modal) {
        // Prevenir scroll en el body
        document.body.style.overflow = 'hidden';
        
        // Sistema antiguo con clases
        if (modal.classList.contains('tw-modal')) {
            if (backdrop) {
                backdrop.classList.remove('hidden');
                backdrop.classList.add('open');
            }
            setTimeout(() => {
                modal.classList.remove('hidden');
                modal.classList.add('open');
            }, 10);
        } 
        // Sistema nuevo con inline styles (data-modal-container)
        else if (modal.hasAttribute('data-modal-container')) {
            modal.style.display = 'block';
            // Forzar reflow
            void modal.offsetWidth;
            modal.style.opacity = '1';
            modal.style.visibility = 'visible';
        }
    }
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');
    
    if (modal) {
        // Sistema antiguo con clases
        if (modal.classList.contains('tw-modal')) {
            modal.classList.remove('open');
            setTimeout(() => {
                modal.classList.add('hidden');
                if (backdrop) {
                    backdrop.classList.remove('open');
                    backdrop.classList.add('hidden');
                }
                document.body.style.overflow = '';
            }, 300);
        }
        // Sistema nuevo con inline styles
        else if (modal.hasAttribute('data-modal-container')) {
            modal.style.opacity = '0';
            modal.style.visibility = 'hidden';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
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

        // Cerrar modal al hacer click fuera (en el backdrop)
        if (e.target.classList.contains('tw-modal')) {
            // Verificar que el click NO sea en el modal-panel
            if (!e.target.querySelector('.tw-modal-panel').contains(e.target)) {
                const modal = e.target;
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            }
            return;
        }
    });


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


// ✅ MANEJADOR DEL LOGOUT - VERSIÓN CORREGIDA CON DELEGACIÓN DE EVENTOS
function setupLogoutHandler() {
    // Usar delegación de eventos en documento para mayor compatibilidad
    document.addEventListener('click', (e) => {
        const logoutBtn = e.target.closest('#logout-button');
        if (logoutBtn) {
            e.preventDefault();
            e.stopPropagation();
            showLogoutModal();
        }
    });

    // Delegación para botón de cancelar
    document.addEventListener('click', (e) => {
        const cancelBtn = e.target.closest('#cancelLogout');
        if (cancelBtn) {
            e.preventDefault();
            hideLogoutModal();
        }
    });

    // Delegación para botón de cerrar (X)
    document.addEventListener('click', (e) => {
        const closeBtn = e.target.closest('#logoutModalClose');
        if (closeBtn) {
            e.preventDefault();
            hideLogoutModal();
        }
    });

    // Delegación para confirmar logout
    document.addEventListener('click', (e) => {
        const confirmBtn = e.target.closest('#confirmLogout');
        if (confirmBtn) {
            e.preventDefault();
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                console.log('✅ Enviando formulario de logout...');
                logoutForm.submit();
            }
        }
    });

    // Cerrar modal al hacer click en el backdrop
    document.addEventListener('click', (e) => {
        const logoutModal = document.getElementById('logoutModal');
        if (e.target === logoutModal && logoutModal && !logoutModal.classList.contains('hidden')) {
            hideLogoutModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const logoutModal = document.getElementById('logoutModal');
            if (logoutModal && !logoutModal.classList.contains('hidden')) {
                hideLogoutModal();
            }
        }
    });

    console.log('✅ setupLogoutHandler inicializado correctamente');
}

// Funciones auxiliares para mostrar/ocultar modal
function showLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = logoutModal?.querySelector('.tw-modal-panel');
    const backdrop = document.getElementById('backdrop');

    if (!logoutModal || !modalContent) {
        console.warn('⚠️ Modal de logout no encontrado');
        return;
    }

    console.log('📂 Abriendo modal de logout...');
    
    // Remover la clase hidden y agregar open
    logoutModal.classList.remove('hidden');
    logoutModal.classList.add('open');
    logoutModal.setAttribute('aria-hidden', 'false');
    
    // Mostrar backdrop si existe
    if (backdrop) {
        backdrop.classList.remove('hidden');
        backdrop.classList.add('open');
    }
    
    // Agregar clase al modal-panel para la animación
    modalContent.classList.remove('scale-95', 'opacity-0');
    modalContent.classList.add('scale-100', 'opacity-100');
}

function hideLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = logoutModal?.querySelector('.tw-modal-panel');
    const backdrop = document.getElementById('backdrop');

    if (!logoutModal || !modalContent) {
        return;
    }

    console.log('📁 Cerrando modal de logout...');
    
    // Remover animación
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        logoutModal.classList.remove('open');
        logoutModal.classList.add('hidden');
        logoutModal.setAttribute('aria-hidden', 'true');
        
        // Ocultar backdrop
        if (backdrop) {
            backdrop.classList.remove('open');
            backdrop.classList.add('hidden');
        }
    }, 200);
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
