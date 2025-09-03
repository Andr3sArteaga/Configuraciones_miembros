@extends('layouts.app')

@section('title', 'Reportes de Incendio')
@section('content_header_title', 'Reportes de Incendio')
@section('content_header_subtitle', 'Editar')

@section('content_body')
    <x-adminlte-card title="Editar Reporte de Incendio" theme="primary" icon="fas fa-fire">
        <form action="{{ route('reportes_incendio.update', $reporte_incendio) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre_incidente" label="Nombre del incidente" value="{{ old('nombre_incidente', $reporte_incendio->nombre_incidente) }}" required />
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label>Controlado</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="controlado" name="controlado" value="1" {{ $reporte_incendio->controlado ? 'checked' : '' }}>
                            <label class="custom-control-label" for="controlado">Sí</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <x-adminlte-input name="extension" type="number" step="any" label="Extensión (ha)" value="{{ old('extension', $reporte_incendio->extension) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="condiciones_clima" label="Condiciones del clima" value="{{ old('condiciones_clima', $reporte_incendio->condiciones_clima) }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="equipos_en_uso" label="Equipos en uso" value="{{ old('equipos_en_uso', $reporte_incendio->equipos_en_uso) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="numero_bomberos" type="number" label="N° Bomberos" value="{{ old('numero_bomberos', $reporte_incendio->numero_bomberos) }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>¿Necesita más bomberos?</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="necesita_mas_bomberos" name="necesita_mas_bomberos" value="1" {{ $reporte_incendio->necesita_mas_bomberos ? 'checked' : '' }}>
                            <label class="custom-control-label" for="necesita_mas_bomberos">Sí</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="apoyo_externo" label="Apoyo externo" value="{{ old('apoyo_externo', $reporte_incendio->apoyo_externo) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-adminlte-textarea name="comentario_adicional" label="Comentario adicional">{{ old('comentario_adicional', $reporte_incendio->comentario_adicional) }}</x-adminlte-textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="fecha_creacion" type="datetime-local" label="Fecha de creación" value="{{ old('fecha_creacion', optional($reporte_incendio->fecha_creacion)->format('Y-m-d\TH:i')) }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="id_usuario_creador" label="Usuario creador (UUID)" value="{{ old('id_usuario_creador', $reporte_incendio->id_usuario_creador) }}" />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('reportes_incendio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop
