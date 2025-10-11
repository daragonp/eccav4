@extends('layouts.panel')

@section('title', 'Mirada Afro')
@section('pageheading', 'Mirada Afro')

{{-- Botón crear (abre el modal base del layout) --}}
@section('addbutton')
   Nuevo tema de actualidad
@endsection

{{-- Modal de creación --}}
@section('modalTitle','Nuevo tema de actualidad')
@section('formaction', url('/addfeed'))
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="title" class="block text-sm font-medium mb-1">Título del tema</label>
      <input id="title" name="title" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Título del tema de actualidad" required>
    </div>
    <div>
      <label for="author" class="block text-sm font-medium mb-1">Autor</label>
      <input id="author" name="author" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Nombre del autor" required>
    </div>
    <div>
      <label for="date" class="block text-sm font-medium mb-1">Fecha de publicación</label>
      <input id="date" name="date" type="date" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="category" class="block text-sm font-medium mb-1">Categoría</label>
      <select id="category" name="category" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="">Seleccionar categoría</option>
        <option value="politica">Política</option>
        <option value="social">Social</option>
        <option value="cultural">Cultural</option>
        <option value="economico">Económico</option>
        <option value="educacion">Educación</option>
      </select>
    </div>
    <div class="md:col-span-2">
      <label for="summary" class="block text-sm font-medium mb-1">Resumen</label>
      <textarea id="summary" name="summary" rows="3" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Breve resumen del tema"></textarea>
    </div>
    <div>
      <label for="image" class="block text-sm font-medium mb-1">Imagen principal</label>
      <input id="image" name="image" type="file" accept="image/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="document" class="block text-sm font-medium mb-1">Documento adjunto</label>
      <input id="document" name="document" type="file" accept=".pdf,.doc,.docx" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Tarjeta de información --}}
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-earth-africa text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Mirada Afro</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra los temas de actualidad y análisis desde la perspectiva afrocolombiana. Usa el botón "Nuevo tema de actualidad" para añadir nuevos contenidos o las acciones por fila para ver, editar o eliminar temas existentes.
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Tarjeta de la tabla --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        {{-- Aquí irá la tabla cuando se implemente el DataTable --}}
        {{-- {!! $dataTable->table(['class' => 'w-full'], true) !!} --}}
        
        <!-- Mensaje temporal mientras se implementa el DataTable -->
        <div class="p-8 text-center">
          <i class="fas fa-earth-africa text-4xl text-slate-300 dark:text-slate-600 mb-4"></i>
          <p class="text-slate-500 dark:text-slate-400">
            El DataTable para "Mirada Afro" está en proceso de implementación.
          </p>
          <p class="text-sm text-slate-400 dark:text-slate-500 mt-2">
            Pronto podrás gestionar aquí los temas de actualidad.
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  {{-- Carga los scripts del DataTable generado por Yajra (cuando se implemente) --}}
  {{-- {!! $dataTable->scripts() !!} --}}
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Aplicar estilos a los controles de DataTables después de que se cargue
      setTimeout(function() {
        // Asumimos que el ID de la tabla será 'feed-table'
        const lengthSelect = document.querySelector('#feed-table_length select');
        const filterInput = document.querySelector('#feed-table_filter input');
        
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