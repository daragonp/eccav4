<header class="">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand order-lg-2" href="/">
                <img src="{{ asset('images/logo/logo.png') }}" alt="ECCA_LOGO">
            </a>
            <button class="navbar-toggler order-lg-1" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center order-lg-3" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Conócenos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a href="{{ url('mision') }}">Misión</a></li>
                            <li><a href="{{ url('objetivos') }}">Objetivo</a></li>
                            <li><a href="{{ url('declaracion') }}">Declaración de fe</a></li>
                            <li><a href="{{ url('meta') }}">Meta</a></li>
                            <li><a href="{{ url('mensajero') }}">Mensajero veloz</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('worship-home') }}">Palabras de vida</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('lumbrera') }}">Programas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>