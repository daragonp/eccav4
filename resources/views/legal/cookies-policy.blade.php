@extends('layouts.main')

@section('title', 'Política de Cookies')

@section('page-hero')
  <div class="hero-content">
    <h1>Política de Cookies</h1>
    <p>Información sobre cómo utilizamos cookies en nuestro sitio</p>
  </div>
@endsection

@section('content')
<div class="legal-content">
  <section class="legal-section">
    <h2>¿Qué son las Cookies?</h2>
    <p>
      Las cookies son pequeños archivos de texto que se almacenan en su dispositivo cuando visita nuestro sitio web. 
      Contienen información sobre su navegación y preferencias, permitiéndonos mejorar su experiencia.
    </p>
  </section>

  <section class="legal-section">
    <h2>Tipos de Cookies que Utilizamos</h2>

    <h3>1. Cookies Esenciales (Necesarias)</h3>
    <p>
      Estas cookies son imprescindibles para el funcionamiento correcto del sitio. Sin ellas, 
      ciertos servicios no podrían funcionar.
    </p>
    <ul>
      <li><strong>PHPSESSID:</strong> Gestiona sesiones de usuario</li>
      <li><strong>privacy_notice_accepted:</strong> Registra la aceptación del aviso de privacidad</li>
      <li><strong>CSRF-TOKEN:</strong> Protege contra ataques de seguridad</li>
      <li><strong>Preferencias de idioma:</strong> Recuerda su idioma preferido</li>
    </ul>
    <p><strong>Duración:</strong> Sesión o 30 días</p>

    <h3>2. Cookies de Análisis</h3>
    <p>
      Nos ayudan a entender cómo los usuarios interactúan con el sitio, qué páginas son populares 
      y dónde pueden mejorar la experiencia.
    </p>
    <ul>
      <li><strong>Google Analytics:</strong> Rastreo anónimo de visitantes</li>
      <li><strong>Hotjar:</strong> Mapas de calor y grabaciones de sesiones (anonimizadas)</li>
    </ul>
    <p><strong>Duración:</strong> Hasta 2 años</p>

    <h3>3. Cookies de Personalización</h3>
    <p>
      Almacenan sus preferencias para personalizar su experiencia en futuras visitas.
    </p>
    <ul>
      <li><strong>Tema (modo claro/oscuro):</strong> Recuerda su preferencia visual</li>
      <li><strong>Preferencias de contenido:</strong> Categorías de interés</li>
      <li><strong>Historial de búsqueda:</strong> Búsquedas recientes</li>
    </ul>
    <p><strong>Duración:</strong> 1 año</p>

    <h3>4. Cookies Publicitarias</h3>
    <p>
      Se utilizan para mostrar anuncios relevantes basados en sus intereses y comportamiento de navegación.
    </p>
    <ul>
      <li><strong>Google Ads:</strong> Publicidad personalizada</li>
      <li><strong>Facebook Pixel:</strong> Seguimiento de conversiones en redes sociales</li>
    </ul>
    <p><strong>Duración:</strong> Hasta 6 meses</p>

    <h3>5. Cookies de Terceros</h3>
    <p>
      Establecidas por sitios externos como redes sociales o proveedores de servicios integrados.
    </p>
    <ul>
      <li><strong>YouTube:</strong> Para videos embebidos</li>
      <li><strong>Spotify/Apple Music:</strong> Para reproductores de música</li>
      <li><strong>Redes Sociales:</strong> Para botones de compartir</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>Cómo Utilizamos las Cookies</h2>
    <ul>
      <li>Autenticar usuarios y gestionar sesiones seguras</li>
      <li>Recordar preferencias y configuraciones personales</li>
      <li>Analizar el comportamiento de usuarios para mejorar contenido</li>
      <li>Detectar y prevenir fraude</li>
      <li>Mostrar publicidad relevante (si lo autoriza)</li>
      <li>Medir la efectividad de campañas</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>Control de Cookies</h2>
    <p>
      Usted tiene pleno control sobre las cookies. Puede:
    </p>
    <ul>
      <li>
        <strong>Aceptar o rechazar cookies:</strong> Utilice el banner de consentimiento en nuestro sitio
      </li>
      <li>
        <strong>Cambiar preferencias en cualquier momento:</strong> Acceda a la configuración de privacidad
      </li>
      <li>
        <strong>Eliminar cookies de su navegador:</strong> Consulte la ayuda de su navegador
      </li>
      <li>
        <strong>Usar modo incógnito:</strong> Para navegar sin guardar cookies
      </li>
      <li>
        <strong>Desactivar cookies a nivel del navegador:</strong> 
        <ul>
          <li><a href="https://support.google.com/chrome/answer/95647" target="_blank">Chrome</a></li>
          <li><a href="https://support.mozilla.org/es/kb/cookies-informacion-que-sitios-web-guardan" target="_blank">Firefox</a></li>
          <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471" target="_blank">Safari</a></li>
          <li><a href="https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies" target="_blank">Edge</a></li>
        </ul>
      </li>
    </ul>
    <p style="margin-top: 1rem;">
      <strong>Nota:</strong> Desactivar cookies esenciales puede afectar el funcionamiento del sitio.
    </p>
  </section>

  <section class="legal-section">
    <h2>Do Not Track (DNT)</h2>
    <p>
      Si su navegador tiene habilitada la opción "No rastrearme" (DNT), respetamos esta preferencia 
      y limitamos el uso de cookies de análisis y publicidad.
    </p>
  </section>

  <section class="legal-section">
    <h2>Cookies de Terceros y Privacidad</h2>
    <p>
      Algunos de nuestros socios utilizan cookies propias:
    </p>
    <ul>
      <li>
        <strong>Google Analytics:</strong> 
        <a href="https://policies.google.com/privacy" target="_blank">Política de privacidad</a>
      </li>
      <li>
        <strong>Facebook/Meta:</strong> 
        <a href="https://www.facebook.com/about/privacy" target="_blank">Política de privacidad</a>
      </li>
      <li>
        <strong>YouTube:</strong> 
        <a href="https://policies.google.com/privacy" target="_blank">Política de privacidad</a>
      </li>
    </ul>
    <p>
      Estos terceros tienen sus propias políticas de privacidad y cookies. 
      No somos responsables por el contenido de sus políticas.
    </p>
  </section>

  <section class="legal-section">
    <h2>Actualización de esta Política</h2>
    <p>
      Esta política se actualiza periódicamente. La última actualización fue: <strong>{{ now()->format('d/m/Y') }}</strong>
    </p>
  </section>

  <section class="legal-section" style="background: #f3f4f6; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #667eea;">
    <h2 style="margin-top: 0;">¿Preguntas sobre Cookies?</h2>
    <p style="margin: 0.5rem 0;">
      Contáctenos en <a href="mailto:privacy@ecca.com">privacy@ecca.com</a> para cualquier consulta sobre nuestro uso de cookies.
    </p>
  </section>
</div>

<style>
  .legal-content {
    max-width: 900px;
    margin: 0 auto;
  }

  .legal-section {
    margin-bottom: 2rem;
  }

  .legal-section h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    margin-top: 2rem;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.75rem;
  }

  .legal-section h2:first-child {
    margin-top: 0;
  }

  .legal-section h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
  }

  .legal-section p {
    line-height: 1.8;
    color: #4b5563;
    margin-bottom: 0.75rem;
  }

  .legal-section ul {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
  }

  .legal-section li {
    margin-bottom: 0.5rem;
    color: #4b5563;
    line-height: 1.6;
  }

  .legal-section a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .legal-section a:hover {
    color: #764ba2;
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .legal-section h2 {
      font-size: 1.3rem;
    }

    .legal-content {
      padding: 0 1rem;
    }
  }

  @media (prefers-color-scheme: dark) {
    .legal-section h2 {
      color: #f3f4f6;
    }

    .legal-section h3 {
      color: #e5e7eb;
    }

    .legal-section p,
    .legal-section li {
      color: #d1d5db;
    }
  }
</style>
@endsection
