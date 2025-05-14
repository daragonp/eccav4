<div class="modal fade" id="RealModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
     aria-labelledby="RealModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="RealModalLabel">¿Está seguro de eliminar el registro?</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Haga clic en aceptar para eliminar el elemento.</div>
            <p class="ms-3">Tenga en cuenta que el contenido no podrá recuperarse</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <a class="btn btn-success" href="{{ $realdelete }}">Aceptar</a>
            </div>
        </div>
    </div>
</div>