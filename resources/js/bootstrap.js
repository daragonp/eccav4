/**
 * Config HTTP global (sin Bootstrap CSS/JS).
 * Deja axios con CSRF/XSRF y utilidades listas.
 */

import axios from 'axios'

// Exponer axios en window (útil en vistas Blade/inline scripts)
window.axios = axios

// Identificar XHR
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Base URL opcional (útil si expones API en subruta/dominio distinto)
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || ''

/**
 * CSRF para formularios POST desde Blade:
 * - Meta <meta name="csrf-token" content="..."> en el layout
 * - Axios tomará el token y lo enviará en 'X-CSRF-TOKEN'
 */
const csrfTag = document.querySelector('meta[name="csrf-token"]')
if (csrfTag) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTag.getAttribute('content')
}

/**
 * XSRF (cookies) para SPA / Sanctum:
 * - Laravel envía cookie 'XSRF-TOKEN'
 * - Configuramos axios para leerla y mandarla como 'X-XSRF-TOKEN'
 */
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'
// Si usas Sanctum en el MISMO dominio/subdominio:
axios.defaults.withCredentials = true

// Interceptores opcionales (logging básico)
axios.interceptors.response.use(
  (r) => r,
  (error) => {
    // Puedes unificar manejo de errores aquí o mostrar Swal si está disponible
    if (window.Swal?.fire) {
      const msg =
        error.response?.data?.message ||
        error.message ||
        'Error al procesar la solicitud.'
      Swal.fire({ icon: 'error', text: msg })
    }
    return Promise.reject(error)
  }
)

/* -------------------------------------------
 * Echo / Pusher (OPT-IN, descomenta si lo usas)
 * ------------------------------------------- */
// import Echo from 'laravel-echo'
// import Pusher from 'pusher-js'
// window.Pusher = Pusher
// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: import.meta.env.VITE_PUSHER_APP_KEY,
//   wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//   wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//   wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//   forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//   enabledTransports: ['ws', 'wss'],
// })

export default axios
