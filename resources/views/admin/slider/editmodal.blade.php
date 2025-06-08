<div class="container my-5">
    <div class="row">
        <!-- Columna de imagen actual -->
        <div class="col-md-6 mb-4">
            <div class="form-group">
                <p class="image-label"><strong>Imagen izquierda</strong></p>
                <img src="images/slider{{$tableM->image_left}}" class="img-fluid img-preview" alt="Imagen izquierda" width="100px">
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="form-group">
                <p class="image-label"><strong>Imagen derecha</strong></p>
                <img src="images/slider{{$tableM->image_right}}" class="img-fluid img-preview" alt="Imagen derecha" width="100px">
            </div>
        </div>


        <!-- Columna de formulario -->
        <div class="col-md-12">
            <form action="{{ url('update-slider/' . $tableM->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="image_left" class="form-label">Nueva imagen de la izquierda</label>
                    <input type="file" name="image_left" id="image_left" accept="image/*" class="form-control custom-input">
                </div>
                <div class="form-group mb-4">
                    <label for="image_right" class="form-label">Nueva imagen de la derecha</label>
                    <input type="file" name="image_right" id="image_right" accept="image/*" class="form-control custom-input">
                </div>
            </form>
        </div>
    </div>
</div>
