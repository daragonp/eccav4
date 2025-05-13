<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="#">Inicio <span class="visually-hidden">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> Mi Cuenta
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('profile') }}"><i class="fa-solid fa-wrench icon-yellow"></i>
                        Ajustes</a>
                    <a class="dropdown-item" href="{{ url('suggestions') }}" data-bs-toggle="modal"
                        data-bs-target="#suggestionsModal"><i class="fa-solid fa-comment icon-purple"></i>Sugerencias</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('logout') }}" data-bs-toggle="modal"
                        data-bs-target="#logoutModal"><i
                            class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>Salir</a>
                </div>
            </li>
        </ul>
    </div>
</nav>