<header class="header">
    <div class="logo">
      <img src="{{ asset('images/logo/logo.png') }}" alt="Logo ECCA" />
    </div>
    <nav class="navbar">
      <ul class="nav-links">
        <li><a href="/">Inicio</a></li>
        <li class="dropdown">
          <a href="#">Conócenos <i class="fas fa-caret-down"></i></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('mision') }}">Misión</a></li>
            <li><a href="{{ url('objetivos') }}">Objetivo</a></li>
            <li><a href="{{ url('declaracion') }}">Declaración de fe</a></li>
            <li><a href="{{ url('meta') }}">Meta</a></li>
            <li><a href="{{ url('mensajero') }}">Mensajero veloz</a></li>
          </ul>
        </li>
        <li><a href="{{ url('worship-home') }}">Palabras de Vida</a></li>
        <li><a href="{{ url('lumbrera') }}">Programas</a></li>
      </ul>
      <button class="menu-toggle"><i class="fas fa-bars"></i></button>
    </nav>
  </header>
