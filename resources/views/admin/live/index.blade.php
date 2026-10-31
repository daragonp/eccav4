@extends('layouts.panel')

@section('title', 'Culto en Vivo')
@section('pageheading', 'Culto en Vivo')

@section('datatable')
<div class="space-y-6 max-w-4xl">

    {{-- Mensajes de estado --}}
    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Override de un clic + archivar --}}
    <div class="card">
        <div class="card-body p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            @if ($live->mode === 'forzar_vivo')
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            @else
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-400"></span>
                            @endif
                        </span>
                        Control rápido de transmisión
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Modo actual:
                        <strong>
                            @switch($live->mode)
                                @case('forzar_vivo') Forzar en vivo @break
                                @case('forzar_oculto') Forzar oculto @break
                                @default Automático (por ventana horaria)
                            @endswitch
                        </strong>
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <form action="{{ route('live.admin.toggle') }}" method="POST">
                        @csrf
                        @if ($live->mode === 'forzar_vivo')
                            <button type="submit" class="btn btn-secondary">
                                <i class="fa-solid fa-stop mr-2"></i> Finalizar transmisión
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700 transition-colors">
                                <i class="fa-solid fa-tower-broadcast mr-2"></i> Estamos EN VIVO ahora
                            </button>
                        @endif
                    </form>
                    <form action="{{ route('live.admin.archive') }}" method="POST"
                          onsubmit="return confirm('¿Archivar la grabación actual como un culto dominical?');">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-box-archive mr-2"></i> Archivar como culto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de configuración --}}
    <form action="{{ route('live.admin.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Plataforma y ventana --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Transmisión</h2>
            </div>
            <div class="card-body p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="platform" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Plataforma principal (embebida)</label>
                    <select id="platform" name="platform" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                        <option value="youtube" @selected(old('platform', $live->platform) === 'youtube')>YouTube</option>
                        <option value="facebook" @selected(old('platform', $live->platform) === 'facebook')>Facebook</option>
                    </select>
                </div>
                <div>
                    <label for="mode" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Modo de visibilidad</label>
                    <select id="mode" name="mode" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                        <option value="auto" @selected(old('mode', $live->mode) === 'auto')>Automático (ventana horaria)</option>
                        <option value="forzar_vivo" @selected(old('mode', $live->mode) === 'forzar_vivo')>Forzar en vivo</option>
                        <option value="forzar_oculto" @selected(old('mode', $live->mode) === 'forzar_oculto')>Forzar oculto</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Días de transmisión</label>
                    @php
                        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
                        $selectedDays = old('window_days', $live->window_days ?? [$live->window_day ?? 7]);
                        if (!is_array($selectedDays)) {
                            $selectedDays = [(int) $selectedDays];
                        }
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach ($dias as $num => $nombre)
                            <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border cursor-pointer transition-all text-xs font-semibold
                                {{ in_array($num, $selectedDays) ? 'bg-emerald-50 border-emerald-400 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-600 dark:text-emerald-200 shadow-2xs' : 'bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                <input type="checkbox" name="window_days[]" value="{{ $num }}"
                                       @checked(in_array($num, $selectedDays))
                                       class="rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500">
                                {{ $nombre }}
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Selecciona uno o más días en los que se activa la ventana horaria automática.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="window_start" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Hora de inicio</label>
                        <input type="time" id="window_start" name="window_start"
                               value="{{ old('window_start', substr((string) $live->window_start, 0, 5)) }}"
                               class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label for="window_end" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Hora de fin</label>
                        <input type="time" id="window_end" name="window_end"
                               value="{{ old('window_end', substr((string) $live->window_end, 0, 5)) }}"
                               class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="title" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">Título del directo (opcional)</label>
                    <input type="text" id="title" name="title" maxlength="255"
                           value="{{ old('title', $live->title) }}"
                           placeholder="Culto Dominical"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
            </div>
        </div>

        {{-- Enlaces por plataforma --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Enlaces de las plataformas</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Solo YouTube se autodetecta. Las demás redes se muestran como enlaces y se activan por ventana u override manual.</p>
            </div>
            <div class="card-body p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="youtube_channel_id" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">ID de canal de YouTube</label>
                    <input type="text" id="youtube_channel_id" name="youtube_channel_id" maxlength="64"
                           value="{{ old('youtube_channel_id', $live->youtube_channel_id) }}"
                           placeholder="UCxxxxxxxxxxxxxxxxxxxxxx"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Para la autodetección del directo (requiere también la API key en el servidor).</p>
                </div>
                <div>
                    <label for="youtube_url" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de YouTube</label>
                    <input type="url" id="youtube_url" name="youtube_url" maxlength="500"
                           value="{{ old('youtube_url', $live->youtube_url) }}"
                           placeholder="https://youtube.com/@canal/live"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="facebook_url" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de Facebook</label>
                    <input type="url" id="facebook_url" name="facebook_url" maxlength="500"
                           value="{{ old('facebook_url', $live->facebook_url) }}"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="tiktok_url" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de TikTok</label>
                    <input type="url" id="tiktok_url" name="tiktok_url" maxlength="500"
                           value="{{ old('tiktok_url', $live->tiktok_url) }}"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="x_url" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de X</label>
                    <input type="url" id="x_url" name="x_url" maxlength="500"
                           value="{{ old('x_url', $live->x_url) }}"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="instagram_url" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de Instagram</label>
                    <input type="url" id="instagram_url" name="instagram_url" maxlength="500"
                           value="{{ old('instagram_url', $live->instagram_url) }}"
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                </div>
            </div>
        </div>

        {{-- Archivado --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Grabación y archivado</h2>
            </div>
            <div class="card-body p-5 space-y-5">
                <label class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                    <input type="checkbox" name="archivar" value="1" @checked(old('archivar', $live->archivar))
                           class="rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500">
                    Marcar la transmisión para archivar la grabación
                </label>
                <div>
                    <label for="url_grabacion" class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300">URL de la grabación</label>
                    <input type="url" id="url_grabacion" name="url_grabacion" maxlength="500"
                           value="{{ old('url_grabacion', $live->url_grabacion) }}"
                           placeholder="https://youtube.com/watch?v=..."
                           class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Al pulsar "Archivar como culto" se crea/actualiza el culto dominical del último domingo con esta URL.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar configuración
            </button>
        </div>
    </form>
</div>
@endsection
