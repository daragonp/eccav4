<!-- Mantén el modal Bootstrap tal cual... -->
<div class="modal fade" id="suggestionsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header bg-[var(--green-dark)] text-white">
        <h5 class="modal-title">Sugerencias</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Formulario de sugerencias</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary bg-[var(--green-light)] border-0 hover:brightness-110"
           href="{{ url('suggestions') }}">Aceptar</a>
      </div>
    </div>
  </div>
</div>
