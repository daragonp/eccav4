{{-- Banner "EN VIVO AHORA" de la home. Oculto por defecto; se muestra solo si
     el endpoint /api/live-status responde is_live=true. Se refresca cada 60s
     para que aparezca al comenzar el culto sin recargar la página. --}}
<div
    x-data="{
        live: false,
        cargar() {
            fetch('/api/live-status', { headers: { 'Accept': 'application/json' } })
                .then(r => r.ok ? r.json() : null)
                .then(d => { this.live = !!(d && d.is_live); })
                .catch(() => { this.live = false; });
        }
    }"
    x-init="cargar(); setInterval(() => cargar(), 60000)"
    x-show="live"
    x-cloak
    class="mb-6"
>
    <div class="page-container">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl bg-emerald-600 text-white px-5 py-4 shadow-lg border border-emerald-400/40">
            <div class="flex items-center gap-3 min-w-0">
                <span class="relative flex h-3 w-3 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                </span>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-100">En vivo ahora</p>
                    <p class="text-base sm:text-lg font-black tracking-tight truncate">Culto Dominical</p>
                </div>
            </div>
            <a href="{{ route('live.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-emerald-700 font-bold px-4 py-2 text-sm shadow-xs hover:bg-emerald-50 transition-colors shrink-0">
                <i class="fa-solid fa-play"></i>
                Ver ahora
            </a>
        </div>
    </div>
</div>
