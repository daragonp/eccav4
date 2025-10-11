@php
  // Requeridos para el modal de edición
  $modalId    = $modalId    ?? ('EditModal_'.$id);
  $formAction = $formAction ?? '#';
  $editPartial= $editPartial?? null;
  $sectionType = $sectionType ?? 'news';
  $sectionTitle = $sectionTitle ?? 'Elemento';
  $isDeleted = isset($tableM) && $tableM->deleted_at ? true : false;
@endphp

<div class="flex items-center justify-center gap-2">

  {{-- Ver --}}
  <a href="{{ $view ?? '#' }}" class="btn btn-ghost text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver">
    <i class="fa-solid fa-eye"></i>
  </a>
<!-- 
  {{-- Editar (abre modal por fila) --}}
  <button class="btn btn-ghost text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300" title="Editar" data-modal-open="{{ $modalId }}">
    <i class="fa-solid fa-pen-to-square"></i>
  </button> -->

  {{-- Activar / Desactivar --}}
  @if ($isDeleted)
    <a href="{{ $activate ?? '#' }}" class="btn btn-ghost text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Activar">
      <i class="fa-solid fa-toggle-off"></i>
    </a>
  @else
    <a href="{{ $softdelete ?? '#' }}" class="btn btn-ghost text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300" title="Desactivar">
      <i class="fa-solid fa-toggle-on"></i>
    </a>
  @endif

  {{-- Eliminar definitivo --}}
  <form action="{{ $realdelete ?? '#' }}" method="GET" onsubmit="return confirm('¿Eliminar definitivamente?');" class="inline">
    <button type="submit" class="btn btn-ghost text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
      <i class="fa-solid fa-trash"></i>
    </button>
  </form>
</div>

{{-- ===== Modal de edición por fila (Universal) ===== --}}
@include('admin.partials.universal-edit-modal', [
    'modalId' => $modalId,
    'formAction' => $formAction,
    'tableM' => $tableM ?? null,
    'sectionType' => $sectionType,
    'sectionTitle' => $sectionTitle,
])