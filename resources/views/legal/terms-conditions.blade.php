@extends('layouts.main')

@section('title', 'Términos y Condiciones')

@section('page-hero')
  <div class="hero-content">
    <h1>Términos y Condiciones</h1>
    <p>Condiciones de uso de nuestro sitio web</p>
  </div>
@endsection

@section('content')
<div class="legal-content">
  <section class="legal-section">
    <h2>1. Aceptación de Términos</h2>
    <p>
      Al acceder y utilizar este sitio web, usted acepta estar vinculado por estos Términos y Condiciones. 
      Si no está de acuerdo con alguna parte de estos términos, le recomendamos no utilizar el sitio.
    </p>
  </section>

  <section class="legal-section">
    <h2>2. Licencia de Uso</h2>
    <p>
      Se le otorga una licencia limitada, no exclusiva, intransferible y revocable para:
    </p>
    <ul>
      <li>Acceder al sitio web con propósitos legales</li>
      <li>Descargar contenido para uso personal y no comercial</li>
      <li>Compartir contenido públicamente si se da crédito</li>
    </ul>
    <p>
      Esta licencia se revoca automáticamente si viola cualquiera de estos términos.
    </p>
  </section>

  <section class="legal-section">
    <h2>3. Prohibiciones</h2>
    <p>
      Usted NO puede:
    </p>
    <ul>
      <li>Reproducir, duplicar o copiar contenido sin permiso</li>
      <li>Modificar, traducir o crear obras derivadas</li>
      <li>Intentar obtener acceso no autorizado</li>
      <li>Utilizar técnicas de "scraping" o minería de datos</li>
      <li>Enviar spam, virus o código malicioso</li>
      <li>Acosar, amenazar o insultar a otros usuarios</li>
      <li>Realizar actividades ilegales o fraudulentas</li>
      <li>Usar bots automatizados (excepto buscadores autorizados)</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>4. Contenido del Usuario</h2>
    <p>
      Si publica contenido en nuestro sitio:
    </p>
    <ul>
      <li>Declara que es titular de los derechos</li>
      <li>Nos autoriza a publicar y distribuir el contenido</li>
      <li>Nos eximimos de responsabilidad por contenido que usted publique</li>
      <li>Nos reservamos el derecho de moderar o eliminar contenido inapropiado</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>5. Limitación de Responsabilidad</h2>
    <p>
      <strong>ECCA no es responsable por:</strong>
    </p>
    <ul>
      <li>Daños directos o indirectos de cualquier tipo</li>
      <li>Pérdida de datos, ganancias o ingresos</li>
      <li>Interrupciones o errores del sitio</li>
      <li>Acceso no autorizado a nuestros servidores</li>
      <li>Contenido de terceros o enlaces externos</li>
    </ul>
    <p>
      <strong>Exclusión:</strong> En jurisdicciones donde no se permita excluir responsabilidad, 
      la nuestra se limita al máximo permitido por ley.
    </p>
  </section>

  <section class="legal-section">
    <h2>6. Disclaimer (Exención de Garantía)</h2>
    <p>
      El sitio web se proporciona "TAL CUAL" sin garantías de ningún tipo, expresas o implícitas. 
      No garantizamos:
    </p>
    <ul>
      <li>Precisión o actualidad del contenido</li>
      <li>Funcionamiento ininterrumpido del servicio</li>
      <li>Ausencia de virus o código malicioso</li>
      <li>Compatibilidad con todos los dispositivos</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>7. Enlaces a Terceros</h2>
    <p>
      Nuestro sitio puede contener enlaces a sitios web de terceros. No somos responsables por:
    </p>
    <ul>
      <li>Contenido de sitios enlazados</li>
      <li>Políticas de privacidad de terceros</li>
      <li>Disponibilidad o funcionamiento de enlaces externos</li>
    </ul>
    <p>
      La inclusión de un enlace no implica endoso del sitio externo.
    </p>
  </section>

  <section class="legal-section">
    <h2>8. Propiedad Intelectual</h2>
    <p>
      Todo el contenido original (textos, imágenes, videos, diseño) es propiedad de ECCA y está protegido por:
    </p>
    <ul>
      <li>Derechos de autor colombianos</li>
      <li>Tratados internacionales (WIPO, ADPIC)</li>
      <li>Leyes de marcas registradas</li>
    </ul>
    <p>
      Está prohibida la reproducción, distribución o modificación sin autorización expresa.
    </p>
  </section>

  <section class="legal-section">
    <h2>9. Conducta del Usuario</h2>
    <p>
      El usuario se compromete a:
    </p>
    <ul>
      <li>Usar el sitio de manera legal y ética</li>
      <li>Respetar los derechos de otros usuarios</li>
      <li>No interferir con el funcionamiento del sitio</li>
      <li>Proporcionar información veraz en formularios</li>
      <li>Mantener confidencialidad de sus credenciales</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>10. Suspensión y Terminación</h2>
    <p>
      ECCA se reserva el derecho de:
    </p>
    <ul>
      <li>Suspender su acceso sin previo aviso</li>
      <li>Terminar servicios si viola estos términos</li>
      <li>Eliminar contenido que considere inapropiado</li>
      <li>Desactivar cuentas de usuarios infractores</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>11. Modificación de Términos</h2>
    <p>
      ECCA puede modificar estos términos en cualquier momento. Las modificaciones entrarán en vigor 
      inmediatamente después de su publicación. Su uso continuado del sitio implica aceptación de los nuevos términos.
    </p>
  </section>

  <section class="legal-section">
    <h2>12. Ley Aplicable</h2>
    <p>
      Estos términos se rigen por las leyes de la República de Colombia. 
      Cualquier disputa será resuelta por los tribunales colombianos.
    </p>
  </section>

  <section class="legal-section">
    <h2>13. Contacto</h2>
    <p>
      Para preguntas sobre estos términos, contáctenos:
    </p>
    <ul style="list-style: none; padding: 0;">
      <li>📧 Correo: <a href="mailto:legal@ecca.com">legal@ecca.com</a></li>
      <li>📞 Teléfono: +57 (1) XXXX-XXXX</li>
      <li>📍 Dirección: Bogotá D.C., Colombia</li>
    </ul>
  </section>

  <section class="legal-section" style="background: #f3f4f6; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #667eea;">
    <p style="margin: 0; font-size: 0.875rem; color: #4b5563;">
      <strong>Última actualización:</strong> {{ now()->format('d de F de Y') }}<br>
      <strong>Versión:</strong> 1.0
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
