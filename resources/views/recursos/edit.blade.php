@extends('layouts.app')

@section('title', 'Recursos')
@section('content_header_title', 'Recursos')
@section('content_header_subtitle', 'Editar')

@section('content_body')
    <x-adminlte-card title="Editar Recurso" theme="primary" icon="fas fa-box-open">
        <form action="{{ route('recursos.update', $recurso) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="codigo" label="Código" value="{{ old('codigo', $recurso->codigo) }}" />
                </div>
                <div class="col-md-8 mb-3">
                    <x-adminlte-input name="descripcion" label="Descripción" value="{{ old('descripcion', $recurso->descripcion) }}" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="fecha_pedido" type="date" label="Fecha de pedido" value="{{ old('fecha_pedido', optional($recurso->fecha_pedido)->format('Y-m-d')) }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="estado_del_pedido" label="Estado del pedido" value="{{ old('estado_del_pedido', $recurso->estado_del_pedido) }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="equipoid" label="Equipo ID" value="{{ old('equipoid', $recurso->equipoid) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="lat" type="number" step="any" label="Latitud" value="{{ old('lat', $recurso->lat) }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="lng" type="number" step="any" label="Longitud" value="{{ old('lng', $recurso->lng) }}" />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('recursos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop


