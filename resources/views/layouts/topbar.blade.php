<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="/"><img src="{{ asset('images/logo/logo.png') }}" width="100px" alt="Logo"
            class="logo-img"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('dashboard') }}"><i class="fa-solid fa-gauge fa-2x" style="color: #d7c823;"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-roles') }}">Roles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-users') }}">Usuarios</a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-worship') }}">Palabra de vida</a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-quote') }}">Versículos</a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-categories') }}">PodCast</a>
            </li>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-schedule') }}">Programación</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-news') }}">Mensaje de la semana</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('show-looks') }}">Mirada afro</a>
            </li>
            <li>
                <div class="navbar-right">
                    <ul class="navbar-nav">
                        <li class="item d-flex">
                            <img src="{{ asset('images/user/user-solid.svg') }}" width="25px" height="25px" alt="Usuario"
                                class="roundimg">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones</a>
                            <div class="dropdown-menu dropdown-menu-end  mt-5" aria-labelledby="userDropdown">
                                {{ auth()->user()->name }}
                                <hr>
                                <a class="dropdown-item" href="{{ url('profile') }}"><i class="fa-solid fa-wrench icon-yellow"></i> Ajustes</a>
                                <a class="dropdown-item" href="{{ url('suggestions') }}" data-toggle="modal" data-target="#suggestionsModal"><i class="fa-solid fa-comment icon-purple"></i>Sugerencias</a>
                                <a class="dropdown-item" href="{{ url('logout') }}" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Salir</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
