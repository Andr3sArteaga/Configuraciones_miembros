@extends('layouts.app')

@section('title', 'Editar Reporte')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Reporte</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('reportes.update', $reporte->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Información del Reportante -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Información del Reportante</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre_reportante">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_reportante') is-invalid @enderror"
                                    id="nombre_reportante" name="nombre_reportante"
                                    value="{{ old('nombre_reportante', $reporte->nombre_reportante) }}" required
                                    maxlength="200">
                                @error('nombre_reportante')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefono_contacto">Teléfono de Contacto</label>
                                <input type="text" class="form-control @error('telefono_contacto') is-invalid @enderror"
                                    id="telefono_contacto" name="telefono_contacto"
                                    value="{{ old('telefono_contacto', $reporte->telefono_contacto) }}" maxlength="20">
                                @error('telefono_contacto')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fecha_hora">Fecha y Hora del Incidente <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('fecha_hora') is-invalid @enderror"
                                    id="fecha_hora" name="fecha_hora"
                                    value="{{ old('fecha_hora', $reporte->fecha_hora->format('Y-m-d\TH:i')) }}" required>
                                @error('fecha_hora')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información del Incidente -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Información del Incidente</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tipo_incidente_id">Tipo de Incidente</label>
                                <select class="form-control select2 @error('tipo_incidente_id') is-invalid @enderror"
                                    id="tipo_incidente_id" name="tipo_incidente_id" style="width: 100%;">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tiposIncidente as $tipo)
                                        <option value="{{ $tipo->id }}"
                                            {{ old('tipo_incidente_id', $reporte->tipo_incidente_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_incidente_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gravedad_id">Nivel de Gravedad</label>
                                <select class="form-control select2 @error('gravedad_id') is-invalid @enderror"
                                    id="gravedad_id" name="gravedad_id" style="width: 100%;">
                                    <option value="">Seleccione un nivel</option>
                                    @foreach ($nivelesGravedad as $nivel)
                                        <option value="{{ $nivel->id }}"
                                            {{ old('gravedad_id', $reporte->gravedad_id) == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gravedad_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="estado_id">Estado del Reporte</label>
                                <select class="form-control select2 @error('estado_id') is-invalid @enderror" id="estado_id"
                                    name="estado_id" style="width: 100%;">
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ old('estado_id', $reporte->estado_id) == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comentario_adicional">Comentarios Adicionales</label>
                                <textarea class="form-control @error('comentario_adicional') is-invalid @enderror" id="comentario_adicional"
                                    name="comentario_adicional" rows="4">{{ old('comentario_adicional', $reporte->comentario_adicional) }}</textarea>
                                @error('comentario_adicional')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Recursos Necesarios -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Recursos Necesarios</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cant_bomberos">
                                            <i class="fas fa-fire-extinguisher text-danger"></i> Bomberos
                                        </label>
                                        <input type="number"
                                            class="form-control @error('cant_bomberos') is-invalid @enderror"
                                            id="cant_bomberos" name="cant_bomberos"
                                            value="{{ old('cant_bomberos', $reporte->cant_bomberos) }}" min="0">
                                        @error('cant_bomberos')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cant_paramedicos">
                                            <i class="fas fa-ambulance text-info"></i> Paramédicos
                                        </label>
                                        <input type="number"
                                            class="form-control @error('cant_paramedicos') is-invalid @enderror"
                                            id="cant_paramedicos" name="cant_paramedicos"
                                            value="{{ old('cant_paramedicos', $reporte->cant_paramedicos) }}"
                                            min="0">
                                        @error('cant_paramedicos')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cant_veterinarios">
                                            <i class="fas fa-paw text-success"></i> Veterinarios
                                        </label>
                                        <input type="number"
                                            class="form-control @error('cant_veterinarios') is-invalid @enderror"
                                            id="cant_veterinarios" name="cant_veterinarios"
                                            value="{{ old('cant_veterinarios', $reporte->cant_veterinarios) }}"
                                            min="0">
                                        @error('cant_veterinarios')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cant_autoridades">
                                            <i class="fas fa-shield-alt text-warning"></i> Autoridades
                                        </label>
                                        <input type="number"
                                            class="form-control @error('cant_autoridades') is-invalid @enderror"
                                            id="cant_autoridades" name="cant_autoridades"
                                            value="{{ old('cant_autoridades', $reporte->cant_autoridades) }}"
                                            min="0">
                                        @error('cant_autoridades')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ubicación con Mapa -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Ubicación del Incidente</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre_lugar">Nombre del Lugar</label>
                                <input type="text" class="form-control @error('nombre_lugar') is-invalid @enderror"
                                    id="nombre_lugar" name="nombre_lugar"
                                    value="{{ old('nombre_lugar', $reporte->nombre_lugar) }}" maxlength="200">
                                @error('nombre_lugar')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitud">Latitud</label>
                                        <input type="number" class="form-control @error('latitud') is-invalid @enderror"
                                            id="latitud" name="latitud"
                                            value="{{ old('latitud', data_get($reporte->ubicacion, 'coordinates.1')) }}"
                                            step="0.000001" readonly>
                                        @error('latitud')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitud">Longitud</label>
                                        <input type="number"
                                            class="form-control @error('longitud') is-invalid @enderror" id="longitud"
                                            name="longitud"
                                            value="{{ old('longitud', data_get($reporte->ubicacion, 'coordinates.0')) }}"
                                            step="0.000001" readonly>
                                        @error('longitud')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Seleccione la ubicación en el mapa</label>
                                @php
                                    $lat = '-17.3895';
                                    $lng = '-66.1568';
                                    $zoom = 6;

                                    if ($reporte->ubicacion) {
                                        $coords = is_array($reporte->ubicacion)
                                            ? $reporte->ubicacion['coordinates']
                                            : $reporte->ubicacion->coordinates;
                                        if (is_array($coords) && count($coords) >= 2) {
                                            $lat = $coords[1];
                                            $lng = $coords[0];
                                            $zoom = 13;
                                        }
                                    }
                                @endphp
                                <x-map.leaflet-map mapId="map-edit-reporte" lat="{{ $lat }}"
                                    lng="{{ $lng }}" zoom="{{ $zoom }}" height="400px" />
                                <small class="form-text text-muted">Haga clic en el mapa para actualizar la
                                    ubicación</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar Reporte
                            </button>
                            <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            // Esperar a que el mapa se inicialice
            const waitForMap = setInterval(function() {
                const map = window.mapInstance_map_edit_reporte;
                if (!map) return;
                clearInterval(waitForMap);

                let marker = null;

                // Click en el mapa para seleccionar ubicación
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    document.getElementById('latitud').value = lat.toFixed(6);
                    document.getElementById('longitud').value = lng.toFixed(6);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                });

                // Cargar ubicación actual si existe (viene como GeoJSON a través del accessor 'ubicacion')
                const reporteUbicacion = @json($reporte->ubicacion ?? null);
                if (reporteUbicacion && Array.isArray(reporteUbicacion.coordinates)) {
                    const latInit = reporteUbicacion.coordinates[1];
                    const lngInit = reporteUbicacion.coordinates[0];

                    document.getElementById('latitud').value = latInit.toFixed(6);
                    document.getElementById('longitud').value = lngInit.toFixed(6);

                    marker = L.marker([latInit, lngInit]).addTo(map);
                    map.setView([latInit, lngInit], 13);
                }
            }, 50);
        });
    </script>
@stop
