<div class="modal fade" id="EditModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
    aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditModalLabel">Edición y actualización de los datos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('../admin/'.$folder.'/editmodal')
                    <button type="submit" style="float: right" class="btn btn-success mb-2 mt-4">Actualizar datos</button>
                </div>
            </form>
        </div>
    </div>
</div>
