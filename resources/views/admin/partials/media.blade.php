@php
  $type = $type ?? 'image';
  $alt  = $alt  ?? 'media';
@endphp

@if ($type === 'image')
  <a href="{{ asset($url) }}" target="_blank" class="inline-flex">
    <img src="{{ asset($url) }}"
         alt="{{ $alt }}"
         class="h-12 w-12 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800" />
  </a>
@elseif ($type === 'audio')
  <audio controls class="w-40">
    <source src="{{ asset($url) }}">
    Tu navegador no soporta audio HTML5.
  </audio>
@else
  <a href="{{ asset($url) }}" target="_blank" class="text-[var(--green-dark)] underline">Ver archivo</a>
@endif
