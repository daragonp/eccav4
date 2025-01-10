@extends('layouts.main')

@section('title', 'Tez brillante')

@section('content')
    <div class="mt-1">
        @include('ticker')
    </div>
    <div class="mt-1">
        @include('social')
    </div>
    <div class="mt-2">
        @include('quote')
    </div>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12 text-center">
                @include('eccard')
            </div>
        </div>
        <hr class="mt-4">
    </div>
@endsection
