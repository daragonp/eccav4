@if ($type === 'video')
    <video width="200" height="100" nocontrols>
        <source src="{{ asset($url) }}" type="video/mp4">
        <source src="{{ asset($url) }}" type="video/ogg">
        Tu navegador no soporta videos.
    </video>
@elseif ($type === 'audio')
    <audio controls style="width: 200px;">
        <source src="{{ asset($url) }}" type="audio/mpeg">
        Tu navegador no soporta audios.
    </audio>
@elseif ($type === 'image')
    <img src="{{ asset($url) }}" alt="Multimedia" width="150" height="100">
@else
    <p>Tipo de medio no soportado.</p>
@endif
