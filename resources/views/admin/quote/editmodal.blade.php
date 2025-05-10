<div class="container my-5">
    <div class="row">
        <!-- Columna de imagen -->
        <div class="col-md-6 mb-4">
            <div class="form-group">
                <p class="image-label"><strong>Imagen actual</strong></p>
                <img src="images/bible/{{ $tableM->image }}" alt="Imagen de {{ $tableM->name }}" class="img-fluid img-preview">
            </div>
        </div>

        <!-- Columna de formulario -->
        <div class="col-md-6">
            <form>
                <div class="form-group mb-4">
                    <label for="video" class="form-label">Documento PDF</label>
                    <input type="file" name="video" accept=".pdf" class="form-control custom-input" id="video">
                </div>
                <div class="form-group mb-4">
                    <label for="image" class="form-label">Nueva imagen del versículo</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control custom-input">
                </div>
                <div class="form-group mb-4">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" name="date" id="date" class="form-control custom-input" value="{{ $tableM->date }}" required>
                </div>
            </form>
        </div>
    </div>
</div>