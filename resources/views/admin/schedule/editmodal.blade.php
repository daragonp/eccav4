<div class="row">
    {{-- Columna izquierda --}}
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" value="{{ $tableM->name }}" required>
            <label>Nombre del programa</label>
        </div>

        <div class="form-floating mb-3">
            {{-- Formatear la hora de inicio a HH:MM --}}
            <input type="time" name="start" class="form-control" value="{{ old('start', \Carbon\Carbon::parse($tableM->start)->format('H:i')) }}" required>
            <label>Hora de inicio</label>
        </div>

        <div class="form-floating mb-3">
            {{-- Formatear la hora de finalización a HH:MM --}}
            <input type="time" name="end" class="form-control" value="{{ old('end', \Carbon\Carbon::parse($tableM->end)->format('H:i')) }}" required>
            <label>Hora de finalización</label>
        </div>

        <div class="form-floating mb-3">
            <textarea name="about" class="form-control" style="height: 100px;">{{ $tableM->about }}</textarea>
            <label>Descripción</label>
        </div>
    </div>

    {{-- Columna derecha --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Imagen actual</label><br>
            <img src="{{ asset('images/schedule/' . $tableM->image) }}" alt="Imagen del programa"
                class="img-fluid rounded shadow" style="max-height: 150px;">
        </div>

        <div class="form-floating mb-3">
            <input type="file" name="image" accept="image/*" class="form-control">
            <label>Cambiar imagen</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="host" class="form-control" value="{{ $tableM->host }}" required>
            <label>Director(a)</label>
        </div>

        <div class="mb-2">
            <label class="form-label">Día(s) de emisión</label>
            @php
                $dias = [
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes',
                    6 => 'Sábado',
                    7 => 'Domingo',
                ];
            @endphp

            @foreach ($dias as $num => $nombre)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="day[]" value="{{ $num }}"
                        id="day_{{ $num }}" @if (isset($daysSelected) && in_array($num, $daysSelected)) checked @endif>
                    <label class="form-check-label" for="day_{{ $num }}">{{ $nombre }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>