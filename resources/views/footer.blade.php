<footer class="custom-footer py-10 bg-[var(--green-dark)] text-white dark:bg-gray-900 dark:text-gray-100">
  <div class="mx-auto max-w-7xl px-4">

    {{-- columnas principales --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Legal --}}
      <section aria-labelledby="footer-legal">
        <h2 id="footer-legal" class="text-sm uppercase tracking-wide font-extrabold text-yellow-400 mb-4">
          Legal
        </h2>
        <ul class="space-y-2">
          <li>
            <a href="{{ url('privacy') }}"
               class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                      focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                      focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-shield-halved"></i>
              <span>Política de tratamiento de datos personales</span>
            </a>
          </li>
          <li>
            <a href="{{ url('rights') }}"
               class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                      focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                      focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-gavel"></i>
              <span>Derechos del titular</span>
            </a>
          </li>
          <li>
            <a href="{{ url('pqr') }}"
               class="inline-flex items-center gap-2 text-white/90 hover:text-white focus:outline-none
                      focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2
                      focus-visible:ring-offset-[var(--green-dark)] dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-comments"></i>
              <span>PQR en línea</span>
            </a>
          </li>
        </ul>
      </section>

      {{-- Contacto --}}
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

      {{-- Otros enlaces (chips) --}}
      <section aria-labelledby="footer-links">
        <h2 id="footer-links" class="text-sm uppercase tracking-wide font-extrabold text-yellow-400 mb-4">
          Otros enlaces
        </h2>

        <div class="flex flex-wrap gap-2">
          @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

            <a href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Cerrar sesión"
               class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                      hover:bg-white/10 dark:hover:bg-white/5 transition
                      focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                      focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                      dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-right-from-bracket"></i>
              Cerrar sesión
            </a>

            <a href="{{ url('dashboard') }}" title="Dashboard"
               class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                      hover:bg-white/10 dark:hover:bg-white/5 transition
                      focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                      focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                      dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-table-columns"></i>
              Dashboard
            </a>
          @else
            <a href="{{ route('login') }}" title="Iniciar sesión"
               class="inline-flex items-center gap-2 rounded-full border border-white/35 px-3 py-1.5 text-sm
                      hover:bg-white/10 dark:hover:bg-white/5 transition
                      focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400
                      focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--green-dark)]
                      dark:focus-visible:ring-offset-gray-900">
              <i class="fa-solid fa-right-to-bracket"></i>
              Iniciar sesión
            </a>
          @endauth

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
    </div>

    {{-- separador + barra inferior --}}
    <hr class="mt-8 mb-4 border-white/25 dark:border-white/10">

    <div class="pt-1 flex flex-col md:flex-row items-center gap-3 text-center md:text-left">
      <p class="text-xs md:text-sm m-0">
        &copy; {{ date('Y') }} Derechos Reservados - Emancipación Cristiana Afro | Ver. 4.8
      </p>
      <img src="{{ asset('images/brands/logoda.png') }}" class="h-5 w-5 md:ml-auto" alt="Logo DA">
    </div>

  </div>
</footer>
