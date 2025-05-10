<footer class="bg-dark text-light pt-5 custom-footer">
    <div class="container">
        <!-- Sección principal del footer -->
        <div class="row text-center text-md-start">
           
            <div class="col-md-4 mb-4 sizem">
                <h5 class="text-uppercase text-warning font-weight-bold">Legal</h5>
                <ul class="list-unstyled">
                    <li><a class="text-light" href="{{ url('privacy') }}">Política de tratamiento de datos
                            personales</a></li>
                    <li><a class="text-light" href="{{ url('rights') }}">Derechos del titular</a></li>
                    <li><a class="text-light" href="{{ url('pqr') }}">PQR en línea</a></li>
                </ul>
            </div>
            <!-- Contáctanos -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase text-warning font-weight-bold">Contáctanos</h5>
                <ul class="list-unstyled">
                    <li>
                        <p><a href="mailto:radio@emancipacioncristianaafro.org"
                                class="text-light">radio@emancipacioncristianaafro.org</a></p>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase text-warning font-weight-bold">Otros enlaces</h5>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    {{-- <a class="btn btn-warning btn-sm mb-2" href="{{ url('opinion') }}" target="">Mirada Afro</a> --}}
                    @auth
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <a class="btn btn-outline-light btn-sm mb-2" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            title="Cerrar sesión">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm mb-2" href="{{ url('dashboard') }}" title="Dashboard"><i
                                class="fa-solid fa-table-columns"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2" href="https://webmail1.hostinger.co/" target="_blank"
                            title="Correo"><i class="fas fa-envelope-square"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2" href="#" target="_blank" title="Ayuda"><i
                                class="fas fa-question-circle"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2"
                            href="https://tunein.com/radio/Radio-Emancipacin-Cristiana-Afro-s292735/" target="_blank"
                            title="TuneIn">
                            <img src="{{ asset('images/brands/logo_ink.webp') }}" width="20px" alt="">
                        </a>
                    @else
                        <a class="btn btn-outline-light btn-sm mb-2" href="{{ route('login') }}" title="Iniciar sesión"><i
                                class="fa-solid fa-right-to-bracket"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2" href="https://webmail1.hostinger.co/" target="_blank"
                            title="Correo"><i class="fas fa-envelope-square"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2" href="#" target="_blank" title="Ayuda"><i
                                class="fas fa-question-circle"></i></a>
                        <a class="btn btn-outline-light btn-sm mb-2"
                            href="https://tunein.com/radio/Radio-Emancipacin-Cristiana-Afro-s292735/" target="_blank"
                            title="TuneIn">
                            <img src="{{ asset('images/brands/tunein.webp') }}" width="20px" alt="">
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Línea de separación -->
        <hr class="bg-light mt-4">

        <!-- Copyright -->
        <div class="row text-center">
            <div class="col">
                <p class="mb-0">&copy; {{ date('Y') }} Derechos Reservados - Emancipación Cristiana Afro | Ver.
                    4.5.0a</p>
                <img src="{{ asset('images/brands/logoda.png') }}" width="20px" alt="">
            </div>
        </div>
    </div>
</footer>
