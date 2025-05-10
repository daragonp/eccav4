@extends('layouts.main')

@section('title', 'Tez brillante')

@section('content')
    <div>
        @include('ticker')
    </div>
    <div>
        <div>
            @include('eccard')
        </div>
    </div>
    <div class="mt-1">
        @include('social')
    </div>
    <div class="mt-2">
        @include('quote')
    </div>
@endsection
