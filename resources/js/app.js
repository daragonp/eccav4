// No Bootstrap. Mantén Alpine para interactividad y lo que ya uses.
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// Si todavía necesitas jQuery por Lightbox CDN, déjalo global:
import jQuery from 'jquery'
window.$ = jQuery

// SweetAlert opcional
import swal from 'sweetalert2'
window.Swal = swal
