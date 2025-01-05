@extends('layouts.main')

@section('title', 'Política de privacidad')

@section('content')
    <div class="container"><br>
        <h1 class="center">Política de privacidad y tratamiento de datos</h1>

        <iframe src ="{{asset('/laraview/#../documents/ecca/EccaPTDPv1.pdf')}}" width="100%" height="600px" style="padding-top: 20px;">
        </iframe>
    </div>
 @stop
