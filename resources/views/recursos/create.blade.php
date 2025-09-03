@extends('layouts.app')

@section('title', 'Recursos')
@section('content_header_title', 'Recursos')
@section('content_header_subtitle', 'Crear')

@section('content_body')
    <x-adminlte-card title="Nuevo Recurso" theme="primary" icon="fas fa-box">
        <form action="{{ route('recursos.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="codigo" label="Código" value="{{ old('codigo') }}" />
                </div>
                <div class="col-md-8 mb-3">
                    <x-adminlte-input name="descripcion" label="Descripción" value="{{ old('descripcion') }}" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="fecha_pedido" type="date" label="Fecha de pedido" value="{{ old('fecha_pedido') }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="estado_del_pedido" label="Estado del pedido" value="{{ old('estado_del_pedido') }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="equipoid" label="Equipo ID" value="{{ old('equipoid') }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="lat" type="number" step="any" label="Latitud" value="{{ old('lat') }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="lng" type="number" step="any" label="Longitud" value="{{ old('lng') }}" />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('recursos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop


