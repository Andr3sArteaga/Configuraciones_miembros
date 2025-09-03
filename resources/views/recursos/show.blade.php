@extends('layouts.app')

@section('title', 'Recursos')
@section('content_header_title', 'Recursos')
@section('content_header_subtitle', 'Detalle')

@section('content_body')
    <x-adminlte-card title="Detalle del Recurso" theme="primary" icon="fas fa-box">
        <div class="row">
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-4">Código</dt>
                    <dd class="col-sm-8">{{ $recurso->codigo }}</dd>
                    <dt class="col-sm-4">Descripción</dt>
                    <dd class="col-sm-8">{{ $recurso->descripcion }}</dd>
                    <dt class="col-sm-4">Fecha de pedido</dt>
                    <dd class="col-sm-8">{{ optional($recurso->fecha_pedido)->format('d/m/Y') }}</dd>
                    <dt class="col-sm-4">Estado del pedido</dt>
                    <dd class="col-sm-8">{{ $recurso->estado_del_pedido }}</dd>
                    <dt class="col-sm-4">Equipo ID</dt>
                    <dd class="col-sm-8">{{ $recurso->equipoid }}</dd>
                    <dt class="col-sm-4">Lat/Lng</dt>
                    <dd class="col-sm-8">{{ $recurso->lat }}, {{ $recurso->lng }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('recursos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </x-adminlte-card>
@stop


