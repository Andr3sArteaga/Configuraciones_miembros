@extends('layouts.app')

@section('title', 'Crear Equipo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Crear Equipo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipos.index') }}">Equipos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Información del Equipo
                        </h3>
                    </div>
                    <form action="{{ route('equipos.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h5><i class="icon fas fa-ban"></i> Errores de validación</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_equipo">
                                            Nombre del Equipo <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('nombre_equipo') is-invalid @enderror"
                                            id="nombre_equipo" name="nombre_equipo" value="{{ old('nombre_equipo') }}"
                                            placeholder="Ej: Equipo Alpha" required>
                                        @error('nombre_equipo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estado_id">
                                            Estado <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('estado_id') is-invalid @enderror" id="estado_id"
                                            name="estado_id" required>
                                            <option value="">Seleccione un estado</option>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                                    {{ $estado->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('estado_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Ubicación GPS (Opcional)</h5>
                                    <p class="text-muted">Haga clic en el mapa para establecer la ubicación del equipo</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="map" style="height: 400px; border-radius: 0.25rem;"></div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Haga clic en el mapa para colocar el
                                                marcador
                                                de ubicación
                                            </small>
                                            <button type="button" id="btn-remove-marker" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Eliminar Marcador
                                            </button>
                                        </div>
                                        <!-- Campos ocultos para las coordenadas -->
                                        <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
                                        <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="icon fas fa-info-circle"></i>
                                        <strong>Nota:</strong> La cantidad de integrantes se actualizará automáticamente
                                        cuando agregue miembros al equipo.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Equipo
                            </button>
                            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@stop

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas por defecto (Bolivia - centro aproximado)
            const defaultLat = -16.5000;
            const defaultLng = -64.5000;
            const defaultZoom = 6;

            // Inicializar el mapa
            map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

            // Agregar capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Cargar valores antiguos si existen (para validación fallida)
            const oldLat = document.getElementById('latitud').value;
            const oldLng = document.getElementById('longitud').value;

            if (oldLat && oldLng) {
                const lat = parseFloat(oldLat);
                const lng = parseFloat(oldLng);
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                map.setView([lat, lng], 13);

                // Actualizar coordenadas al arrastrar el marcador
                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }

            // Evento de clic en el mapa
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Si ya existe un marcador, moverlo
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    // Crear nuevo marcador draggable
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);

                    // Actualizar coordenadas al arrastrar
                    marker.on('dragend', function(e) {
                        const position = marker.getLatLng();
                        updateCoordinates(position.lat, position.lng);
                    });
                }

                updateCoordinates(lat, lng);
            });

            // Función para actualizar los campos de coordenadas
            function updateCoordinates(lat, lng) {
                const latInput = document.getElementById('latitud');
                const lngInput = document.getElementById('longitud');
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);

                // Hacer readonly para evitar edición manual
                latInput.setAttribute('readonly', 'readonly');
                lngInput.setAttribute('readonly', 'readonly');
            }

            // Prevenir edición manual de los campos
            document.getElementById('latitud').addEventListener('focus', function() {
                this.blur();
            });
            document.getElementById('longitud').addEventListener('focus', function() {
                this.blur();
            });

            // Botón para obtener ubicación actual
            const locationControl = L.Control.extend({
                options: {
                    position: 'topleft'
                },
                onAdd: function(map) {
                    const container = L.DomUtil.create('div',
                        'leaflet-bar leaflet-control leaflet-control-custom');
                    container.innerHTML =
                        '<a href="#" title="Usar mi ubicación actual" style="background-color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 18px;"><i class="fas fa-crosshairs"></i></a>';

                    container.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (navigator.geolocation) {
                            container.style.opacity = '0.5';
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    const lat = position.coords.latitude;
                                    const lng = position.coords.longitude;

                                    map.setView([lat, lng], 13);

                                    if (marker) {
                                        marker.setLatLng([lat, lng]);
                                    } else {
                                        marker = L.marker([lat, lng], {
                                            draggable: true
                                        }).addTo(map);

                                        marker.on('dragend', function(e) {
                                            const position = marker.getLatLng();
                                            updateCoordinates(position.lat, position
                                                .lng);
                                        });
                                    }

                                    updateCoordinates(lat, lng);
                                    container.style.opacity = '1';
                                },
                                function(error) {
                                    alert('Error al obtener la ubicación: ' + error.message);
                                    container.style.opacity = '1';
                                }
                            );
                        } else {
                            alert('Su navegador no soporta geolocalización');
                        }
                    };

                    return container;
                }
            });

            map.addControl(new locationControl());

            // Evento para eliminar el marcador
            document.getElementById('btn-remove-marker').addEventListener('click', function() {
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }
                document.getElementById('latitud').value = '';
                document.getElementById('longitud').value = '';
            });
        });
    </script>
@stop
