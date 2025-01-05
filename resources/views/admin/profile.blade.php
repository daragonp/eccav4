@php
    $showAddButton = false; // o la condición que desees
@endphp
@php
    $showModal = false;
@endphp

@extends('layouts.panel')

@section('title', 'Perfil de usuario')

@section('datatable')

    <div class="container rounded bg-white mb-5">
        <div class="row">
            <div class="col-md-4 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                        width="150px" src="{{ asset('images/user/' . $user->image) }}"><span
                        class="font-weight-bold">{{ $user->name }}</span><span
                        class="text-black-50">{{ $user->email }}</span><span> </span></div>
            </div>
            <div class="col-md-8 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Configuración del perfil</h4>
                    </div>
                    <!-- En el formulario de edición -->
                    <form action="{{ ($user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mt-2">
                            <div class="col-md-12"><label class="labels">Nombre</label><input type="text"
                                    class="form-control" value="{{ $user->name }}"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Teléfono</label><input type="number"
                                    class="form-control" placeholder="Ingrese un número de teléfono válido"
                                    value="{{ $user->phone }}"></div>
                            <div class="col-md-12"><label class="labels">Correo electrónico</label><input type="email"
                                    class="form-control" placeholder="Ingrese una dirección de email válida"
                                    value="{{ $user->email }}"></div>
                            <div class="col-md-12"><label class="labels">Fecha de nacimiento</label><input type="date"
                                    class="form-control" placeholder="education" value="{{ $user->birthdate }}"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">Contraseña</label><input type="password"
                                    class="form-control" placeholder="*********************"></div>
                            <div class="col-md-6"><label class="labels">Repetir contraseña</label><input type="password"
                                    class="form-control"
                                    placeholder="Para cambiar la contraseña, debe escribirla en ambas cajas de texto"></div>
                        </div>
                        <button class="btn btn-primary mt-2" type="submit">Actualizar datos</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
