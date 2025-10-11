@extends('layouts.panel')

@section('title','Noticias')
@section('pageheading','Mensajes de la Semana')

{{-- Botón crear (abre el modal base del layout) --}}
@section('addbutton')
  Crear mensaje
@endsection

{{-- Modal de creación --}}
@section('modalTitle','Crear nuevo mensaje')
@section('formaction', url('/addnews'))
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="title" class="block text-sm font-medium mb-1">Título</label>
      <input id="title" name="title" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="autor" class="block text-sm font-medium mb-1">Autor</label>
      <input id="autor" name="autor" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="broadcast" class="block text-sm font-medium mb-1">Fecha de emisión</label>
      <input id="broadcast" name="broadcast" type="date" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="category" class="block text-sm font-medium mb-1">Categoría (ID)</label>
      <input id="category" name="category" type="number" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" value="1">
    </div>
    <div>
      <label for="pdfdoc" class="block text-sm font-medium mb-1">Documento PDF</label>
      <input id="pdfdoc" name="pdfdoc" type="file" accept="application/pdf" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="image" class="block text-sm font-medium mb-1">Imagen</label>
      <input id="image" name="image" type="file" accept="image/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="audio" class="block text-sm font-medium mb-1">Audio (opcional)</label>
      <input id="audio" name="audio" type="file" accept="audio/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="abstract" class="block text-sm font-medium mb-1">Resumen</label>
      <textarea id="abstract" name="abstract" rows="3" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"></textarea>
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Tarjeta de información --}}
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-newspaper text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Mensajes de la Semana</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra los mensajes semanales de la comunidad. Usa el botón "Crear mensaje" para añadir nuevos contenidos o las acciones por fila para ver, editar o eliminar mensajes existentes.
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Tarjeta de la tabla --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        {!! $dataTable->table(['class' => 'w-full'], true) !!}
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  {{-- Carga los scripts del DataTable generado por Yajra --}}
  {!! $dataTable->scripts() !!}
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Aplicar estilos a los controles de DataTables después de que se cargue
      setTimeout(function() {
        const lengthSelect = document.querySelector('#news-table_length select');
        const filterInput = document.querySelector('#news-table_filter input');
        
        if (lengthSelect) {
          lengthSelect.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm';
        }
        
        if (filterInput) {
          filterInput.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm';
          filterInput.placeholder = 'Buscar...';
        }
      }, 100);
    });
  </script>
@endpush