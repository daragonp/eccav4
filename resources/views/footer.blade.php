<footer class="bg-dark text-light py-5 custom-footer">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Legal Section -->
            <div class="col">
                <div>
                    <h5 class="text-uppercase text-warning fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled legal-links">
                        <li>
                            <a class="text-light text-decoration-none mb-2 d-block" href="{{ url('privacy') }}">
                                <i class="fa-solid fa-shield-alt"></i> Política de tratamiento de datos personales
                            </a>
                        </li>
                        <li>
                            <a class="text-light text-decoration-none mb-2 d-block" href="{{ url('rights') }}">
                                <i class="fa-solid fa-gavel"></i> Derechos del titular
                            </a>
                        </li>
                        <li>
                            <a class="text-light text-decoration-none mb-2 d-block" href="{{ url('pqr') }}">
                                <i class="fa-solid fa-comment-alt"></i> PQR en línea
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="col">
                <div>
                    <h5 class="text-uppercase text-warning fw-bold mb-3">Contáctanos</h5>
                    <ul class="list-unstyled contact-links">
                        <li>
                            <p class="mb-2">
                                <a href="mailto:radio@emancipacioncristianaafro.org"
                                   class="text-light text-decoration-none">
                                    <i class="fa-solid fa-envelope"></i> radio@emancipacioncristianaafro.org
                                </a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Other Links Section -->
            <div class="col">
                <div>
                    <h5 class="text-uppercase text-warning fw-bold mb-3">Otros enlaces</h5>
                    <div class="d-flex flex-wrap gap-2 other-links">
                        @auth
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <a class="btn btn-outline-light btn-sm mb-2" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               title="Cerrar sesión">
                                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2" href="{{ url('dashboard') }}" title="Dashboard">
                                <i class="fa-solid fa-table-columns"></i> Dashboard
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2" href="https://webmail1.hostinger.co/" target="_blank"
                               title="Correo">
                                <i class="fas fa-envelope-square"></i> Correo
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2" href="#" target="_blank" title="Ayuda">
                                <i class="fas fa-question-circle"></i> Ayuda
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2"
                               href="https://tunein.com/radio/Radio-Emancipacin-Cristiana-Afro-s292735/" target="_blank"
                               title="TuneIn">
                                <img src="{{ asset('images/brands/tunein.webp') }}" width="20" height="20" class="align-middle" alt="TuneIn"> TuneIn
                            </a>
                        @else
                            <a class="btn btn-outline-light btn-sm mb-2" href="{{ route('login') }}" title="Iniciar sesión">
                                <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2" href="https://webmail1.hostinger.co/" target="_blank"
                               title="Correo">
                                <i class="fas fa-envelope-square"></i> Correo
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2" href="#" target="_blank" title="Ayuda">
                                <i class="fas fa-question-circle"></i> Ayuda
                            </a>
                            <a class="btn btn-outline-light btn-sm mb-2"
                               href="https://tunein.com/radio/Radio-Emancipacin-Cristiana-Afro-s292735/" target="_blank"
                               title="TuneIn">
                                <img src="{{ asset('images/brands/tunein.webp') }}" width="20" height="20" class="align-middle" alt="TuneIn"> TuneIn
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-light mt-4 mb-3">
        <div class="d-flex justify-content-center align-items-center flex-column flex-md-row text-center text-md-start">
            <p class="mb-0 me-md-auto copyright">&copy; {{ date('Y') }} Derechos Reservados - Emancipación Cristiana Afro | Ver. 4.7.8</p>
            <img src="{{ asset('images/brands/logoda.png') }}" width="20" height="20" class="ms-md-2 logo-da" alt="Logo DA">
        </div>
    </div>
</footer>
