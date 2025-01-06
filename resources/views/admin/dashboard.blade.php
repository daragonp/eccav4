@php
    $showAddButton = false; // o la condición que desees
@endphp
@php
    $showModal = false;
@endphp

@extends('layouts.panel')

@section('title', 'Pánel de administrador')

@section('pageheading', 'Dashboard')

@section('urlbtn', 'dashboard')
@section('addbutton', 'Dashboard')
@section('mainheading', 'Bienvenido(a)')

@section('datatable')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <p class="lead">
                    Bienvenido al sistema de administración y creación de contenidos de <strong>Emancipación Cristiana
                        Afro</strong>;
                    este sistema está desarrollado en Laravel, un poderoso framework de PHP.
                </p>
                <p class="lead">
                    El sistema está pensado para ser robusto, escalable y con el poder de soportar las exigencias de la
                    World Wide Web.
                </p>
            </div>

            <div class="col-12">
                <p>
                    Para comenzar, en cada ítem del menú superior, encontrará una opción para crear contenido. Además, en
                    cada listado de los elementos, podrá tener acceso a las siguientes opciones:
                </p>
                <table class="table table-bordered table-responsive">
                    <thead class="text-center">
                        <tr>
                            <th class="fw-bold">Acción</th>
                            <th class="fw-bold">Íconos</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td>Crear contenido</td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2"><i class="fa-sharp fa-solid fa-plus-circle"></i>
                                    Crear</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Editar contenido</td>
                            <td>
                                <button class="btn btn-warning btn-sm me-2"><i
                                        class="fa-sharp fa-solid fa-pen-to-square"></i> Editar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Eliminar contenido</td>
                            <td>
                                <button class="btn btn-danger btn-sm me-2"><i class="fa-sharp fa-solid fa-trash"></i>
                                    Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Ver en detalle</td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2"><i class="fa-sharp fa-solid fa-eye"></i>
                                    Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Activar contenido</td>
                            <td>
                                <button class="btn btn-success btn-sm me-2"><i class="fa-sharp fa-solid fa-toggle-on"></i>
                                    Activar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Desactivar contenido</td>
                            <td>
                                <button class="btn btn-dark btn-sm me-2"><i class="fa-sharp fa-solid fa-power-off"></i>
                                    Desactivar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <p>
                    Por defecto, cada vez que se crea un contenido, este estará desactivado para que usted pueda revisar la
                    ortografía, dicción y sintaxis en detalle, haciendo clic en la opción <button
                        class="btn btn-primary btn-sm me-2"><i class="fa-sharp fa-solid fa-eye"></i> Ver</button>;
                    cuando ya esté seguro(a), entonces, debe hacer clic en el botón <button
                        class="btn btn-success btn-sm me-2"><i class="fa-sharp fa-solid fa-toggle-on"></i> Activar</button>
                    y de esta manera, el contenido estará visible al público.
                </p>
            </div>

            <div class="col-12">
                <p>
                    En el menú superior, verá su imagen de perfil. Si hace clic allí, tendrá algunas opciones; una de ellas
                    es la opción de sugerir nuevas características. Mediante ese enlace, abrirá un formulario para enviar al
                    Superadministrador del sistema la sugerencia de nuevas características y mejoras.
                </p>
            </div>
        </div>
    </div>
@stop
