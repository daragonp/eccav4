

<div class="modal fade" id="ActivateModal_{{ $tableM->id }}" tabindex="-1" role="dialog"
    aria-labelledby="ActivateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ActivateModal">¿Está seguro de activar el registro?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Haga clic en aceptar para activar el elemento.</div>
            <p>Tenga en cuenta que el contenido se hará visible para el público</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-success" href="{{ $activate }}">Aceptar</a>
            </div>
        </div>
    </div>
</div>
