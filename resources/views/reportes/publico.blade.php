@extends('layouts.app')

@section('title', 'Reporte Rápido')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reporte Rápido de Incidente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('focos-calor.index') }}">Mapa</a></li>
                    <li class="breadcrumb-item active">Reporte Rápido</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Información del Reporte</h3>
                    </div>
                    <form action="{{ route('reporte.publico.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Atención</h5>
                                Complete el formulario para reportar un incidente. Los campos marcados con
                                <span class="text-danger">*</span> son obligatorios.
                            </div>

                            <div class="row">
                                <!-- Información del Reportante -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_reportante">Nombre Completo <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nombre_reportante') is-invalid @enderror"
                                            id="nombre_reportante" name="nombre_reportante"
                                            value="{{ old('nombre_reportante') }}" required maxlength="200"
                                            placeholder="Ej: Juan Pérez">
                                        @error('nombre_reportante')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono_contacto">Teléfono de Contacto</label>
                                        <input type="text" class="form-control @error('telefono_contacto') is-invalid @enderror"
                                            id="telefono_contacto" name="telefono_contacto"
                                            value="{{ old('telefono_contacto') }}" maxlength="20"
                                            placeholder="Ej: 77123456">
                                        @error('telefono_contacto')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_incidente_id">Tipo de Incidente</label>
                                        <select class="form-control select2 @error('tipo_incidente_id') is-invalid @enderror"
                                            id="tipo_incidente_id" name="tipo_incidente_id" style="width: 100%;">
                                            <option value="">Seleccione un tipo</option>
                                            @foreach ($tiposIncidente as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    {{ old('tipo_incidente_id') == $tipo->id ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipo_incidente_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gravedad_id">Nivel de Gravedad</label>
                                        <select class="form-control select2 @error('gravedad_id') is-invalid @enderror"
                                            id="gravedad_id" name="gravedad_id" style="width: 100%;">
                                            <option value="">Seleccione un nivel</option>
                                            @foreach ($nivelesGravedad as $nivel)
                                                <option value="{{ $nivel->id }}"
                                                    {{ old('gravedad_id') == $nivel->id ? 'selected' : '' }}>
                                                    {{ $nivel->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gravedad_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nombre_lugar">Nombre del Lugar</label>
                                <input type="text" class="form-control @error('nombre_lugar') is-invalid @enderror"
                                    id="nombre_lugar" name="nombre_lugar" value="{{ old('nombre_lugar') }}"
                                    maxlength="200" placeholder="Ej: Cerca del parque central">
                                @error('nombre_lugar')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comentario_adicional">Comentarios Adicionales</label>
                                <textarea class="form-control @error('comentario_adicional') is-invalid @enderror"
                                    id="comentario_adicional" name="comentario_adicional" rows="3"
                                    placeholder="Describa con detalle lo que está sucediendo...">{{ old('comentario_adicional') }}</textarea>
                                @error('comentario_adicional')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Ubicación con Mapa -->
                            <div class="form-group">
                                <label>Ubicación del Incidente <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" class="form-control @error('latitud') is-invalid @enderror"
                                            id="latitud" name="latitud" value="{{ old('latitud') }}" step="0.000001"
                                            placeholder="Latitud" readonly required>
                                        @error('latitud')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control @error('longitud') is-invalid @enderror"
                                            id="longitud" name="longitud" value="{{ old('longitud') }}" step="0.000001"
                                            placeholder="Longitud" readonly required>
                                        @error('longitud')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <x-map.leaflet-map mapId="map-reporte-publico" lat="-17.3895" lng="-66.1568"
                                    zoom="6" height="400px" />
                                <small class="form-text text-muted">
                                    <i class="fas fa-map-marker-alt"></i> Haga clic en el mapa para marcar la ubicación
                                    del incidente
                                </small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-paper-plane"></i> Enviar Reporte
                            </button>
                            <a href="{{ route('focos-calor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                const map = window.mapInstance_map_reporte_publico;
                if (!map) return;
                clearInterval(waitForMap);

                let marker = null;

                // Click en el mapa para seleccionar ubicación
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    // Actualizar campos
                    document.getElementById('latitud').value = lat.toFixed(6);
                    document.getElementById('longitud').value = lng.toFixed(6);

                    // Quitar marcador anterior
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Agregar nuevo marcador
                    marker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            html: '<div style="background-color: #dc3545; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.5);"><i class="fas fa-exclamation-triangle" style="font-size: 14px;"></i></div>',
                            className: 'reporte-marker',
                            iconSize: [30, 30]
                        })
                    }).addTo(map);
                });

                // Si hay valores previos (por validación), mostrar marcador
                const latitud = document.getElementById('latitud').value;
                const longitud = document.getElementById('longitud').value;

                if (latitud && longitud) {
                    marker = L.marker([latitud, longitud], {
                        icon: L.divIcon({
                            html: '<div style="background-color: #dc3545; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.5);"><i class="fas fa-exclamation-triangle" style="font-size: 14px;"></i></div>',
                            className: 'reporte-marker',
                            iconSize: [30, 30]
                        })
                    }).addTo(map);
                    map.setView([latitud, longitud], 13);
                }
            }, 50);
        });
    </script>
@stop
