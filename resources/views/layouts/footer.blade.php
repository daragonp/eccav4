<footer class="custom-footer py-10 bg-[var(--green-dark)] text-white dark:bg-gray-900 dark:text-gray-100">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <section aria-labelledby="footer-legal">
                <h2 id="footer-legal" class="text-sm uppercase tracking-wide font-extrabold text-yellow-400 mb-4">
                    Legal
                </h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('privacy') }}"
                            class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                            focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                            focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Política de tratamiento de datos personales</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rights') }}"
                            class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                            focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                            focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
                            <i class="fa-solid fa-gavel"></i>
                            <span>Derechos del titular</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pqr') }}"
                            class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                            focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                            focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
                            <i class="fa-solid fa-comments"></i>
                            <span>PQR en línea</span>
                        </a>
                    </li>
                </ul>
            </section>
            <section aria-labelledby="footer-contact">
                <h2 id="footer-contact" class="text-sm uppercase tracking-wide font-extrabold text-yellow-400 mb-4">
                    Contáctanos
                </h2>
                <ul class="space-y-2">
                    <li>
                        <a href="mailto:radio@emancipacioncristianaafro.org"
                            class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                            focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                            focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
                            <i class="fa-solid fa-envelope"></i>
                            <span>radio@emancipacioncristianaafro.org</span>
                        </a>
                    </li>
                </ul>
            </section>
            <section aria-labelledby="footer-links">
                <h2 id="footer-links" class="text-sm uppercase tracking-wide font-extrabold text-yellow-400 mb-4">
                    Otros enlaces
                </h2>
                <div class="flex flex-wrap gap-2">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Cerrar sesión"
                        class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                        hover:bg-white/10 dark:hover:bg-white/5 transition
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                        focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                        dark:focus-visible:ring-offset-gray-900">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Cerrar sesión
                    </a>
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                        class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                        hover:bg-white/10 dark:hover:bg-white/5 transition
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                        focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                        dark:focus-visible:ring-offset-gray-900">
                        <i class="fa-solid fa-table-columns"></i>
                        Dashboard
                    </a>
                    <a href="https://webmail1.hostinger.co/" target="_blank" rel="noopener" title="Correo"
                        class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                        hover:bg-white/10 dark:hover:bg-white/5 transition
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                        focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                        dark:focus-visible:ring-offset-gray-900">
                        <i class="fa-solid fa-square-envelope"></i>
                        Correo
                    </a>
                    <a href="#" target="_blank" rel="noopener" title="Ayuda"
                        class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                        hover:bg-white/10 dark:hover:bg-white/5 transition
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                        focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                        dark:focus-visible:ring-offset-gray-900">
                        <i class="fa-solid fa-circle-question"></i>
                        Ayuda
                    </a>
                    <a href="https://tunein.com/radio/Radio-Emancipacin-Cristiana-Afro-s292735/"
                        target="_blank" rel="noopener" title="TuneIn"
                        class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                        hover:bg-white/10 dark:hover:bg-white/5 transition
                        focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                        focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                        dark:focus-visible:ring-offset-gray-900">
                        <img src="{{ asset('images/brands/tunein.webp') }}" class="h-4 w-4" alt="TuneIn">
                        TuneIn
                    </a>
                </div>
            </section>
        @php
            $versionService = app(\App\Services\VersionService::class);
            $footerVersion = $versionService->getVersion();
            $footerCommit = $versionService->getShortCommit();
            $footerCommitUrl = $versionService->getCommitUrl();
        @endphp
        <hr class="mt-8 mb-4 border-white/25 dark:border-white/10">
        <div class="pt-1 flex flex-col md:flex-row items-center justify-between gap-3 text-center md:text-left text-xs md:text-sm">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                <span>&copy; {{ now()->year }} Derechos Reservados - Emancipación Cristiana Afro</span>
                <span class="opacity-60">|</span>
                <a href="{{ route('about') }}" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/15 hover:bg-white/25 text-yellow-300 font-mono text-xs transition" title="Ver información del sistema y registro de cambios">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    {{ $footerVersion }}
                </a>
                @if($footerCommitUrl)
                    <a href="{{ $footerCommitUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 opacity-80 hover:opacity-100 hover:text-white transition font-mono text-xs" title="Ver compilación en GitHub">
                        <i class="fa-brands fa-github text-xs"></i>
                        <span>{{ $footerCommit }}</span>
                    </a>
                @endif
            </div>
            <img src="{{ asset('images/brands/logoda.png') }}" class="h-5 w-5 md:ml-auto" alt="Logo DA">
        </div>
    </div>
</footer>
