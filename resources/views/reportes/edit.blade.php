@extends('layouts.app')

@section('title', 'Reportes')
@section('content_header_title', 'Reportes')
@section('content_header_subtitle', 'Editar')

@section('content_body')
    <x-adminlte-card title="Editar Reporte" theme="primary" icon="fas fa-file-edit">
        <form action="{{ route('reportes.update', $reporte) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre_reportante" label="Nombre del reportante" value="{{ old('nombre_reportante', $reporte->nombre_reportante) }}" required />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="telefono_contacto" label="Teléfono de contacto" value="{{ old('telefono_contacto', $reporte->telefono_contacto) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="fecha_hora" type="datetime-local" label="Fecha y hora" value="{{ old('fecha_hora', optional($reporte->fecha_hora)->format('Y-m-d\TH:i')) }}" required />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre_lugar" label="Nombre del lugar" value="{{ old('nombre_lugar', $reporte->nombre_lugar) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="tipo_incendio">Tipo de incendio</label>
                        <select name="tipo_incendio" id="tipo_incendio" class="form-control">
                            <option value="">Seleccionar tipo</option>
                            <option value="Forestal" {{ old('tipo_incendio', $reporte->tipo_incendio) == 'Forestal' ? 'selected' : '' }}>Forestal</option>
                            <option value="Estructural" {{ old('tipo_incendio', $reporte->tipo_incendio) == 'Estructural' ? 'selected' : '' }}>Estructural</option>
                            <option value="Vehicular" {{ old('tipo_incendio', $reporte->tipo_incendio) == 'Vehicular' ? 'selected' : '' }}>Vehicular</option>
                            <option value="Industrial" {{ old('tipo_incendio', $reporte->tipo_incendio) == 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="Otro" {{ old('tipo_incendio', $reporte->tipo_incendio) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="gravedad_incendio">Gravedad</label>
                        <select name="gravedad_incendio" id="gravedad_incendio" class="form-control">
                            <option value="">Seleccionar gravedad</option>
                            <option value="Leve" {{ old('gravedad_incendio', $reporte->gravedad_incendio) == 'Leve' ? 'selected' : '' }}>Leve</option>
                            <option value="Moderado" {{ old('gravedad_incendio', $reporte->gravedad_incendio) == 'Moderado' ? 'selected' : '' }}>Moderado</option>
                            <option value="Grave" {{ old('gravedad_incendio', $reporte->gravedad_incendio) == 'Grave' ? 'selected' : '' }}>Grave</option>
                            <option value="Crítico" {{ old('gravedad_incendio', $reporte->gravedad_incendio) == 'Crítico' ? 'selected' : '' }}>Crítico</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-adminlte-textarea name="comentario_adicional" label="Comentario adicional">{{ old('comentario_adicional', $reporte->comentario_adicional) }}</x-adminlte-textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <x-adminlte-input name="cant_bomberos" type="number" label="Bomberos" value="{{ old('cant_bomberos', $reporte->cant_bomberos) }}" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-adminlte-input name="cant_paramedicos" type="number" label="Paramédicos" value="{{ old('cant_paramedicos', $reporte->cant_paramedicos) }}" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-adminlte-input name="cant_veterinarios" type="number" label="Veterinarios" value="{{ old('cant_veterinarios', $reporte->cant_veterinarios) }}" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-adminlte-input name="cant_autoridades" type="number" label="Autoridades" value="{{ old('cant_autoridades', $reporte->cant_autoridades) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Seleccionar estado</option>
                            <option value="PENDIENTE" {{ old('estado', $reporte->estado) == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                            <option value="EN_PROCESO" {{ old('estado', $reporte->estado) == 'EN_PROCESO' ? 'selected' : '' }}>EN_PROCESO</option>
                            <option value="CONTROLADO" {{ old('estado', $reporte->estado) == 'CONTROLADO' ? 'selected' : '' }}>CONTROLADO</option>
                            <option value="EXTINGUIDO" {{ old('estado', $reporte->estado) == 'EXTINGUIDO' ? 'selected' : '' }}>EXTINGUIDO</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop


