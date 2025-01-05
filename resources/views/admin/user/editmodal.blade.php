<div class="row">
    <div class="col-md-4">
        <div class="form-floating mb-3">
            <img src="images/user/{{ $tableM->image }}" alt="Imagen de {{ $tableM->name }}" width="200px">
            <p>Imagen actual</p>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="image" id="image" class="form-control" value="{{ $tableM->image }}" required>
            <label>Cambiar imagen de perfil</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" value="{{ $tableM->name }}"
                required>
            <label>Nombre completo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" name="email" id="email" class="form-control" value="{{ $tableM->email }}"
                required>
            <label>Correo electrónico</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $tableM->phone }}">
            <label>Teléfono</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $tableM->birthdate }}">
            <label>Fecha de nacimiento</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mb-3">
            <input type="password" name="password" id="password" class="form-control" value="" required>
            <label>Contraseña</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="cpassword" id="cpassword" class="form-control" value="" required>
            <label>Repetir contraseña</label>
        </div>
        <div class="form-floating mb-3">
            <label>Rol: {{ Auth::user()->roles->pluck('name') }}</label>
        </div>
        <div class="form-floating mb-3">
            <h6>Estado</h6>
            @if($tableM->deleted_at)
                <h3 style="color: green">ACTIVO</h3>
            @else
                <h3 style="color: crimson">INACTIVO</h3>
                <p>ADEVERTENCIA: Los datos no podrán ser actualizados debido a que su usuario está desactivado; por
                    favor, contacte al administrador.</p>
            @endif
        </div>
    </div>
</div>
