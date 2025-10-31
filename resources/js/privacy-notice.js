/**
 * Aviso de Privacidad y Cookies
 * Compatible con Turbo y Alpine.js
 */

import Alpine from 'alpinejs'

console.log('📦 [PRIVACY] Cargando módulo...')

const PRIVACY_NOTICE_CONFIG = {
  cookieName: 'privacy_notice_accepted',
  sessionDuration: 30 * 24 * 60 * 60 * 1000,
}

/**
 * Componente Alpine para Privacy Notice
 */
Alpine.data('privacyNoticeApp', () => ({
  privacyNoticeVisible: false,

  /**
   * Inicializa el aviso
   */
  init() {
    console.log('🔐 [PRIVACY] init() llamado')
    setTimeout(() => this.checkAndShow(), 50)
  },

  /**
   * Verifica y muestra si es necesario
   */
  checkAndShow() {
    console.log('🔍 [PRIVACY] Verificando...')
    
    if (!this.hasAcceptedPrivacy()) {
      console.log('✋ [PRIVACY] Mostrando aviso (no aceptado)')
      this.show()
    } else {
      console.log('✅ [PRIVACY] Ocultando aviso (ya aceptado)')
    }
  },

  /**
   * Muestra el modal
   */
  show() {
    console.log('📂 [PRIVACY] Mostrando modal')
    this.privacyNoticeVisible = true
    document.body.style.overflow = 'hidden'
  },

  /**
   * Verifica la cookie de aceptación
   */
  hasAcceptedPrivacy() {
    const cookie = this.getCookie(PRIVACY_NOTICE_CONFIG.cookieName)
    const accepted = cookie !== null
    console.log('🍪 [PRIVACY] Cookie aceptada:', accepted)
    return accepted
  },

  /**
   * Lee una cookie
   */
  getCookie(name) {
    const nameEQ = name + '='
    const cookies = document.cookie.split(';')
    
    for (let i = 0; i < cookies.length; i++) {
      let cookie = cookies[i].trim()
      if (cookie.indexOf(nameEQ) === 0) {
        return decodeURIComponent(cookie.substring(nameEQ.length))
      }
    }
    
    return null
  },

  /**
   * Escribe una cookie
   */
  setCookie(name, value, days = 30) {
    const expires = new Date()
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000)
    
    const cookieString = 
      name + '=' + encodeURIComponent(value) +
      '; expires=' + expires.toUTCString() +
      '; path=/' +
      '; SameSite=Lax'
    
    document.cookie = cookieString
    console.log('🍪 [PRIVACY] Cookie guardada:', name)
  },

  /**
   * Usuario acepta el aviso
   */
  acceptPrivacyNotice() {
    console.log('✅ [PRIVACY] Aceptando...')
    
    this.setCookie(
      PRIVACY_NOTICE_CONFIG.cookieName,
      JSON.stringify({
        accepted: true,
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
      }),
      30
    )

    this.logPrivacyAcceptance()
    this.closePrivacyNotice()
  },

  /**
   * Cierra el modal
   */
  closePrivacyNotice() {
    console.log('🚪 [PRIVACY] Cerrando')
    this.privacyNoticeVisible = false
    document.body.style.overflow = 'auto'
  },

  /**
   * Abre la política completa
   */
  openPrivacyPolicy() {
    console.log('📄 [PRIVACY] Abriendo /legal/privacy')
    window.open('/legal/privacy', '_blank')
  },

  /**
   * Registra la aceptación en el servidor
   */
  logPrivacyAcceptance() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

    if (!csrfToken) {
      console.warn('⚠️ [PRIVACY] No hay CSRF token')
      return
    }

    fetch('/api/privacy-acceptance', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        accepted: true,
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        pageUrl: window.location.href,
      }),
    })
    .then(response => {
      if (response.ok) {
        console.log('📊 [PRIVACY] Registrado en servidor')
      }
    })
    .catch(error => console.error('❌ [PRIVACY] Error:', error))
  },
}))

console.log('✨ [PRIVACY] Componente registrado con Alpine')
