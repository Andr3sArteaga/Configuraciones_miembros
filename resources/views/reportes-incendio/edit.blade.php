@extends('layouts.app')

@section('title', 'Editar Reporte de Incendio')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Reporte de Incendio</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reportes-incendio.index') }}">Reportes</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('reportes-incendio.update', $reporte->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Información del Incidente -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-fire mr-1"></i>
                                Información del Incidente
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre_incidente">Nombre del Incidente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_incidente') is-invalid @enderror"
                                    id="nombre_incidente" name="nombre_incidente" 
                                    value="{{ old('nombre_incidente', $reporte->nombre_incidente) }}"
                                    required maxlength="255">
                                @error('nombre_incidente')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condicion_climatica_id">Condición Climática</label>
                                        <select class="form-control select2 @error('condicion_climatica_id') is-invalid @enderror"
                                            id="condicion_climatica_id" name="condicion_climatica_id" style="width: 100%;">
                                            <option value="">Seleccione una condición</option>
                                            @foreach ($condicionesClimaticas as $condicion)
                                                <option value="{{ $condicion->id }}"
                                                    {{ old('condicion_climatica_id', $reporte->condicion_climatica_id) == $condicion->id ? 'selected' : '' }}>
                                                    {{ $condicion->nombre }}
                                                    @if($condicion->factor_riesgo)
                                                        (Riesgo: {{ $condicion->factor_riesgo }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('condicion_climatica_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="extension">Extensión (hectáreas)</label>
                                        <input type="number" class="form-control @error('extension') is-invalid @enderror"
                                            id="extension" name="extension" 
                                            value="{{ old('extension', $reporte->extension) }}"
                                            min="0" step="0.01">
                                        @error('extension')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero_bomberos">Número de Bomberos Desplegados</label>
                                        <input type="number" class="form-control @error('numero_bomberos') is-invalid @enderror"
                                            id="numero_bomberos" name="numero_bomberos" 
                                            value="{{ old('numero_bomberos', $reporte->numero_bomberos) }}"
                                            min="0">
                                        @error('numero_bomberos')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="necesita_mas_bomberos"
                                                name="necesita_mas_bomberos" value="1"
                                                {{ old('necesita_mas_bomberos', $reporte->necesita_mas_bomberos) ? 'checked' : '' }}>
                                            <label for="necesita_mas_bomberos" class="custom-control-label">
                                                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                                                Necesita más ayuda?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="controlado"
                                                name="controlado" value="1"
                                                {{ old('controlado', $reporte->controlado) ? 'checked' : '' }}>
                                            <label for="controlado" class="custom-control-label">
                                                <i class="fas fa-check-circle text-success mr-1"></i>
                                                <strong>Marcar incendio como controlado</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="equipos_en_uso">Equipos en Uso</label>
                                <textarea class="form-control @error('equipos_en_uso') is-invalid @enderror"
                                    id="equipos_en_uso" name="equipos_en_uso" rows="2"
                                    placeholder="Describa los equipos utilizados">{{ old('equipos_en_uso', $reporte->equipos_en_uso) }}</textarea>
                                @error('equipos_en_uso')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="apoyo_externo">Apoyo Externo</label>
                                <textarea class="form-control @error('apoyo_externo') is-invalid @enderror"
                                    id="apoyo_externo" name="apoyo_externo" rows="2"
                                    placeholder="Indique si hay apoyo de otros organismos">{{ old('apoyo_externo', $reporte->apoyo_externo) }}</textarea>
                                @error('apoyo_externo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comentario_adicional">Comentarios Adicionales</label>
                                <textarea class="form-control @error('comentario_adicional') is-invalid @enderror"
                                    id="comentario_adicional" name="comentario_adicional" rows="3"
                                    placeholder="Información adicional relevante...">{{ old('comentario_adicional', $reporte->comentario_adicional) }}</textarea>
                                @error('comentario_adicional')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Informativo -->
                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Información del Reporte
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-sm">
                                <i class="fas fa-user-circle text-primary mr-1"></i>
                                <strong>Creado por:</strong><br>
                                @if ($reporte->usuario)
                                    {{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}
                                @else
                                    Sistema
                                @endif
                            </p>
                            <p class="text-sm">
                                <i class="fas fa-calendar-alt text-success mr-1"></i>
                                <strong>Fecha de creación:</strong><br>
                                {{ $reporte->fecha_creacion?->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm mb-0">
                                <i class="fas fa-fingerprint text-info mr-1"></i>
                                <strong>ID:</strong><br>
                                <small class="font-monospace">{{ $reporte->id }}</small>
                            </p>
                        </div>
                    </div>

                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-1"></i>
                                Edición
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-sm mb-0">
                                Puede actualizar la información del reporte de incendio. Los cambios se guardarán inmediatamente al hacer clic en "Actualizar Reporte".
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Actualizar Reporte
                            </button>
                            <a href="{{ route('reportes-incendio.show', $reporte->id) }}" class="btn btn-info">
                                <i class="fas fa-eye mr-1"></i> Ver Detalles
                            </a>
                            <a href="{{ route('reportes-incendio.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        .font-monospace {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>
@stop
