@php $showAddButton = false; @endphp

@extends('layouts.panel')

@section('title', 'Podcasts & Series')
@section('pageheading', 'Gestión de Podcasts & Series')

@section('datatable')
<div x-data="{ currentTab: '{{ request('tab', $activeTab ?? 'podcasts') }}', showPodcastModal: false, showCategoryModal: false }">

  {{-- Tarjeta Informativa & Acciones Rápidas --}}
  <div class="card mb-6">
    <div class="card-body p-5">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xl shadow-xs">
            <i class="fa-solid fa-podcast"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Podcasts y Series Temáticas</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">
              Administra los episodios de audio bajo demanda, organizados por series bíblicas, conferencias y categorías temáticas.
            </p>
          </div>
        </div>

        {{-- Botones de Creación Directa --}}
        <div class="flex flex-wrap items-center gap-2 shrink-0">
          <button type="button" @click="showPodcastModal = true" class="btn btn-primary inline-flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Nuevo Episodio</span>
          </button>
          <button type="button" @click="showCategoryModal = true" class="btn btn-secondary inline-flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-folder-plus text-xs"></i>
            <span>Nueva Serie / Categoría</span>
          </button>
        </div>
      </div>

      {{-- Pestañas de Navegación --}}
      <div class="flex items-center gap-2 mt-6 border-b border-slate-200 dark:border-slate-800">
        <button type="button"
                @click="currentTab = 'podcasts'"
                :class="currentTab === 'podcasts' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'"
                class="px-4 py-2.5 border-b-2 text-sm transition-all flex items-center gap-2">
          <i class="fa-solid fa-headphones"></i>
          <span>Episodios de Podcast</span>
          <span class="px-2 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
            {{ $podcasts->total() }}
          </span>
        </button>

        <button type="button"
                @click="currentTab = 'categories'"
                :class="currentTab === 'categories' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'"
                class="px-4 py-2.5 border-b-2 text-sm transition-all flex items-center gap-2">
          <i class="fa-solid fa-layer-group"></i>
          <span>Series & Categorías</span>
          <span class="px-2 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
            {{ count($categories) }}
          </span>
        </button>
      </div>
    </div>
  </div>

  {{-- Buscador Global --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-6">
    <input type="hidden" name="tab" :value="currentTab">
    <div class="relative flex-1">
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Buscar por título, serie, temática o descripción..." 
        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
      >
      <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
    </div>
    <button type="submit" class="btn btn-secondary px-5">Buscar</button>
    @if(request('search'))
      <a href="{{ url()->current() }}?tab={{ request('tab', 'podcasts') }}" class="btn btn-ghost">Limpiar</a>
    @endif
  </form>

  {{-- ============================================== --}}
  {{-- TAB 1: LISTADO DE PODCASTS                     --}}
  {{-- ============================================== --}}
  <div x-show="currentTab === 'podcasts'" x-transition class="space-y-4">
    <div class="card">
      <div class="card-body p-0">
        <div class="table-wrap">
          <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
              <tr>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Episodio</th>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Serie / Categoría</th>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Reproductor de Audio</th>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Fecha</th>
                <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
              @forelse ($podcasts as $podcast)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                  <td class="px-4 py-3.5">
                    <div class="font-semibold text-slate-900 dark:text-white text-sm">
                      {{ $podcast->title }}
                    </div>
                    @if($podcast->description)
                      <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1 max-w-md">
                        {{ $podcast->description }}
                      </div>
                    @endif
                  </td>
                  <td class="px-4 py-3.5 text-sm">
                    @if($podcast->category)
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                        <i class="fa-solid fa-bookmark text-[10px]"></i>
                        {{ $podcast->category->name }}
                      </span>
                    @else
                      <span class="text-slate-400 text-xs italic">Sin categoría</span>
                    @endif
                  </td>
                  <td class="px-4 py-3.5 text-sm">
                    @if ($podcast->audio_file)
                      <div class="flex items-center gap-2">
                        <audio controls class="h-8 w-48 sm:w-60">
                          <source src="{{ asset('audio/podcast/' . $podcast->audio_file) }}">
                        </audio>
                      </div>
                    @else
                      <span class="text-slate-400 text-xs inline-flex items-center gap-1">
                        <i class="fa-solid fa-volume-xmark"></i> Sin audio
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                    {{ $podcast->created_at ? $podcast->created_at->format('d/m/Y') : '-' }}
                  </td>
                  <td class="px-4 py-3.5 text-center">
                    @include('admin.partials.actions', [
                        'id'           => $podcast->id,
                        'view'         => null,
                        'delete'       => url("delete-podcast", $podcast->id),
                        'formAction'   => url("updatepodcast", $podcast->id),
                        'tableM'       => $podcast,
                        'sectionType'  => 'podcast',
                        'sectionTitle' => 'Episodio de Podcast',
                    ])
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-headphones text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                    <p class="font-medium">No se encontraron episodios de podcast</p>
                    <p class="text-xs text-slate-400 mt-1">Haz clic en "Nuevo Episodio" para publicar el primero.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @if ($podcasts->hasPages())
      <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl shadow-xs">
        {{ $podcasts->links() }}
      </div>
    @endif
  </div>

  {{-- ============================================== --}}
  {{-- TAB 2: LISTADO DE CATEGORÍAS                   --}}
  {{-- ============================================== --}}
  <div x-show="currentTab === 'categories'" x-transition class="space-y-4">
    <div class="card">
      <div class="card-body p-0">
        <div class="table-wrap">
          <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
              <tr>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Nombre de Serie</th>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Subcategoría / Tag</th>
                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Descripción</th>
                <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Episodios</th>
                <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
              @forelse ($categories as $cat)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                  <td class="px-4 py-3.5 font-semibold text-slate-900 dark:text-white text-sm">
                    {{ $cat->name }}
                  </td>
                  <td class="px-4 py-3.5 text-sm">
                    @if($cat->subcategory)
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        {{ $cat->subcategory }}
                      </span>
                    @else
                      <span class="text-slate-400 text-xs">-</span>
                    @endif
                  </td>
                  <td class="px-4 py-3.5 text-xs text-slate-600 dark:text-slate-300 max-w-sm">
                    {{ $cat->description }}
                  </td>
                  <td class="px-4 py-3.5 text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                      {{ $cat->podcast_count ?? 0 }}
                    </span>
                  </td>
                  <td class="px-4 py-3.5 text-center">
                    @include('admin.partials.actions', [
                        'id'           => $cat->id,
                        'view'         => null,
                        'delete'       => url("delete-category", $cat->id),
                        'formAction'   => url("updatecategory", $cat->id),
                        'tableM'       => $cat,
                        'sectionType'  => 'category',
                        'sectionTitle' => 'Categoría de Podcast',
                    ])
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                    <p class="font-medium">No se encontraron series ni categorías</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================== --}}
  {{-- MODAL 1: CREAR PODCAST                         --}}
  {{-- ============================================== --}}
  <div x-show="showPodcastModal" 
       x-cloak
       class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div @click.away="showPodcastModal = false" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-700 overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 bg-linear-to-r from-emerald-600 to-teal-700 text-white">
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-podcast text-xl"></i>
          <h3 class="font-bold text-lg">Nuevo Episodio de Podcast</h3>
        </div>
        <button type="button" @click="showPodcastModal = false" class="text-white/80 hover:text-white">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <form action="{{ url('addpodcast') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        <div>
          <label for="p_name" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Título del Episodio</label>
          <input id="p_name" type="text" name="name" required placeholder="Ej: Romanos 8 - Vida en el Espíritu" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
          <label for="p_category" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Serie / Categoría</label>
          <select id="p_category" name="category" required class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            <option value="">Selecciona una serie o categoría</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }} {{ $cat->subcategory ? '('.$cat->subcategory.')' : '' }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="p_description" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Descripción / Notas</label>
          <textarea id="p_description" name="description" rows="3" placeholder="Resumen del mensaje o notas del episodio..." class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"></textarea>
        </div>

        <div>
          <label for="p_audio" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Archivo de Audio (MP3 / M4A / WAV)</label>
          <input id="p_audio" type="file" name="audio" accept="audio/*" required class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-900 dark:text-white file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-950 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 cursor-pointer">
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Soporta archivos de hasta 64MB.</p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
          <button type="button" @click="showPodcastModal = false" class="btn btn-ghost">Cancelar</button>
          <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Publicar Episodio</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ============================================== --}}
  {{-- MODAL 2: CREAR CATEGORÍA                       --}}
  {{-- ============================================== --}}
  <div x-show="showCategoryModal" 
       x-cloak
       class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div @click.away="showCategoryModal = false" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-xl w-full border border-slate-200 dark:border-slate-700 overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 bg-linear-to-r from-slate-800 to-slate-900 text-white">
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-folder-plus text-emerald-400 text-xl"></i>
          <h3 class="font-bold text-lg">Nueva Serie / Categoría</h3>
        </div>
        <button type="button" @click="showCategoryModal = false" class="text-white/80 hover:text-white">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <form action="{{ url('addcategory') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <div>
          <label for="c_name" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Nombre de la Serie</label>
          <input id="c_name" type="text" name="name" required placeholder="Ej: Lumbrera a mi camino - Romanos" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
          <label for="c_subcategory" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Subcategoría o Etiqueta Corta (Opcional)</label>
          <input id="c_subcategory" type="text" name="subcategory" placeholder="Ej: Romanos" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
          <label for="c_description" class="block text-sm font-semibold mb-1 text-slate-800 dark:text-slate-200">Descripción de la Serie</label>
          <textarea id="c_description" name="description" rows="3" required placeholder="Contenido o propósito de esta serie..." class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
          <button type="button" @click="showCategoryModal = false" class="btn btn-ghost">Cancelar</button>
          <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
            <i class="fa-solid fa-save"></i>
            <span>Guardar Serie</span>
          </button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection