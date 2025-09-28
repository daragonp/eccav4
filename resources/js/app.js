// Turbo para navegación sin full reload
import * as Turbo from '@hotwired/turbo'
window.Turbo = Turbo

import './bootstrap'

// Alpine
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// SweetAlert opcional
import swal from 'sweetalert2'
window.Swal = swal

// ---- Tema (dark/light) ----
const root = document.documentElement
const THEME_KEY = 'theme'

function applySavedTheme() {
  try {
    const saved = localStorage.getItem(THEME_KEY)
    if (saved === 'dark') root.classList.add('dark')
    if (saved === 'light') root.classList.remove('dark')
  } catch (_) {}
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
  // Limpia manejador previo (por si Turbo re-llama)
  btn.onclick = () => {
    root.classList.toggle('dark')
    localStorage.setItem(THEME_KEY, root.classList.contains('dark') ? 'dark' : 'light')
    syncThemeIcon()
  }
  syncThemeIcon()
}

// Ejecuta en primer load y en cada navegación Turbo
document.addEventListener('DOMContentLoaded', () => {
  applySavedTheme()
  bindThemeToggle()
})
document.addEventListener('turbo:load', () => {
  applySavedTheme()
  bindThemeToggle()
})

// ---- Lightbox (opcional) ----
window.addEventListener('load', () => {
  if (window.lightbox?.option) {
    lightbox.option({ fadeDuration: 180, resizeDuration: 180, positionFromTop: 40, wrapAround: true })
  }
})

document.addEventListener('turbo:click', (e) => {
    const a = e.target.closest && e.target.closest('a[data-lightbox]');
    if (a) e.preventDefault(); // deja que Lightbox maneje el click
  });
  
  // Reaplica opciones de Lightbox tras cada navegación Turbo (opcional)
  document.addEventListener('turbo:load', () => {
    if (window.lightbox?.option) {
      lightbox.option({
        fadeDuration: 200,
        resizeDuration: 200,
        wrapAround: true,
        disableScrolling: true,
        positionFromTop: 50,
      });
    }
  });
  