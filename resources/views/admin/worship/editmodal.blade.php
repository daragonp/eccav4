<div class="row">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" name="title" value="{{ $tableM->title }}"
                required>
            <label for="floatingInput">Título o tema principal</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" id="floatingTextarea" name="abstract" style="height: 100px">{{ $tableM->abstract }}</textarea>
            <label for="floatingTextarea">Resumen</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="floatingInput" name="broadcast"
                value="{{ $tableM->broadcast }}">
            <label for="floatingInput">Fecha de emisión</label>
        </div>
        <div class="form-group mb-2">
            <label>Palabra</label>
            <input type="text" name="badge" class="form-control" value="{{ $tableM->badge }}" required>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" name="autor" value="{{ $tableM->autor }}"
                required>
            <label for="floatingInput">Autor</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="file" class="form-control" id="floatingInput" accept='.pdf' name="pdfdoc"
                value="documents/worship/{{ $tableM->pdfdoc }}">
            <label for="floatingInput mb-3">Documento PDF <a
                    href="documents/worship/{{ $tableM->pdfdoc }}">{{ $tableM->pdfdoc }}</a></label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" class="form-control" id="floatingInput" accept="image/*" name="image"
                value="images/worship/{{ $tableM->image }}">
            <label for="floatingInput mb-3">Imagen <a
                    href="images/worship/{{ $tableM->image }}">{{ $tableM->image }}</a></label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" class="form-control" id="floatingInput" accept="audio/*" name="audio"
                value="audio/worship/{{ $tableM->audio }}">
            <label for="floatingInput mb-3">Audio <a
                    href="audio/worship/{{ $tableM->audio }}">{{ $tableM->audio }}</a></label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" class="form-control" id="floatingInput" accept="video/*" name="video"
                value="video/worship/{{ $tableM->video }}">
            <label for="floatingInput mb-3">Video <a
                    href="video/worship/{{ $tableM->video }}">{{ $tableM->video }}</a></label>
        </div>
    </div>
</div>
