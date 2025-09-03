@extends('layouts.app')

@section('title', 'Reportes')
@section('content_header_title', 'Reportes')
@section('content_header_subtitle', 'Detalle')

@section('content_body')
    <x-adminlte-card title="Detalle del Reporte" theme="primary" icon="fas fa-file">
        <div class="row">
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-4">Reportante</dt>
                    <dd class="col-sm-8">{{ $reporte->nombre_reportante }}</dd>
                    <dt class="col-sm-4">Teléfono</dt>
                    <dd class="col-sm-8">{{ $reporte->telefono_contacto }}</dd>
                    <dt class="col-sm-4">Fecha y hora</dt>
                    <dd class="col-sm-8">{{ optional($reporte->fecha_hora)->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-4">Lugar</dt>
                    <dd class="col-sm-8">{{ $reporte->nombre_lugar }}</dd>
                    <dt class="col-sm-4">Tipo</dt>
                    <dd class="col-sm-8">{{ $reporte->tipo_incendio }}</dd>
                    <dt class="col-sm-4">Gravedad</dt>
                    <dd class="col-sm-8">{{ $reporte->gravedad_incendio }}</dd>
                    <dt class="col-sm-4">Comentario</dt>
                    <dd class="col-sm-8">{{ $reporte->comentario_adicional }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">{{ $reporte->estado }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('reportes.edit', $reporte) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </x-adminlte-card>
@stop


