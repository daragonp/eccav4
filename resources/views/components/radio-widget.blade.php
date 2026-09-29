@props([
    'layout' => 'main',        // 'main' (80px), 'card' (190px), 'full' (380px), 'button' (48px)
    'theme' => null,           // null (default/dark), 'light', 'transparent'
    'width' => '100%',
    'height' => null,
    'class' => '',
    'title' => 'Radio Emancipación Cristiana Afro - WideStream',
])

@php
    $baseEmbed = config('app.stream_embed_url', 'https://widestream.app/embed/main');
    
    // Alturas predeterminadas por layout si no se especifica una custom
    $defaultHeights = [
        'main' => '80',
        'card' => '190',
        'full' => '380',
        'button' => '48',
    ];
    
    $resolvedHeight = $height ?? ($defaultHeights[$layout] ?? '80');
    
    // Construcción de query params
    $params = [];
    if (!empty($layout) && $layout !== 'main') {
        $params['layout'] = $layout;
    }
    if (!empty($theme)) {
        $params['theme'] = $theme;
    }
    
    $embedUrl = $baseEmbed . (count($params) > 0 ? '?' . http_build_query($params) : '');
@endphp

<div class="widestream-widget-wrapper {{ $class }}">
    <iframe
        src="{{ $embedUrl }}"
        width="{{ $width }}"
        height="{{ $resolvedHeight }}"
        frameborder="0"
        allow="autoplay"
        loading="lazy"
        title="{{ $title }}"
        style="border-radius: 16px; overflow: hidden; border: none; display: block;"
    ></iframe>
</div>
