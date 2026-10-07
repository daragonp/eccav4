{{-- WideStream Player: Barra Sticky Flotante (Dock) --}}
{{-- Reemplaza al reproductor personalizado anterior. La URL del embed se toma de la
     configuración (config/app.php -> stream_embed_url, por defecto el embed de WideStream). --}}
@php
    $wideStreamEmbed = config('app.stream_embed_url', 'https://widestream.app/embed/main');
    // Parámetros del dock sticky transparente solicitados por el servicio de streaming.
    $wideStreamSrc = $wideStreamEmbed
        . (str_contains($wideStreamEmbed, '?') ? '&' : '?')
        . 'theme=transparent&sticky=dock';
@endphp

<div style="position: fixed; bottom: 16px; left: 16px; right: 16px; z-index: 999999; display: flex; justify-content: center; pointer-events: none;">
  <div style="width: 100%; max-width: 960px; pointer-events: auto; filter: drop-shadow(0 12px 32px rgba(0, 0, 0, 0.6));">
    <iframe
      src="{{ $wideStreamSrc }}"
      width="100%"
      height="80"
      frameborder="0"
      allow="autoplay"
      title="Radio Emancipación Cristiana Afro - WideStream"
      style="width: 100%; height: 80px; border: none; border-radius: 16px; display: block; overflow: hidden;"
    ></iframe>
  </div>
</div>
