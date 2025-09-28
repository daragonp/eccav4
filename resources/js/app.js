import './bootstrap' // ⬅️ importante

// No Bootstrap. Mantén Alpine para interactividad.
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// SweetAlert opcional
import swal from 'sweetalert2'
window.Swal = swal

// Config ligera para Lightbox (usa el global `lightbox` del CDN)
window.addEventListener('load', () => {
  if (window.lightbox?.option) {
    lightbox.option({
      fadeDuration: 180,
      resizeDuration: 180,
      positionFromTop: 40,
      wrapAround: true,
    })
  }
})
