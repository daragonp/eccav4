<div class="row">
    <div class="col-md-5">
        <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" value="{{$tableM->name}}" required>
            <label>Nombre del programa</label>
        </div>
        <div class="form-floating mb-3">
            <input type="time" name="start" id="start" class="form-control" value="{{$tableM->start}}" required>
            <label>Hora de inicio</label>
        </div>
        <div class="form-floating mb-3">
            <input type="time" name="end" id="end" class="form-control" value="{{$tableM->end}}" required>
            <label>Hora de finalización</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="about" id="about" class="form-control" rows="2">{{$tableM->about}}</textarea>
            <label>Descripción</label>
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-floating mb-3">
            <input type="file" id="image" accept="image/*" class="form-control" value="images/schedule/{{$tableM->image}}" >
            <label>Imagen</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="host" id="host" class="form-control" value="{{$tableM->host}}" required>
            <label>Director(a)</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="2" @if($tableM->day == 2) checked @endif> Lunes <br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="3" @if($tableM->day == 3) checked @endif> Martes <br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="4" @if($tableM->day == 4) checked @endif> Miércoles<br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="5" @if($tableM->day == 5) checked @endif> Jueves<br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="6" @if($tableM->day == 6) checked @endif> Viernes<br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="7" @if($tableM->day == 7) checked @endif> Sábado<br>
            <input class="form-check-input form-floating mb-3" type="checkbox" name="day[]" id="day" value="1" @if($tableM->day == 1) checked @endif> Domingo
            <label>Día(s) de emisión</label><br>
        </div>
    </div>
</div>
