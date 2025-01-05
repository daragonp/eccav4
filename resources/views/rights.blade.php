@extends('layouts.main')

@section('title', 'Derechos del titular')

@section('content')
    <div class="container"><br>
        <h1 class="center">Derechos del titular</h1>

        <iframe src ="{{asset('/laraview/#../documents/ecca/EccaDTv1.pdf')}}" width="100%" height="600px" style="padding-top: 20px;">
        </iframe>
    </div>
 @stop
