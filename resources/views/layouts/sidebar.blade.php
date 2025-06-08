<div class="bg-light border-end" id="sidebar-wrapper">
    <div class="sidebar-heading p-3 border-bottom fw-bold">Dashboard</div>

    <div class="user-info p-3 border-bottom">
        <div class="fw-semibold">{{ auth()->user()->name }}</div>
        {{-- <div class="text-muted small">{{ auth()->user()->role->name }}</div> --}}
        <div class="text-muted small">{{ auth()->user()->created_at->format('d M Y') }}</div>
    </div>

    <div class="list-group list-group-flush">

        <!-- Sección: Navegación Principal -->
        <a href="/" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fas fa-home me-2" style="width: 20px;"></i>Inicio
        </a>
        <a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fas fa-tachometer-alt me-2" style="width: 20px;"></i>Panel Principal
        </a>

        <!-- Sección: Administración -->
        <div class="px-3 mt-3 text-uppercase small text-muted">Administración</div>
        <a href="{{ url('show-roles') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-user-tie me-2" style="width: 20px;"></i>Roles
        </a>
        <a href="{{ url('show-users') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-users me-2" style="width: 20px;"></i>Usuarios
        </a>

        <!-- Sección: Contenido -->
        <div class="px-3 mt-3 text-uppercase small text-muted">Contenido</div>
        <a href="{{ url('show-quote') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-book-bible me-2" style="width: 20px;"></i>Palabra de vida
        </a>
        <a href="{{ url('show-slider') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-sliders me-2" style="width: 20px;"></i>Banner Carrusel 
        </a>
        <a href="{{ url('show-categories') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-podcast me-2" style="width: 20px;"></i>PodCast
        </a>
        <a href="{{ url('show-schedule') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-clock me-2" style="width: 20px;"></i>Programación
        </a>
        <a href="{{ url('show-news') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-newspaper me-2" style="width: 20px;"></i>Mensaje de la semana
        </a>
        <a href="{{ url('show-looks') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
            <i class="fa-solid fa-earth-africa me-2" style="width: 20px;"></i>Mirada Afro
        </a>

    </div>
</div>
