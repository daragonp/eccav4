function applyTheme(theme) {
    const root = document.documentElement;
    if (theme === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }

    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    localStorage.setItem('theme', theme);
}

function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(savedTheme || (systemPrefersDark ? 'dark' : 'light'));
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');

    if (!modal) return;

    document.body.style.overflow = 'hidden';

    if (modal.classList.contains('tw-modal')) {
        if (backdrop) {
            backdrop.classList.remove('hidden');
            backdrop.classList.add('open');
        }

        setTimeout(() => {
            modal.classList.remove('hidden');
            modal.classList.add('open');
        }, 10);
        return;
    }

    if (modal.hasAttribute('data-modal-container')) {
        modal.style.display = 'block';
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modal.style.visibility = 'visible';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('backdrop');

    if (!modal) return;

    if (modal.classList.contains('tw-modal')) {
        modal.classList.remove('open');
        setTimeout(() => {
            modal.classList.add('hidden');
            if (backdrop) {
                backdrop.classList.remove('open');
                backdrop.classList.add('hidden');
            }
            document.body.style.overflow = '';
        }, 200);
        return;
    }

    if (modal.hasAttribute('data-modal-container')) {
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 200);
    }
}

function bindSidebarToggle() {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapsed = document.getElementById('sidebar-collapsed');

    if (!toggleBtn || !sidebar || !sidebarCollapsed || toggleBtn.dataset.bound === '1') return;

    toggleBtn.dataset.bound = '1';
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        sidebarCollapsed.classList.toggle('hidden');
    });
}

function bindThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle || themeToggle.dataset.bound === '1') return;

    themeToggle.dataset.bound = '1';
    themeToggle.addEventListener('click', () => {
        const isDark = document.documentElement.classList.contains('dark');
        applyTheme(isDark ? 'light' : 'dark');
    });
}

function bindUserMenuToggle() {
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');

    if (!userMenuBtn || !userMenu || userMenuBtn.dataset.bound === '1') return;

    userMenuBtn.dataset.bound = '1';
    userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenu.classList.toggle('hidden');
    });
}

function initDataTables() {
    if (!window.jQuery || typeof window.jQuery.fn?.DataTable === 'undefined') return;

    const tables = document.querySelectorAll('.datatable');
    tables.forEach((table) => {
        if (window.jQuery.fn.DataTable.isDataTable(table)) return;

        window.jQuery(table).DataTable({
            responsive: true,
            language: {
                url: '/js/datatables-es.json',
            },
        });
    });
}

function showLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = logoutModal?.querySelector('.tw-modal-panel');
    const backdrop = document.getElementById('backdrop');

    if (!logoutModal || !modalContent) return;

    logoutModal.classList.remove('hidden');
    logoutModal.classList.add('open');
    logoutModal.setAttribute('aria-hidden', 'false');

    if (backdrop) {
        backdrop.classList.remove('hidden');
        backdrop.classList.add('open');
    }

    modalContent.classList.remove('scale-95', 'opacity-0');
    modalContent.classList.add('scale-100', 'opacity-100');
}

function hideLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = logoutModal?.querySelector('.tw-modal-panel');
    const backdrop = document.getElementById('backdrop');

    if (!logoutModal || !modalContent) return;

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        logoutModal.classList.remove('open');
        logoutModal.classList.add('hidden');
        logoutModal.setAttribute('aria-hidden', 'true');

        if (backdrop) {
            backdrop.classList.remove('open');
            backdrop.classList.add('hidden');
        }
    }, 200);
}

function bindDelegatedEvents() {
    if (document.body.dataset.dashboardDelegated === '1') return;
    document.body.dataset.dashboardDelegated = '1';

    document.addEventListener('click', (e) => {
        const closeBtn = e.target.closest('[data-close]');
        if (closeBtn) {
            const alert = closeBtn.closest('.alert');
            if (alert) alert.remove();
            return;
        }

        const modalTrigger = e.target.closest('[data-modal-open]');
        if (modalTrigger) {
            e.preventDefault();
            openModal(modalTrigger.getAttribute('data-modal-open'));
            return;
        }

        const modalClose = e.target.closest('[data-modal-close]');
        if (modalClose) {
            e.preventDefault();
            const modal = modalClose.closest('.tw-modal');
            if (modal) closeModal(modal.id);
            return;
        }

        const logoutBtn = e.target.closest('#logout-button');
        if (logoutBtn) {
            e.preventDefault();
            showLogoutModal();
            return;
        }

        if (e.target.closest('#cancelLogout') || e.target.closest('#logoutModalClose')) {
            e.preventDefault();
            hideLogoutModal();
            return;
        }

        const confirmBtn = e.target.closest('#confirmLogout');
        if (confirmBtn) {
            e.preventDefault();
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) logoutForm.submit();
            return;
        }

        const userMenu = document.getElementById('userMenu');
        const userMenuBtn = document.getElementById('userMenuBtn');
        if (userMenu && userMenuBtn && !userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
            userMenu.classList.add('hidden');
        }

        if (e.target.classList.contains('tw-modal')) {
            const panel = e.target.querySelector('.tw-modal-panel');
            if (panel && !panel.contains(e.target)) {
                closeModal(e.target.id);
            }
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;

        const openModals = document.querySelectorAll('.tw-modal.open');
        openModals.forEach((modal) => closeModal(modal.id));

        const logoutModal = document.getElementById('logoutModal');
        if (logoutModal && !logoutModal.classList.contains('hidden')) {
            hideLogoutModal();
        }
    });
}

function initDashboard() {
    initTheme();
    bindSidebarToggle();
    bindThemeToggle();
    bindUserMenuToggle();
    bindDelegatedEvents();
    initDataTables();
}

document.addEventListener('DOMContentLoaded', initDashboard);
document.addEventListener('turbo:load', initDashboard);

window.openModal = openModal;
window.closeModal = closeModal;
