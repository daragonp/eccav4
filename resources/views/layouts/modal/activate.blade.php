<div class="modal fade" id="ActivateModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
     aria-labelledby="ActivateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ActivateModalLabel">¿Está seguro de activar el registro?</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Haga clic en aceptar para activar el elemento.</div>
            <p class="ms-3">Tenga en cuenta que el contenido se hará visible para el público</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <a class="btn btn-success" href="{{ $activate }}">Aceptar</a>
            </div>
        </div>
    </div>
</div>