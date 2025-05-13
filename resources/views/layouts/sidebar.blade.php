<div class="bg-light border-end" id="sidebar-wrapper">
    <div class="sidebar-heading">Dashboard</div>
    <div class="user-info p-3">
        <div class="font-weight-bold">{{ auth()->user()->name }}</div>
        {{-- <div class="text-muted small">{{ auth()->user()->role->name }}</div> --}}
        <div class="text-muted small">{{ auth()->user()->created_at }}</div>
    </div>
    <div class="list-group list-group-flush">
        <a href="/" class="list-group-item list-group-item-action bg-light">
            <i class="fas fa-home me-2"></i>Home
        </a>
        <a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
        <a href="{{ url('show-roles') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-user-tie me-2"></i>Roles
        </a>
        <a href="{{ url('show-users') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-users me-2"></i>Usuarios
        </a>
        <a href="{{ url('show-quote') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-book-bible me-2"></i>Palabra de vida
        </a>
        <a href="{{ url('show-categories') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-podcast me-2"></i>PodCast
        </a>
        <a href="{{ url('show-schedule') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-clock me-2"></i>Programación
        </a>
        <a href="{{ url('show-news') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-newspaper me-2"></i>Mensaje de la semana
        </a>
        <a href="{{ url('show-looks') }}" class="list-group-item list-group-item-action bg-light">
            <i class="fa-solid fa-earth-africa me-2"></i>Mirada afro
        </a>
    </div>
</div>