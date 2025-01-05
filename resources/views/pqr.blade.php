@extends('layouts.main')

@section('title', 'Peticios, Quejas, Recursos')

@section('content')
    <div class="container"><br>
        <h1 class="center">PQRS en línea</h1>
        <div class="container"><br>
            <iframe src ="{{asset('/laraview/#../documents/ecca/EccaPQRv1.pdf')}}" width="100%" height="600px" style="padding-top: 20px;">
            </iframe>
        </div>
            <a href="mailto:radio@emancipacioncristianaafro.org" class="btn btn-info mt-4 mb-2 float-end">Crear una solicitud</a><br><br><br>
    </div>
 @stop
