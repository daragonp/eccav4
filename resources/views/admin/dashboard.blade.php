@extends('layouts.panel')


@section('title', 'Panel principal')
@section('pageheading', 'Panel principal')


@section('datatable')
<!-- Tarjetas de estadísticas mejoradas -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
    <div class="card-stats-users group relative overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="card-body relative">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>12%
                </span>
            </div>
            <div class="text-xs uppercase text-slate-500 dark:text-slate-400 mb-1">Usuarios</div>
            <div class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $stats['users'] ?? 0 }}</div>
            <div class="text-xs text-slate-600 dark:text-slate-400">{{ $stats['users_change'] ?? '12 nuevos este mes' }}</div>
            <div class="mt-3">
                <a href="{{ url('show-users') }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Gestionar usuarios
                    <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-stats-verses group relative overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="card-body relative">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2 bg-white/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-book-bible text-white"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-white/20 text-white rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>8%
                </span>
            </div>
            <div class="text-xs uppercase opacity-80 mb-1">Versículos</div>
            <div class="text-3xl font-extrabold text-white mb-2">{{ $stats['verses'] ?? 0 }}</div>
            <div class="text-xs opacity-80">{{ $stats['verses_change'] ?? '8 nuevos este mes' }}</div>
            <div class="mt-3">
                <a href="{{ url('show-quote') }}" class="inline-flex items-center text-sm font-medium text-white hover:underline transition-colors">
                    Ver versículos
                    <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-stats-schedules group relative overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="card-body relative">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2 bg-white/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-white/20 text-white rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>5%
                </span>
            </div>
            <div class="text-xs uppercase opacity-80 mb-1">Programación</div>
            <div class="text-3xl font-extrabold text-white mb-2">{{ $stats['schedules'] ?? 0 }}</div>
            <div class="text-xs opacity-80">{{ $stats['schedules_change'] ?? '5 nuevos este mes' }}</div>
            <div class="mt-3">
                <a href="{{ url('show-schedule') }}" class="inline-flex items-center text-sm font-medium text-white hover:underline transition-colors">
                    Ver parrilla
                    <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-stats-banners group relative overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="card-body relative">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-images text-purple-600 dark:text-purple-400"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full">
                    <i class="fas fa-arrow-down text-xs mr-1"></i>3%
                </span>
            </div>
            <div class="text-xs uppercase text-slate-700 dark:text-slate-300 mb-1">Banners</div>
            <div class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $stats['banners'] ?? 0 }}</div>
            <div class="text-xs text-slate-600 dark:text-slate-400">{{ $stats['banners_change'] ?? '2 inactivos' }}</div>
            <div class="mt-3">
                <a href="{{ url('show-slider') }}" class="inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    Gestionar
                    <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-stats-news group relative overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="card-body relative">
            <div class="flex items-start justify-between mb-3">
                <div class="p-2 bg-white/20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-newspaper text-white"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-white/20 text-white rounded-full">
                    <i class="fas fa-arrow-up text-xs mr-1"></i>15%
                </span>
            </div>
            <div class="text-xs uppercase opacity-80 mb-1">Noticias</div>
            <div class="text-3xl font-extrabold text-white mb-2">{{ $stats['news'] ?? 0 }}</div>
            <div class="text-xs opacity-80">{{ $stats['news_change'] ?? '15 nuevas este mes' }}</div>
            <div class="mt-3">
                <a href="{{ url('show-news') }}" class="inline-flex items-center text-sm font-medium text-white hover:underline transition-colors">
                    Ir a noticias
                    <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Sección de contenido principal -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimos versículos mejorados -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="fas fa-book-bible text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimos versículos</h3>
                </div>
                <a href="{{ url('show-quote') }}" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-book-bible mr-1"></i> Ver todo
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm border-b border-slate-200 dark:border-slate-700">
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Imagen</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">PDF</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($latestVerses as $v)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group/row">
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-900 dark:text-slate-200">{{ \Carbon\Carbon::parse($v->date)->format('d/m') }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($v->date)->format('Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($v->image)
                                    <div class="relative group/image">
                                        <img src="{{ asset('images/bible/'.$v->image) }}" alt="" class="w-10 h-10 rounded object-cover group-hover/image:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/50 rounded opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-xs"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded bg-slate-200 dark:bg-slate-700 flex items-center justify-center group-hover/image:scale-110 transition-transform duration-300">
                                        <i class="fas fa-image text-slate-400 text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($v->video)
                                    <a href="{{ asset('documents/quote/'.$v->video) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Activo
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-bible text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    <span class="text-slate-500 dark:text-slate-400">Sin versículos registrados</span>
                                    <a href="{{ url('show-quote') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer versículo</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Últimas noticias mejoradas -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <i class="fas fa-newspaper text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimas noticias</h3>
                </div>
                <a href="{{ url('show-news') }}" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-newspaper mr-1"></i> Ver todo
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse ($latestNews as $n)
                <div class="group/item p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 cursor-pointer">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-slate-900 dark:text-white truncate group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                {{ $n->title }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                {{ $n->created_at->format('d/m/Y H:i') }}
                            </p>
                            @if($n->excerpt)
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 line-clamp-2">
                                    {{ $n->excerpt }}
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1">
                            <a href="{{ url('view-news/'.$n->id) }}" class="p-1.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors group/edit">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ url('edit-news/'.$n->id) }}" class="p-1.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors group/edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-newspaper text-slate-300 dark:text-slate-600 text-2xl"></i>
                        <span class="text-slate-500 dark:text-slate-400">Sin noticias publicadas</span>
                        <a href="{{ url('show-news') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Publicar primera noticia</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


<!-- Sección de acciones rápidas -->
<div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Acciones rápidas</h3>
        <span class="text-xs text-slate-500 dark:text-slate-400">Gestiona tu contenido de forma eficiente</span>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <button data-url="{{ url('show-quote') }}" class="quick-action-btn group flex flex-col items-center gap-2 p-4 bg-white dark:bg-slate-800 rounded-lg hover:shadow-md transition-all duration-300 hover:scale-105">
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-green-600 dark:text-green-400"></i>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Nuevo Versículo</span>
        </button>
        
        <button data-url="{{ url('show-news') }}" class="quick-action-btn group flex flex-col items-center gap-2 p-4 bg-white dark:bg-slate-800 rounded-lg hover:shadow-md transition-all duration-300 hover:scale-105">
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Nueva Noticia</span>
        </button>
        
        <button data-url="{{ url('show-slider') }}" class="quick-action-btn group flex flex-col items-center gap-2 p-4 bg-white dark:bg-slate-800 rounded-lg hover:shadow-md transition-all duration-300 hover:scale-105">
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-purple-600 dark:text-purple-400"></i>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Nuevo Banner</span>
        </button>
        
        <button data-url="{{ url('show-schedule') }}" class="quick-action-btn group flex flex-col items-center gap-2 p-4 bg-white dark:bg-slate-800 rounded-lg hover:shadow-md transition-all duration-300 hover:scale-105">
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Nueva Programación</span>
        </button>
    </div>
</div>


<!-- Resumen de actividad -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-slate-600 dark:text-slate-400">Visitas hoy</span>
            <i class="fas fa-chart-line text-green-500"></i>
        </div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['visits_today'] ?? '1,234' }}</div>
        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
            <i class="fas fa-arrow-up text-xs"></i> 12% vs ayer
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-slate-600 dark:text-slate-400">Usuarios activos</span>
            <i class="fas fa-users text-blue-500"></i>
        </div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['active_users'] ?? '89' }}</div>
        <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
            <i class="fas fa-arrow-up text-xs"></i> 5% vs semana pasada
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-slate-600 dark:text-slate-400">Tasa de conversión</span>
            <i class="fas fa-percentage text-purple-500"></i>
        </div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['conversion_rate'] ?? '3.2%' }}</div>
        <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">
            <i class="fas fa-arrow-up text-xs"></i> 0.8% vs mes pasado
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Manejador para botones de acciones rápidas
    // ========================================
    document.querySelectorAll('.quick-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });
    
    // ========================================
    // Animación de entrada para las tarjetas
    // ========================================
    const cards = document.querySelectorAll('.card-stats-users, .card-stats-verses, .card-stats-schedules, .card-stats-banners, .card-stats-news');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // ========================================
    // Efecto de actualización automática de estadísticas
    // ========================================
    const updateStats = () => {
        const statNumbers = document.querySelectorAll('.text-3xl');
        statNumbers.forEach(stat => {
            const finalValue = stat.textContent;
            const numValue = parseInt(finalValue.replace(/[^0-9]/g, '')) || 0;
            let currentValue = 0;
            const increment = numValue / 30;
            
            const updateNumber = () => {
                if (currentValue < numValue) {
                    currentValue += increment;
                    stat.textContent = Math.floor(currentValue).toLocaleString();
                    requestAnimationFrame(updateNumber);
                } else {
                    stat.textContent = finalValue;
                }
            };
            
            updateNumber();
        });
    };
    
    // Iniciar animación de números
    setTimeout(updateStats, 500);
    
    // ========================================
    // Refrescar datos cada 30 segundos
    // ========================================
    setInterval(() => {
        // Aquí podrías hacer una llamada AJAX para actualizar los datos
        console.log('Actualizando datos del dashboard...');
    }, 30000);
    
    // ========================================
    // Efecto hover en las filas de las tablas
    // ========================================
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // ========================================
    // Inicializar tooltips si es necesario
    // ========================================
    const initTooltips = () => {
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                // Lógica para mostrar tooltips
            });
        });
    };
    
    initTooltips();
});
</script>
@endpush
