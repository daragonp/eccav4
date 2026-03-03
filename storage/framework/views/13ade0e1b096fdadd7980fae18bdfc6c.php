<div 
  id="privacy-notice-container"
  x-show="privacyNoticeVisible"
  x-transition
  class="privacy-notice-wrapper"
  style="display: none;"
>
  <div class="privacy-notice-overlay"></div>
  
  <div class="privacy-notice-modal">
    
    <div class="privacy-notice-header">
      <h2 class="privacy-notice-title">
        <i class="fas fa-shield-alt"></i>
        Aviso de Privacidad y Tratamiento de Datos
      </h2>
      <button 
        @click="closePrivacyNotice()" 
        type="button"
        class="privacy-notice-close"
        aria-label="Cerrar aviso"
        title="Cerrar"
      >
        <i class="fas fa-times"></i>
      </button>
    </div>

    
    <div class="privacy-notice-content">
      <section class="privacy-section">
        <h3><i class="fas fa-info-circle"></i> Información del Responsable del Tratamiento</h3>
        <p>
          <strong>Emancipación Cristiana Afro (ECCA)</strong> es el responsable del tratamiento de sus datos personales. 
          Cumplimos con la Ley 1581 de 2012 y sus decretos reglamentarios en Colombia.
        </p>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-database"></i> ¿Qué Datos Recopilamos?</h3>
        <ul>
          <li><strong>Datos de Identificación:</strong> Nombre, correo electrónico, teléfono</li>
          <li><strong>Datos de Navegación:</strong> Dirección IP, tipo de navegador, páginas visitadas</li>
          <li><strong>Cookies:</strong> Para mejorar su experiencia de navegación</li>
          <li><strong>Datos de Localización:</strong> Si usted lo autoriza explícitamente</li>
        </ul>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-lock"></i> Finalidad del Tratamiento</h3>
        <p>
          Utilizamos sus datos personales para:
        </p>
        <ul>
          <li>Proporcionar y mejorar nuestros servicios</li>
          <li>Enviar comunicaciones informativas y promocionales (con su consentimiento)</li>
          <li>Cumplir con obligaciones legales y regulatorias</li>
          <li>Garantizar la seguridad de nuestro sitio web</li>
          <li>Realizar análisis estadísticos y mejorar la experiencia del usuario</li>
        </ul>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-cookie-bite"></i> Cookies y Tecnologías Similares</h3>
        <p>
          Utilizamos cookies y similares para:
        </p>
        <ul>
          <li><strong>Cookies Esenciales:</strong> Para el funcionamiento del sitio (sesiones, seguridad)</li>
          <li><strong>Cookies de Análisis:</strong> Para entender cómo usa el sitio</li>
          <li><strong>Cookies de Personalización:</strong> Para recordar sus preferencias</li>
          <li><strong>Cookies Publicitarias:</strong> Para mostrar contenido relevante</li>
        </ul>
        <p>
          Puede rechazar cookies no esenciales en cualquier momento. Sin embargo, esto puede afectar su experiencia de navegación.
        </p>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-share-alt"></i> Compartición de Datos</h3>
        <p>
          Sus datos <strong>NO serán compartidos con terceros</strong> sin su consentimiento explícito, 
          excepto cuando sea requerido por ley o para:
        </p>
        <ul>
          <li>Proveedores de servicios que nos ayudan a operar el sitio</li>
          <li>Autoridades competentes cuando lo requiera la ley</li>
        </ul>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-user-shield"></i> Derechos del Titular</h3>
        <p>
          Conforme a la legislación colombiana, usted tiene derecho a:
        </p>
        <ul>
          <li><strong>Acceder</strong> a sus datos personales</li>
          <li><strong>Rectificar</strong> información inexacta</li>
          <li><strong>Solicitar supresión</strong> de sus datos (derecho al olvido)</li>
          <li><strong>Oponerme</strong> al procesamiento de mis datos</li>
          <li><strong>Revocar</strong> el consentimiento otorgado</li>
        </ul>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-envelope"></i> Cómo Ejercer sus Derechos</h3>
        <p>
          Para ejercer cualquiera de estos derechos, contáctenos a:
        </p>
        <ul style="list-style: none; padding: 0;">
          <li>📧 <strong>Correo:</strong> <a href="mailto:info@ecca.com">info@ecca.com</a></li>
          <li>📞 <strong>Teléfono:</strong> +57 (1) XXXX-XXXX</li>
          <li>📍 <strong>Dirección:</strong> Bogotá D.C., Colombia</li>
        </ul>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-shield-alt"></i> Seguridad de Datos</h3>
        <p>
          Implementamos medidas de seguridad técnicas y administrativas para proteger sus datos contra 
          acceso no autorizado, alteración o destrucción.
        </p>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-history"></i> Cambios en este Aviso</h3>
        <p>
          Nos reservamos el derecho de actualizar este aviso de privacidad en cualquier momento. 
          Le notificaremos de cambios significativos mediante este sitio web.
        </p>
      </section>

      <section class="privacy-section">
        <h3><i class="fas fa-file-pdf"></i> Documentos Legales</h3>
        <p>
          Consulte nuestros documentos completos:
        </p>
        <ul style="list-style: none; padding: 0;">
          <li><a href="<?php echo e(route('privacy-policy')); ?>" target="_blank" class="privacy-link">
            <i class="fas fa-external-link-alt"></i> Política de Privacidad Completa
          </a></li>
          <li><a href="<?php echo e(route('cookies-policy')); ?>" target="_blank" class="privacy-link">
            <i class="fas fa-external-link-alt"></i> Política de Cookies
          </a></li>
          <li><a href="<?php echo e(route('terms-conditions')); ?>" target="_blank" class="privacy-link">
            <i class="fas fa-external-link-alt"></i> Términos y Condiciones
          </a></li>
        </ul>
      </section>

      <section class="privacy-section" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(0,0,0,0.1);">
        <p style="font-size: 0.875rem; color: rgba(0,0,0,0.6);">
          <strong>Versión:</strong> 1.0<br>
          <strong>Última actualización:</strong> <?php echo e(now()->format('d/m/Y')); ?>

        </p>
      </section>
    </div>

    
    <div class="privacy-notice-footer">
      <button 
        @click="openPrivacyPolicy()" 
        type="button"
        class="privacy-notice-btn privacy-notice-btn-secondary"
      >
        <i class="fas fa-file-alt"></i>
        Leer Política Completa
      </button>
      
      <button 
        @click="acceptPrivacyNotice()" 
        type="button"
        class="privacy-notice-btn privacy-notice-btn-primary"
      >
        <i class="fas fa-check-circle"></i>
        Aceptar y Continuar
      </button>
    </div>
  </div>
</div>
<?php /**PATH /Library/Proyectos/eccav4/resources/views/privacy-notice.blade.php ENDPATH**/ ?>