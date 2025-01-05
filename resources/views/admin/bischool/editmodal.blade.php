<div class="row">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <label for="">Seleccione una opción</label>
            <select name="category" id="">
                <option value="1">Mensaje de la semana</option>
                <option value="2">Mirada afro</option>
            </select>
        </div>
        <div class="form-floating mb-3">
            <input type="text" value= "{{$tableM->title}}" name="title" class="form-control" required>
            <label>Títular de la noticia</label>
        </div>
        <div>
            <textarea value= "" name="abstract" id="" class="form-control" rows="2">{{$tableM->abstract}}</textarea>
            <label>Resumen</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" value= "{{$tableM->autor}}" name="autor" class="form-control" required>
            <label>Autor</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="file" value= "{{$tableM->pdfdoc}}" name="pdfdoc" accept=".pdf*" class="form-control">
            <label>Documento PDF</label><a href="documents/news/{{ $tableM->pdfdoc }}">{{ $tableM->pdfdoc }}</a>
        </div>
        <div class="form-floating mb-3">
            <input type="file" value= "{{$tableM->image}}" name="image" accept="image/*" class="form-control">
            <label>Imagen de contexto</label><a href="images/news/{{ $tableM->image }}">{{ $tableM->image }}</a>
        </div>
        <div class="form-floating mb-3">
            <input type="file" value= "{{$tableM->audio}}" name="audio" accept="audio/*" class="form-control">
            <label>Audio</label>
        </div>
    </div>
</div>
