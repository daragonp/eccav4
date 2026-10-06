@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Categorías de Biblioteca')
@section('pageheading', 'Categorías de Biblioteca')

@section('addbutton', 'Agregar categoría')
@section('formaction', url('addlibrary-category'))
@section('modalTitle', 'Agregar categoría')

@section('modalFields')
  <div class="grid grid-cols-1 gap-4">
    <div>
      <label for="name" class="block text-sm mb-1">Nombre</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="description" class="block text-sm mb-1">Descripción</label>
      <textarea id="description" name="description" rows="3"
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">{{ old('description') }}</textarea>
    </div>
  </div>
@endsection

@section('datatable')
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Nombre</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Descripción</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($categories as $category)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $category->name }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $category->description ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <button type="button"
                            data-modal-open="EditCat_{{ $category->id }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-400 transition-all"
                            title="Editar">
                      <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                    <form action="{{ url('delete-library-category', $category->id) }}" method="POST"
                          onsubmit="return confirm('¿Desea eliminar esta categoría?');" class="inline">
                      @csrf
                      <button type="submit"
                              class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 transition-all"
                              title="Eliminar">
                        <i class="fa-solid fa-trash text-sm"></i>
                      </button>
                    </form>
                  </div>

                  {{-- Modal de edición propio por categoría (patrón tw-modal del panel) --}}
                  <section id="EditCat_{{ $category->id }}" class="tw-modal hidden" role="dialog" aria-modal="true" aria-hidden="true">
                    <div class="tw-modal-panel text-left">
                      <div class="tw-modal-header">
                        <h3 class="text-lg font-semibold">Editar categoría</h3>
                        <button type="button" data-modal-close class="btn btn-ghost" aria-label="Cerrar"><i class="fa-solid fa-xmark"></i></button>
                      </div>
                      <form action="{{ url('update-library-category', $category->id) }}" method="POST">
                        @csrf
                        <div class="tw-modal-body space-y-4">
                          <div>
                            <label class="block text-sm mb-1">Nombre</label>
                            <input type="text" name="name" value="{{ $category->name }}" required
                                   class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
                          </div>
                          <div>
                            <label class="block text-sm mb-1">Descripción</label>
                            <textarea name="description" rows="3"
                                      class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">{{ $category->description }}</textarea>
                          </div>
                        </div>
                        <div class="tw-modal-footer">
                          <button type="button" data-modal-close class="btn">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                      </form>
                    </div>
                  </section>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-folder-open text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No hay categorías registradas</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($categories->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $categories->links() }}
    </div>
  @endif
@endsection
