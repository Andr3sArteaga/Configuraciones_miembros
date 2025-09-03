@extends('layouts.app')

@section('title', 'Reportes de Incendio')
@section('content_header_title', 'Reportes de Incendio')
@section('content_header_subtitle', 'Detalle')

@section('content_body')
    <x-adminlte-card title="Detalle del Reporte de Incendio" theme="primary" icon="fas fa-fire">
        <div class="row">
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-5">Nombre del incidente</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->nombre_incidente }}</dd>
                    <dt class="col-sm-5">Controlado</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->controlado ? 'Sí' : 'No' }}</dd>
                    <dt class="col-sm-5">Extensión (ha)</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->extension }}</dd>
                    <dt class="col-sm-5">Clima</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->condiciones_clima }}</dd>
                    <dt class="col-sm-5">Equipos en uso</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->equipos_en_uso }}</dd>
                    <dt class="col-sm-5">N° Bomberos</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->numero_bomberos }}</dd>
                    <dt class="col-sm-5">¿Necesita más bomberos?</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->necesita_mas_bomberos ? 'Sí' : 'No' }}</dd>
                    <dt class="col-sm-5">Apoyo externo</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->apoyo_externo }}</dd>
                    <dt class="col-sm-5">Comentario adicional</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->comentario_adicional }}</dd>
                    <dt class="col-sm-5">Fecha de creación</dt>
                    <dd class="col-sm-7">{{ optional($reporte_incendio->fecha_creacion)->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-5">Usuario creador</dt>
                    <dd class="col-sm-7">{{ $reporte_incendio->id_usuario_creador }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('reportes_incendio.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('reportes_incendio.edit', $reporte_incendio) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </x-adminlte-card>
@stop


