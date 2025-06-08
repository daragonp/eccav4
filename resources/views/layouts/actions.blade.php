<div class="d-flex gap-1 flex-wrap">

    <a href="{{ $formAction }}" data-bs-toggle="modal" data-bs-target="#EditModal_{{ $tableM->id }}"
        class="edit btn btn-warning btn-sm">
        <i class="fa-sharp fa-solid fa-pen-to-square"></i>
    </a>

    <a href="{{ $view }}" class="btn btn-primary btn-sm">
        <i class="fa-sharp fa-solid fa-eye"></i>
    </a>

    @if ($tableM->deleted_at != NULL)
        <a href="{{ $activate }}" data-bs-toggle="modal"
            data-bs-target="#ActivateModal_{{ $tableM->id }}" class="btn btn-success btn-sm">
            <i class="fa-sharp fa-solid fa-toggle-on"></i>
        </a>
    @else
        <a href="{{ $softdelete }}" data-bs-toggle="modal"
            data-bs-target="#DeleteModal_{{ $tableM->id }}" class="btn btn-danger btn-sm">
            <i class="fa-sharp fa-solid fa-power-off"></i>
        </a>
    @endif

    @if (auth()->user()->roles->pluck('id')[0] ?? '' === 1)
        <a href="{{ $realdelete }}" data-bs-toggle="modal"
            data-bs-target="#RealModal_{{ $tableM->id }}" class="btn btn-dark btn-sm">
            <i class="fa-sharp fa-solid fa-trash"></i>
        </a>
    @endif

</div>

@include('layouts.modal.update')
@include('layouts.modal.activate')
@include('layouts.modal.softdelete')
@include('layouts.modal.delete')