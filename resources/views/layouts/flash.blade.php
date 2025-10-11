@if ($errors->any())
  <div class="alert alert-error" data-autohide="8000">
    <i class="fa-solid fa-circle-exclamation mt-1"></i>
    <div>
      <strong>Corrige lo siguiente:</strong>
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
    <button class="close" type="button" data-alert-close aria-label="Cerrar">&times;</button>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success" data-autohide="4000">
    <i class="fa-solid fa-circle-check mt-1"></i>
    <div>{{ session('success') }}</div>
    <button class="close" type="button" data-alert-close aria-label="Cerrar">&times;</button>
  </div>
@endif

@if (session('info'))
  <div class="alert alert-info" data-autohide="5000">
    <i class="fa-solid fa-circle-info mt-1"></i>
    <div>{{ session('info') }}</div>
    <button class="close" type="button" data-alert-close aria-label="Cerrar">&times;</button>
  </div>
@endif
