<a href="{{$formAction}}" data-toggle="modal" data-target="#EditModal_{{ $tableM->id }}"
    class="edit btn btn-warning btn-sm">
    <i class="fa-sharp fa-solid fa-pen-to-square"></i>
</a>
<a style="margin-left: 10px;" href="{{ $view}}" class="btn btn-primary btn-sm">
    <i class="fa-sharp fa-solid fa-eye"></i>
</a>
@if ($tableM->deleted_at != NULL)
    <a style="margin-left: 10px;" href="{{ $activate }}" data-toggle="modal"
        data-target="#ActivateModal_{{ $tableM->id }}" class="btn btn-success btn-sm">
        <i class="fa-sharp fa-solid fa-toggle-on"></i>
    </a>
@else
    <a style="margin-left: 10px;" href="{{ $softdelete }}" data-toggle="modal"
        data-target="#DeleteModal_{{ $tableM->id }}" class="btn btn-danger btn-sm">
        <i class="fa-sharp fa-solid fa-power-off"></i>
    </a>
@endif
@if ( auth()->user()->roles->pluck('id')[0] ?? '' === 1 )
    <a style="margin-left: 10px;" href="{{ $realdelete }}" data-toggle="modal"
        data-target="#RealModal_{{ $tableM->id }}" class="btn btn-dark btn-sm">
        <i class="fa-sharp fa-solid fa-trash"></i>
    </a>
@endif

@include('layouts.modal.update')
@include('layouts.modal.activate')
@include('layouts.modal.softdelete')
@include('layouts.modal.delete')
