@extends('layouts.main')

@section('title', 'Política de Privacidad')

@section('page-hero')
  <div class="hero-content">
    <h1>Política de Privacidad</h1>
    <p>Última actualización: {{ now()->format('d de F de Y') }}</p>
  </div>
@endsection

@section('content')
<div class="legal-content">
  <section class="legal-section">
    <h2>1. Responsable del Tratamiento de Datos</h2>
    <p>
      <strong>Emancipación Cristiana Afro (ECCA)</strong> es la organización responsable del tratamiento 
      de sus datos personales conforme a las disposiciones de la Ley 1581 de 2012 y sus decretos reglamentarios.
    </p>
    <p>
      <strong>Contacto:</strong><br>
      📧 Correo: <a href="mailto:info@ecca.com">info@ecca.com</a><br>
      📞 Teléfono: +57 (1) XXXX-XXXX<br>
      📍 Dirección: Bogotá D.C., Colombia
    </p>
  </section>

  <section class="legal-section">
    <h2>2. Información que Recopilamos</h2>
    <h3>2.1 Datos Personales Directos</h3>
    <ul>
      <li>Nombre completo</li>
      <li>Correo electrónico</li>
      <li>Número de teléfono</li>
      <li>Dirección física</li>
      <li>Datos de identificación (cédula, pasaporte, etc.)</li>
      <li>Información de pago (si aplica)</li>
    </ul>

    <h3>2.2 Datos de Navegación y Técnicos</h3>
    <ul>
      <li>Dirección IP</li>
      <li>Tipo de navegador y dispositivo</li>
      <li>Páginas visitadas y tiempo de permanencia</li>
      <li>Información de cookies y tecnologías similares</li>
      <li>Sistema operativo</li>
      <li>Datos de localización (solo si autoriza)</li>
    </ul>

    <h3>2.3 Datos de Comunicación</h3>
    <ul>
      <li>Contenido de mensajes y consultas</li>
      <li>Registros de interacciones</li>
      <li>Feedback y comentarios</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>3. Finalidad del Tratamiento</h2>
    <p>Utilizamos sus datos personales para:</p>
    <ul>
      <li>Proporcionar y mejorar nuestros servicios</li>
      <li>Procesar solicitudes y transacciones</li>
      <li>Enviar comunicaciones informativas (previa autorización)</li>
      <li>Enviar comunicaciones promocionales (previa autorización)</li>
      <li>Cumplir con obligaciones legales y regulatorias</li>
      <li>Garantizar la seguridad del sitio web</li>
      <li>Prevenir fraude y actividades ilegales</li>
      <li>Realizar análisis estadísticos y mejora de UX</li>
      <li>Personalizar su experiencia de navegación</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>4. Fundamento Legal del Tratamiento</h2>
    <p>El tratamiento de sus datos se basa en:</p>
    <ul>
      <li><strong>Consentimiento previo, expreso e informado:</strong> Usted autoriza expresamente el tratamiento de sus datos</li>
      <li><strong>Cumplimiento de obligaciones legales:</strong> Cuando lo requiera la ley colombiana</li>
      <li><strong>Ejecución de contrato:</strong> Para prestar servicios contratados</li>
      <li><strong>Interés legítimo:</strong> Para mejorar nuestros servicios (cuando sea proporcional)</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>5. Compartición de Datos</h2>
    <p>
      <strong>Sus datos NO serán compartidos con terceros sin su consentimiento explícito</strong>, 
      excepto en los siguientes casos:
    </p>
    <ul>
      <li><strong>Proveedores de servicios:</strong> Empresas que nos ayudan a operar (hosting, email, análisis)</li>
      <li><strong>Requisito legal:</strong> Cuando autoridades competentes lo requieran</li>
      <li><strong>Socios comerciales:</strong> Solo con su autorización previa y específica</li>
      <li><strong>Transferencia de negocio:</strong> En caso de fusión, adquisición o venta de activos</li>
    </ul>
    <p>
      Todos nuestros proveedores están obligados a mantener confidencialidad y implementar medidas de seguridad equivalentes.
    </p>
  </section>

  <section class="legal-section">
    <h2>6. Seguridad de los Datos</h2>
    <p>
      Implementamos medidas de seguridad técnicas y administrativas para proteger sus datos contra:
    </p>
    <ul>
      <li>Acceso no autorizado</li>
      <li>Alteración o destrucción</li>
      <li>Pérdida accidental</li>
      <li>Divulgación no autorizada</li>
      <li>Ataque cibernético</li>
    </ul>
    <p>
      <strong>Medidas implementadas:</strong>
    </p>
    <ul>
      <li>Encriptación SSL/TLS en todo el sitio</li>
      <li>Autenticación y control de acceso</li>
      <li>Firewalls y sistemas de detección de intrusiones</li>
      <li>Copias de seguridad regulares</li>
      <li>Políticas de acceso restringido</li>
      <li>Auditorías de seguridad periódicas</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>7. Retención de Datos</h2>
    <p>
      Conservamos sus datos personales durante el tiempo necesario para:
    </p>
    <ul>
      <li>Cumplir con la finalidad para la cual fueron recopilados</li>
      <li>Satisfacer requisitos legales y fiscales</li>
      <li>Resolver disputas y ejecutar acuerdos</li>
      <li>Proteger los derechos e intereses legales</li>
    </ul>
    <p>
      Una vez alcanzada la finalidad, procederemos a la eliminación segura de los datos, 
      a menos que la ley requiera su conservación prolongada.
    </p>
  </section>

  <section class="legal-section">
    <h2>8. Derechos del Titular</h2>
    <p>
      Conforme a la Ley 1581 de 2012, usted tiene derecho a:
    </p>
    <ul>
      <li>
        <strong>Acceso:</strong> Conocer qué datos personales tenemos sobre usted
      </li>
      <li>
        <strong>Rectificación:</strong> Corregir datos inexactos o incompletos
      </li>
      <li>
        <strong>Supresión:</strong> Solicitar la eliminación de sus datos (derecho al olvido), 
        salvo cuando exista obligación legal de mantenerlos
      </li>
      <li>
        <strong>Oposición:</strong> Rechazar el procesamiento de sus datos para ciertos fines
      </li>
      <li>
        <strong>Revocación del consentimiento:</strong> Retirar la autorización otorgada en cualquier momento
      </li>
      <li>
        <strong>Portabilidad:</strong> Recibir sus datos en formato estructurado para transferirlos a otro responsable
      </li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>9. Cómo Ejercer sus Derechos</h2>
    <p>
      Para ejercer cualquiera de los derechos mencionados, envíe una solicitud escrita a:
    </p>
    <ul style="list-style: none; padding: 0;">
      <li>📧 <strong>Correo:</strong> <a href="mailto:privacy@ecca.com">privacy@ecca.com</a></li>
      <li>📞 <strong>Teléfono:</strong> +57 (1) XXXX-XXXX</li>
      <li>📍 <strong>Dirección:</strong> Bogotá D.C., Colombia</li>
    </ul>
    <p>
      <strong>Plazo de respuesta:</strong> Responderemos dentro de 10 días hábiles conforme a la normativa vigente.
    </p>
    <p>
      <strong>Requisitos de la solicitud:</strong>
    </p>
    <ul>
      <li>Identificación clara del solicitante</li>
      <li>Descripción detallada de lo solicitado</li>
      <li>Datos de contacto para la respuesta</li>
      <li>Firma o confirmación de autenticidad</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>10. Cookies y Tecnologías de Rastreo</h2>
    <p>
      Consulte nuestra <a href="{{ route('cookies-policy') }}">Política de Cookies</a> para información 
      completa sobre el uso de cookies y tecnologías similares.
    </p>
  </section>

  <section class="legal-section">
    <h2>11. Cambios en esta Política</h2>
    <p>
      Nos reservamos el derecho de actualizar esta política de privacidad en cualquier momento. 
      Los cambios significativos le serán notificados mediante:
    </p>
    <ul>
      <li>Publicación en este sitio web</li>
      <li>Correo electrónico (si tenemos contacto)</li>
      <li>Banner notificador en el sitio</li>
    </ul>
    <p>
      La fecha de última actualización aparece al inicio de este documento.
    </p>
  </section>

  <section class="legal-section">
    <h2>12. Autoridades Competentes</h2>
    <p>
      En caso de conflicto relacionado con el tratamiento de datos personales, puede dirigirse a:
    </p>
    <ul style="list-style: none; padding: 0;">
      <li>
        <strong>Superintendencia de Industria y Comercio (SIC)</strong><br>
        🌐 <a href="https://www.sic.gov.co" target="_blank">www.sic.gov.co</a><br>
        📍 Bogotá D.C., Colombia
      </li>
      <li style="margin-top: 1rem;">
        <strong>Defensoría del Consumidor</strong><br>
        🌐 <a href="https://www.defensoria.gov.co" target="_blank">www.defensoria.gov.co</a>
      </li>
    </ul>
  </section>

  <section class="legal-section" style="background: #f3f4f6; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #667eea;">
    <h2 style="margin-top: 0;">Contacto de Privacidad</h2>
    <p style="margin: 0.5rem 0;">
      Si tiene preguntas, inquietudes o desea ejercer sus derechos sobre el tratamiento de sus datos personales, 
      no dude en contactarnos:
    </p>
    <ul style="list-style: none; padding: 0.75rem 0 0 0;">
      <li>📧 <a href="mailto:privacy@ecca.com">privacy@ecca.com</a></li>
      <li>📞 +57 (1) XXXX-XXXX</li>
      <li>🕐 Horario: Lunes a Viernes, 9:00 AM - 5:00 PM (Hora Colombia)</li>
    </ul>
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
