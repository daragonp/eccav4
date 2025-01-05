@extends('layouts.panel')

@section('title', 'Mirada Afro')

@section('pageheading', 'Mirada Afro')

@section('urlbtn', 'new-feed')
@section('addbutton', 'Nuevo tema de actualidad')
@section('mainheading', 'Mirada Afro - Publicaciones')

@section('datatable')
    {{-- {!! $dataTable->table() !!} --}}
@stop
@section('datatablscr')
    {{-- {!! $dataTable->scripts(attributes: ['type' => 'module']) !!} --}}
@stop
