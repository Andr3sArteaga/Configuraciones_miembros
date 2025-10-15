@extends('layouts.app')
@section('title', 'Mapa de Incendios')
@section('content_header_title', 'Mapa de Incendios')
@section('content_header_subtitle', 'Visualización')

@section('content_body')
    <x-adminlte-card title="Mapa de Incendios" theme="red" icon="fas fa-map">
        @include('mapa_incendios.mapa')
    </x-adminlte-card>
@stop
