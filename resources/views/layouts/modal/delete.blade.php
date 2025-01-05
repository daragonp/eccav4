<div class="modal fade" id="RealModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
    aria-labelledby="RealModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="RealModal">¿Está seguro de eliminar el registro?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Haga clic en aceptar para eliminar el elemento.</div>
            <p>Tenga en cuenta que el contenido no podrá recuperarse</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-success" href="{{ $realdelete }}">Aceptar</a>
            </div>
        </div>
    </div>
</div>
