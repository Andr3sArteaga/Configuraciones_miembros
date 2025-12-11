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

                            <!-- Reporte de Animales -->
                            <hr class="my-4">
                            <h5 class="mb-3"><i class="fas fa-paw text-warning"></i> Información de Animales</h5>
                            <div class="form-group">
                                <label>¿Hay algún animal herido presente?</label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="animal_si" name="animal_presente"
                                        value="si">
                                    <label for="animal_si" class="custom-control-label">Sí</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="animal_no" name="animal_presente"
                                        value="no" checked>
                                    <label for="animal_no" class="custom-control-label">No</label>
                                </div>
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

        <!-- Modal Animales -->
        <div class="modal fade" id="animalModal" tabindex="-1" role="dialog" aria-labelledby="animalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="animalModalLabel">Detalles del Animal Herido</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Imagen -->
                        <div class="form-group">
                            <label>Imagen</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imagen_animal"
                                        name="imagen_animal">
                                    <label class="custom-file-label" for="imagen_animal">Subir la imagen del
                                        animal</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Subir</span>
                                </div>
                            </div>
                            <div class="mt-2 text-center" id="imagen_preview_container" style="display: none;">
                                <img id="imagen_preview" src="#" alt="Vista previa" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Estado inicial -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado inicial del animal</label>
                                    <select class="form-control" name="estado_animal">
                                        <option value="Atascado / atrapado">Atascado / atrapado</option>
                                        <option value="Desconocido">Desconocido</option>
                                        <option value="Deshidratado">Deshidratado</option>
                                        <option value="Desorientado / shock">Desorientado / shock</option>
                                        <option value="Difícil acceso">Difícil acceso</option>
                                        <option value="Herido grave">Herido grave</option>
                                        <option value="Herido leve">Herido leve</option>
                                        <option value="Inconsciente">Inconsciente</option>
                                        <option value="Quemaduras">Quemaduras</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Tipo de incidente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de incidente</label>
                                    <select class="form-control" name="tipo_incidente_animal">
                                        <option value="Incendio cercano - Alto">Incendio cercano - Alto</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tamaño -->
                        <div class="form-group">
                            <label>Tamaño del animal</label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mr-3">
                                    <input class="custom-control-input" type="radio" id="tamano_pequeno"
                                        name="tamano_animal" value="pequeno">
                                    <label for="tamano_pequeno" class="custom-control-label">Pequeño</label>
                                </div>
                                <div class="custom-control custom-radio mr-3">
                                    <input class="custom-control-input" type="radio" id="tamano_mediano"
                                        name="tamano_animal" value="mediano" checked>
                                    <label for="tamano_mediano" class="custom-control-label">Mediano</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="tamano_grande"
                                        name="tamano_animal" value="grande">
                                    <label for="tamano_grande" class="custom-control-label">Grande</label>
                                </div>
                            </div>
                        </div>

                        <!-- Puede moverse -->
                        <div class="form-group">
                            <label>¿Puede moverse?</label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mr-3">
                                    <input class="custom-control-input" type="radio" id="moverse_si"
                                        name="puede_moverse" value="si">
                                    <label for="moverse_si" class="custom-control-label">Sí</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="moverse_no"
                                        name="puede_moverse" value="no" checked>
                                    <label for="moverse_no" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Guardar y Cerrar</button>
                    </div>
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

                    // Reverse Geocoding con Nominatim
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.display_name) {
                                document.getElementById('nombre_lugar').value = data.display_name;
                            }
                        })
                        .catch(error => console.error('Error en geocoding:', error));

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

            // Mostrar modal si se selecciona "Sí" en animal herido
            $('input[name="animal_presente"]').change(function() {
                if (this.value === 'si') {
                    $('#animalModal').modal('show');
                }
            });

            // Actualizar label del input file y mostrar preview
            $('.custom-file-input').on('change', function() {
                // Update label
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);

                // Show preview
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagen_preview').attr('src', e.target.result);
                        $('#imagen_preview_container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imagen_preview_container').hide();
                }
            });

            // Intercept form submission
            $('form').on('submit', function(e) {
                const animalPresente = $('input[name="animal_presente"]:checked').val();
                
                if (animalPresente === 'si') {
                    e.preventDefault(); // Stop normal submission
                    
                    // 1. Submit Main Report via AJAX
                    const mainForm = $(this);
                    const mainFormData = new FormData(this);
                    
                    // Show loading state
                    const submitBtn = mainForm.find('button[type="submit"]');
                    const originalBtnText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                    $.ajax({
                        url: mainForm.attr('action'),
                        method: 'POST',
                        data: mainFormData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Force JSON response
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            if (response.success && response.id) {
                                // 2. Send Animal Report
                                sendAnimalReport(response.id, submitBtn, originalBtnText);
                            } else {
                                alert('Error al guardar el reporte principal: ' + (response.message || 'Desconocido'));
                                submitBtn.prop('disabled', false).html(originalBtnText);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            alert('Error al guardar el reporte: ' + (xhr.responseJSON?.message || 'Error de servidor'));
                            submitBtn.prop('disabled', false).html(originalBtnText);
                        }
                    });
                }
                // If 'no', let it submit normally
            });

            function sendAnimalReport(incendioId, btn, originalText) {
                const animalFormData = new FormData();
                
                // Static mappings for IDs (Simulated for Microservice)
                const conditionMap = {
                    'Atascado / atrapado': 1,
                    'Desconocido': 2,
                    'Deshidratado': 3,
                    'Desorientado / shock': 4,
                    'Difícil acceso': 5,
                    'Herido grave': 6,
                    'Herido leve': 7,
                    'Inconsciente': 8,
                    'Quemaduras': 9
                };
                
                const incidentTypeMap = {
                    'Incendio cercano - Alto': 1
                };

                // Prepare Data
                const estado = $('select[name="estado_animal"]').val();
                const tipo = $('select[name="tipo_incidente_animal"]').val();
                const tamano = $('input[name="tamano_animal"]:checked').val();
                const moverse = $('input[name="puede_moverse"]:checked').val() === 'si';
                const imagen = $('#imagen_animal')[0].files[0];
                
                // Get lat/lon from main form
                const lat = $('#latitud').val();
                const lng = $('#longitud').val();
                const obs = $('#comentario_adicional').val();
                
                // Append fields as per requirement
                animalFormData.append('incendio_id', incendioId);
                animalFormData.append('latitud', lat);
                animalFormData.append('longitud', lng);
                animalFormData.append('direccion', $('#nombre_lugar').val() || '');
                animalFormData.append('observaciones', obs || 'Sin observaciones adicionales');
                animalFormData.append('condicion_inicial_id', conditionMap[estado] || 2);
                animalFormData.append('tipo_incidente_id', incidentTypeMap[tipo] || 1);
                animalFormData.append('tamano', tamano);
                animalFormData.append('puede_moverse', moverse);
                animalFormData.append('traslado_inmediato', false); // Default false
                // Don't append centro_id since it's null
                
                if (imagen) {
                    animalFormData.append('imagen', imagen);
                } else {
                    animalFormData.append('imagen', null); // Image is optional
                }

                // Send to API endpoint
                $.ajax({
                    url: '/api/v1/reports', 
                    method: 'POST',
                    data: animalFormData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Success! Redirect to map with success message
                        window.location.href = '{{ route("focos-calor.index") }}?success=Reporte+y+Animal+guardados';
                    },
                    error: function(xhr) {
                        console.error('Animal Report Error:', xhr);
                        // Even if animal fails, main report was saved. Redirect with warning.
                        alert('Reporte de incendio guardado, pero falló el reporte animal: ' + (xhr.responseJSON?.message || 'Error'));
                        window.location.href = '{{ route("focos-calor.index") }}';
                    }
                });
            }
        });
    </script>
@stop
