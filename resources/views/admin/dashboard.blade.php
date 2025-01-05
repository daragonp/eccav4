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
    <div class="row">
        <p>Bienvenido al sistema de administración y creación de contenidos de Emancipación Cristiana Afro; este sistema
            está desarrollado en Laravel, un poderoso framework de PHP.</p>
        <p>El sistema está pensado para ser robusto, escalable y con el poder de soportar las exigencias de la Word Wide
            Web.</p>
        <p>Para comenzar, usted en cada item del menú superior, encontrará una opción para crear contenido. Además, en cada
            listado de los elementos, podrá tener acceso a las opciones siguientes: <button
                class="btn btn-primary">Crear...</button>, editar contenido,<i
                class="fa-sharp fa-solid fa-pen-to-square btn btn-warning"></i>
            eliminarlo<i class="fa-sharp fa-solid fa-trash btn btn-danger"></i>, ver en detalle <i
                class="fa-sharp fa-solid fa-eye btn btn-primary"></i>, activar<i
                class="fa-sharp fa-2x fa-solid fa-toggle-on"></i> y desactivar<i
                class="fa-sharp fa-solid fa-power-off btn btn-dark"></i>
        </p>
        <p>Por defecto, cada vez que se crea un contenido, este estará desactivado para que usted puede revisar la
            ortografía, dicción, y sintaxis en detalle, haciendo clic en la opción ver <i
                class="fa-sharp fa-solid fa-eye btn btn-primary"></i>; cuando ya esté seguro(a), entonces, debe hacer clic en
            activar <i class="fa-sharp fa-2x fa-solid fa-toggle-on"></i> y de esta manera, el contenido estará visible al
            público.</p>
        En el menú superior, verá su imagen de perfil, si hace clic ahí, tendrá algunas opciones; una de ellas, es la opción
        de sugerir nuevas características; mediante ese enlace, abrirá un formulario para enviar al Superadministrador del
        sistema, la sugerencia de nuevas características y mejoras para el mismo.


    </div>
@stop
