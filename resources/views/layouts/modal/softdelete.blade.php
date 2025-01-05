<div class="modal fade" id="DeleteModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
    aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeleteModal">¿Está seguro de desactivar el registro?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Haga clic en aceptar para desactivar el elemento.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-success" href="{{ $softdelete}}">Aceptar</a>
            </div>
        </div>
    </div>
</div>
