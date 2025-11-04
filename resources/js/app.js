/**
 * ═══════════════════════════════════════════════════════════════════
 * APLICACIÓN PRINCIPAL - ECCA
 * ═══════════════════════════════════════════════════════════════════
 */

// ═══════════════════════════════════════════════════════════════════
// 1. TURBO (Navegación sin full reload)
// ═══════════════════════════════════════════════════════════════════
import * as Turbo from '@hotwired/turbo'
window.Turbo = Turbo
console.log('🚀 [APP] Turbo inicializado')

// ═══════════════════════════════════════════════════════════════════
// 2. BOOTSTRAP (Configuración base)
// ═══════════════════════════════════════════════════════════════════
import './bootstrap'

// ═══════════════════════════════════════════════════════════════════
// 3. ALPINE.JS (Framework reactivo)
// ═══════════════════════════════════════════════════════════════════
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'

window.Alpine = Alpine

// Registrar plugins
Alpine.plugin(collapse)
Alpine.plugin(focus)

console.log('✨ [APP] Alpine.js configurado')

// ═══════════════════════════════════════════════════════════════════
// 4. PRIVACIDAD (Importar ANTES de Alpine.start)
// ═══════════════════════════════════════════════════════════════════
import('./privacy-notice').then(module => {
  console.log('🔐 [APP] Privacy Notice registrado con Alpine')
}).catch(err => {
  console.error('❌ [APP] Error cargando Privacy Notice:', err)
})

// ═══════════════════════════════════════════════════════════════════
// 5. INICIAR ALPINE (Una sola vez)
// ══════════════════════════════════════════════════════════════════
Alpine.start()
console.log('✅ [APP] Alpine.js iniciado')

// ═══════════════════════════════════════════════════════════════════
// 6. SWEETALERT (Notificaciones bonitas)
// ═══════════════════════════════════════════════════════════════════
import Swal from 'sweetalert2'
window.Swal = Swal
console.log('🎨 [APP] SweetAlert2 disponible')

/**
 * ═══════════════════════════════════════════════════════════════════
 * TEMA (Dark/Light Mode)
 * ═══════════════════════════════════════════════════════════════════
 */
const root = document.documentElement
const THEME_KEY = 'theme'

function applySavedTheme() {
  try {
    const saved = localStorage.getItem(THEME_KEY)
    if (saved === 'dark') {
      root.classList.add('dark')
      console.log('🌙 [THEME] Tema oscuro aplicado')
    }
    if (saved === 'light') {
      root.classList.remove('dark')
      console.log('☀️ [THEME] Tema claro aplicado')
    }
  } catch (_) {
    console.warn('⚠️ [THEME] Error leyendo localStorage')
  }
  syncThemeIcon()
}

function syncThemeIcon() {
  const icon = document.getElementById('themeIcon')
  if (!icon) return
  icon.textContent = root.classList.contains('dark') ? '☀️' : '🌙'
}

function bindThemeToggle() {
  const btn = document.getElementById('toggleTheme')
  if (!btn) return
  
  // Limpiar handler previo (para evitar duplicados con Turbo)
  btn.onclick = null
  
  // Nuevo handler
  btn.onclick = () => {
    root.classList.toggle('dark')
    localStorage.setItem(THEME_KEY, root.classList.contains('dark') ? 'dark' : 'light')
    syncThemeIcon()
    console.log('🔄 [THEME] Tema cambiado')
  }
  
  syncThemeIcon()
}

// Ejecutar en primer load
document.addEventListener('DOMContentLoaded', () => {
  console.log('📄 [THEME] DOM cargado - aplicando tema')
  applySavedTheme()
  bindThemeToggle()
})

// Re-ejecutar con cada navegación Turbo
document.addEventListener('turbo:load', () => {
  console.log('🔄 [THEME] Turbo navigó - re-aplicando tema')
  applySavedTheme()
  bindThemeToggle()
  
  // También reiniciar Privacy Notice si es primera página
  initPrivacyNoticeOnTurboLoad()
})

/**
 * ═══════════════════════════════════════════════════════════════════
 * PRIVACIDAD - Integración con Turbo
 * ═══════════════════════════════════════════════════════════════════
 */
function initPrivacyNoticeOnTurboLoad() {
  // Solo en la primera carga (no en navegaciones Turbo posteriores)
  const currentPath = window.location.pathname
  const privacyInitPath = sessionStorage.getItem('privacyInitPath')
  
  if (privacyInitPath === null) {
    // Primera carga - inicializar privacidad
    sessionStorage.setItem('privacyInitPath', currentPath)
    console.log('🔐 [PRIVACY] Inicializando en primera carga')
    
    // Esperar a que el DOM esté listo
    setTimeout(() => {
      const wrapper = document.querySelector('[x-data="privacyNoticeApp()"]')
      if (wrapper && wrapper.__x) {
        console.log('🔐 [PRIVACY] Llamando a init()')
        wrapper.__x.$data.init()
      }
    }, 100)
  } else {
    console.log('⏭️ [PRIVACY] Ya inicializado, saltando')
  }
}

/**
 * ═══════════════════════════════════════════════════════════════════
 * LIGHTBOX
 * ═══════════════════════════════════════════════════════════════════
 */

// Configurar Lightbox en primer load
window.addEventListener('load', () => {
  if (typeof lightbox !== 'undefined' && lightbox.option) {
    console.log('🖼️ [LIGHTBOX] Configurando opciones')
    lightbox.option({
      fadeDuration: 180,
      resizeDuration: 180,
      positionFromTop: 40,
      wrapAround: true,
      disableScrolling: true,
      albumLabel: "Imagen %1 de %2"
    })
  }
})

// Prevenir que Lightbox interfiera con clics en links
document.addEventListener('turbo:click', (e) => {
  const a = e.target.closest && e.target.closest('a[data-lightbox]')
  if (a) {
    e.preventDefault() // Dejar que Lightbox maneje
    console.log('📸 [LIGHTBOX] Link clickeado')
  }
})

// Re-configurar Lightbox tras cada navegación Turbo
document.addEventListener('turbo:load', () => {
  if (typeof lightbox !== 'undefined' && lightbox.option) {
    console.log('🖼️ [LIGHTBOX] Re-configurando tras Turbo')
    lightbox.option({
      fadeDuration: 200,
      resizeDuration: 200,
      wrapAround: true,
      disableScrolling: true,
      positionFromTop: 50,
      albumLabel: "Imagen %1 de %2"
    })
  }
})

/**
 * ═══════════════════════════════════════════════════════════════════
 * EXPORTAR
 * ═══════════════════════════════════════════════════════════════════
 */
export { Alpine }

console.log('✅ [APP] Aplicación completamente inicializada')