<div class="row">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <img src="images/bible/{{ $tableM->image }}" alt="Imagen de {{ $tableM->name }}" width="350px">
            <p>Imagen actual</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="file" name="image" id="floatingInput" accept="image/*" class="form-control"
                value="images/bible/{{ $tableM->image }}">
            <label>Nueva imagen del versículo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="audio" accept="audio/*" class="form-control"
                value="audio/quote/{{ $tableM->audio }}">
            <label>Audio del versículo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="video" accept="video/*" class="form-control"
                value="video/quote/{{ $tableM->video }}">
            <label>Video del versículo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" name="date" class="form-control"
                value="{{ $tableM->date }}" required>
            <label>Fecha</label>
        </div>
    </div>
</div>
