{{-- Partial for Create/Edit Team Modal --}}
{{-- This partial is included in both index and edit views --}}
{{-- Pass $equipo variable for edit mode, leave empty for create mode --}}

@php
    $isEditMode = isset($equipo);
    $modalId = $isEditMode ? 'editTeamModal' : 'createTeamModal';
    $modalTitle = $isEditMode ? 'Editar Equipo' : 'Crear Nuevo Equipo';
    $formAction = $isEditMode ? route('equipos.update', $equipo->id) : route('equipos.store');
    $formMethod = $isEditMode ? 'PUT' : 'POST';
@endphp

<!-- Modal Principal - Crear/Editar Equipo con Tabs -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="fas fa-users"></i> {{ $modalTitle }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Progress Bar -->
                <div class="progress mb-4" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" 
                         id="progressBar-{{ $modalId }}" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        Paso 1 de 5
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="teamTabs-{{ $modalId }}" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-ubicacion-{{ $modalId }}" data-toggle="tab" href="#step-ubicacion-{{ $modalId }}" role="tab">
                            <i class="fas fa-map-marker-alt"></i> 1. Ubicación
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-configurar-{{ $modalId }}" data-toggle="tab" href="#step-configurar-{{ $modalId }}" role="tab">
                            <i class="fas fa-cog"></i> 2. Configurar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-lider-{{ $modalId }}" data-toggle="tab" href="#step-lider-{{ $modalId }}" role="tab">
                            <i class="fas fa-user-tie"></i> 3. Líder
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-mochila-{{ $modalId }}" data-toggle="tab" href="#step-mochila-{{ $modalId }}" role="tab">
                            <i class="fas fa-backpack"></i> 4. Mochila
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-comunarios-{{ $modalId }}" data-toggle="tab" href="#step-comunarios-{{ $modalId }}" role="tab">
                            <i class="fas fa-users"></i> 5. Comunarios
                        </a>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content mt-3" id="teamTabsContent-{{ $modalId }}">
                    <!-- PASO 1: Seleccionar Ubicación -->
                    <div class="tab-pane fade show active" id="step-ubicacion-{{ $modalId }}" role="tabpanel">
                        <h4 class="mb-3"><i class="fas fa-map-marker-alt text-primary"></i> Seleccionar Ubicación del Equipo</h4>
                        <p class="text-muted">Haga clic en el mapa para establecer la ubicación del equipo. Los marcadores rojos indican reportes rápidos de incendios.</p>
                        
                        <div class="form-group">
                            <div id="map-{{ $modalId }}" style="height: 450px; border-radius: 0.25rem; border: 2px solid #007bff;"></div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Haga clic en el mapa para colocar el marcador de ubicación del equipo
                                </small>
                                <button type="button" id="btn-remove-marker-{{ $modalId }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar Marcador
                                </button>
                            </div>
                            <!-- Campos ocultos para las coordenadas -->
                            <input type="hidden" id="latitud-{{ $modalId }}" name="latitud" value="{{ $isEditMode ? ($equipo->latitud ?? '') : '' }}">
                            <input type="hidden" id="longitud-{{ $modalId }}" name="longitud" value="{{ $isEditMode ? ($equipo->longitud ?? '') : '' }}">
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-fire text-danger"></i> <strong>Incendios Reportados:</strong> Los marcadores rojos en el mapa representan reportes rápidos de incendios activos.
                        </div>
                    </div>

                    <!-- PASO 2: Configurar Equipo -->
                    <div class="tab-pane fade" id="step-configurar-{{ $modalId }}" role="tabpanel">
                        <h4 class="mb-3"><i class="fas fa-cog text-primary"></i> Configurar Equipo</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_equipo-{{ $modalId }}">
                                        Nombre del Equipo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nombre_equipo-{{ $modalId }}" name="nombre_equipo" 
                                           placeholder="Ej: Equipo Alpha" value="{{ $isEditMode ? $equipo->nombre_equipo : '' }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado_id-{{ $modalId }}">
                                        Estado del Equipo <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="estado_id-{{ $modalId }}" name="estado_id" required>
                                        <option value="">Seleccione un estado</option>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}" {{ $isEditMode && $equipo->estado_id == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fas fa-user-plus"></i> Agregar Miembros</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search-member-{{ $modalId }}">
                                        <i class="fas fa-search"></i> Buscar Miembros
                                    </label>
                                    <input type="text" class="form-control" id="search-member-{{ $modalId }}" 
                                           placeholder="Buscar por nombre...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter-entidad-{{ $modalId }}">
                                        <i class="fas fa-building"></i> Filtrar por Entidad
                                    </label>
                                    <select class="form-control" id="filter-entidad-{{ $modalId }}">
                                        <option value="">Todas las entidades</option>
                                        <option value="bomberos">Bomberos</option>
                                        <option value="policia">Policía</option>
                                        <option value="defensa_civil">Defensa Civil</option>
                                        <option value="voluntarios">Voluntarios</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter-nivel-{{ $modalId }}">
                                        <i class="fas fa-layer-group"></i> Filtrar por Nivel
                                    </label>
                                    <select class="form-control" id="filter-nivel-{{ $modalId }}">
                                        <option value="">Todos los niveles</option>
                                        <option value="basico">Básico</option>
                                        <option value="intermedio">Intermedio</option>
                                        <option value="avanzado">Avanzado</option>
                                        <option value="experto">Experto</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-users"></i> Usuarios Disponibles
                                    <span class="badge badge-primary float-right" id="selected-count-{{ $modalId }}">0 seleccionados</span>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-hover table-sm mb-0" id="members-table-{{ $modalId }}">
                                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="select-all-members-{{ $modalId }}">
                                                </th>
                                                <th>Nombre</th>
                                                <th>Entidad</th>
                                                <th>Nivel</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="members-tbody-{{ $modalId }}">
                                            <!-- Datos de ejemplo - estos vendrían de la base de datos -->
                                            <tr class="member-row" data-entidad="bomberos" data-nivel="avanzado">
                                                <td><input type="checkbox" class="member-checkbox" value="1"></td>
                                                <td>Juan Pérez García</td>
                                                <td><span class="badge badge-danger">Bomberos</span></td>
                                                <td><span class="badge badge-success">Avanzado</span></td>
                                                <td><span class="badge badge-success">Disponible</span></td>
                                            </tr>
                                            <tr class="member-row" data-entidad="policia" data-nivel="intermedio">
                                                <td><input type="checkbox" class="member-checkbox" value="2"></td>
                                                <td>María López Fernández</td>
                                                <td><span class="badge badge-primary">Policía</span></td>
                                                <td><span class="badge badge-info">Intermedio</span></td>
                                                <td><span class="badge badge-success">Disponible</span></td>
                                            </tr>
                                            <tr class="member-row" data-entidad="defensa_civil" data-nivel="experto">
                                                <td><input type="checkbox" class="member-checkbox" value="3"></td>
                                                <td>Carlos Rodríguez Sánchez</td>
                                                <td><span class="badge badge-warning">Defensa Civil</span></td>
                                                <td><span class="badge badge-danger">Experto</span></td>
                                                <td><span class="badge badge-success">Disponible</span></td>
                                            </tr>
                                            <tr class="member-row" data-entidad="voluntarios" data-nivel="basico">
                                                <td><input type="checkbox" class="member-checkbox" value="4"></td>
                                                <td>Ana Martínez Torres</td>
                                                <td><span class="badge badge-secondary">Voluntarios</span></td>
                                                <td><span class="badge badge-secondary">Básico</span></td>
                                                <td><span class="badge badge-success">Disponible</span></td>
                                            </tr>
                                            <tr class="member-row" data-entidad="bomberos" data-nivel="intermedio">
                                                <td><input type="checkbox" class="member-checkbox" value="5"></td>
                                                <td>Pedro González Ramírez</td>
                                                <td><span class="badge badge-danger">Bomberos</span></td>
                                                <td><span class="badge badge-info">Intermedio</span></td>
                                                <td><span class="badge badge-success">Disponible</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Seleccione los miembros que formarán parte del equipo. Puede usar los filtros para encontrar usuarios específicos.
                        </div>
                    </div>

                    <!-- PASO 3: Seleccionar Líder -->
                    <div class="tab-pane fade" id="step-lider-{{ $modalId }}" role="tabpanel">
                        <h4 class="mb-3"><i class="fas fa-user-tie text-primary"></i> Seleccionar Líder del Equipo</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h5 class="card-title"><i class="fas fa-clipboard-list"></i> Resumen del Equipo Actual</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold"><i class="fas fa-tag text-primary"></i> Nombre:</td>
                                                    <td id="resumen-nombre-{{ $modalId }}">{{ $isEditMode ? $equipo->nombre_equipo : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold"><i class="fas fa-map-marker-alt text-danger"></i> Ubicación:</td>
                                                    <td id="resumen-ubicacion-{{ $modalId }}">{{ $isEditMode && $equipo->latitud ? $equipo->latitud.', '.$equipo->longitud : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold"><i class="fas fa-info-circle text-success"></i> Estado:</td>
                                                    <td id="resumen-estado-{{ $modalId }}">{{ $isEditMode && $equipo->estados_sistema ? $equipo->estados_sistema->nombre : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold"><i class="fas fa-users text-info"></i> Miembros:</td>
                                                    <td id="resumen-miembros-{{ $modalId }}">{{ $isEditMode ? $equipo->cantidad_integrantes : 0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h5 class="card-title"><i class="fas fa-crown"></i> Seleccionar Líder del Equipo</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="lider-equipo-{{ $modalId }}">
                                                Líder <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" id="lider-equipo-{{ $modalId }}" name="lider_id" required>
                                                <option value="">Seleccione un líder</option>
                                            </select>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Seleccione un miembro del equipo para ser el líder
                                            </small>
                                        </div>

                                        <div id="lider-info-{{ $modalId }}" class="alert alert-success d-none mt-3">
                                            <h6 class="alert-heading"><i class="fas fa-user-check"></i> Líder Seleccionado</h6>
                                            <hr>
                                            <p class="mb-0" id="lider-nombre-display-{{ $modalId }}"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list"></i> Lista de Miembros del Equipo</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Entidad</th>
                                                <th>Nivel</th>
                                                <th>Rol</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selected-members-list-{{ $modalId }}">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    <i class="fas fa-info-circle"></i> No hay miembros seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 4: Mochila (Opcional) -->
                    <div class="tab-pane fade" id="step-mochila-{{ $modalId }}" role="tabpanel">
                        <h4 class="mb-3"><i class="fas fa-backpack text-primary"></i> Mochila de Equipos</h4>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Asigna suministros al equipo según su disponibilidad</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Nota:</strong> Esta sección es opcional y está en desarrollo. Los suministros pueden asignarse posteriormente.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 5: Agregar Comunarios Locales -->
                    <div class="tab-pane fade" id="step-comunarios-{{ $modalId }}" role="tabpanel">
                        <h4 class="mb-3"><i class="fas fa-users text-primary"></i> Agregar Comunarios Locales</h4>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Opcional:</strong> Agregue comunarios locales que apoyarán al equipo en la zona.
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Nota:</strong> Esta sección es opcional y está en desarrollo.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn-prev-{{ $modalId }}" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn-next-{{ $modalId }}">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="btn btn-success" id="btn-save-{{ $modalId }}" style="display: none;">
                    <i class="fas fa-save"></i> {{ $isEditMode ? 'Actualizar' : 'Crear' }} Equipo
                </button>
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .nav-tabs .nav-link.disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
    .nav-tabs .nav-link {
        cursor: pointer;
    }
    .member-row {
        cursor: pointer;
    }
    .member-row:hover {
        background-color: #f8f9fa;
    }
    .table thead.thead-light {
        background-color: #f8f9fa;
    }
    [id^="map-"] {
        z-index: 1;
    }
    .modal {
        z-index: 1050;
    }
    .modal-backdrop {
        z-index: 1040;
    }
</style>
@endpush

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
    const modalId = '{{ $modalId }}';
    const isEditMode = {{ $isEditMode ? 'true' : 'false' }};
    
    let map, marker;
    let currentStep = 1;
    let selectedMembers = [];

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize when modal is shown
        $('#' + modalId).on('shown.bs.modal', function () {
            if (!map) {
                initializeMap();
            }
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        });

        // Reset on modal hide
        $('#' + modalId).on('hidden.bs.modal', function () {
            resetForm();
        });

        initializeEventListeners();
    });

    function initializeMap() {
        const defaultLat = {{ ($isEditMode && isset($equipo) && $equipo->latitud) ? $equipo->latitud : -16.5000 }};
        const defaultLng = {{ ($isEditMode && isset($equipo) && $equipo->longitud) ? $equipo->longitud : -64.5000 }};
        const defaultZoom = {{ ($isEditMode && isset($equipo) && $equipo->latitud) ? 13 : 6 }};

        map = L.map('map-' + modalId).setView([defaultLat, defaultLng], defaultZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add existing marker if in edit mode
        @if($isEditMode && isset($equipo) && $equipo->latitud && $equipo->longitud)
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
        @endif

        // Click event on map
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }

            updateCoordinates(lat, lng);
        });

        // Remove marker button
        document.getElementById('btn-remove-marker-' + modalId).addEventListener('click', function() {
            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }
            document.getElementById('latitud-' + modalId).value = '';
            document.getElementById('longitud-' + modalId).value = '';
        });
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitud-' + modalId).value = lat.toFixed(6);
        document.getElementById('longitud-' + modalId).value = lng.toFixed(6);
        document.getElementById('resumen-ubicacion-' + modalId).textContent = lat.toFixed(4) + ', ' + lng.toFixed(4);
    }

    function initializeEventListeners() {
        // Navigation
        document.getElementById('btn-next-' + modalId).addEventListener('click', nextStep);
        document.getElementById('btn-prev-' + modalId).addEventListener('click', prevStep);
        document.getElementById('btn-save-' + modalId).addEventListener('click', saveTeam);

        // Member selection
        document.querySelectorAll('#members-tbody-' + modalId + ' .member-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedMembers);
        });

        // Update resumen when name or state changes
        document.getElementById('nombre_equipo-' + modalId).addEventListener('input', updateResumen);
        document.getElementById('estado_id-' + modalId).addEventListener('change', updateResumen);
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            updateStep();
        }
    }

    function prevStep() {
        currentStep--;
        updateStep();
    }

    function updateStep() {
        const tabs = ['ubicacion', 'configurar', 'lider', 'mochila', 'comunarios'];
        tabs.forEach((tab, index) => {
            const tabElement = document.getElementById('tab-' + tab + '-' + modalId);
            const stepElement = document.getElementById('step-' + tab + '-' + modalId);
            
            if (index + 1 === currentStep) {
                tabElement.classList.add('active');
                tabElement.classList.remove('disabled');
                stepElement.classList.add('show', 'active');
            } else if (index + 1 < currentStep) {
                tabElement.classList.remove('active', 'disabled');
                stepElement.classList.remove('show', 'active');
            } else {
                tabElement.classList.remove('active');
                tabElement.classList.add('disabled');
                stepElement.classList.remove('show', 'active');
            }
        });

        // Update progress bar
        const progress = (currentStep / 5) * 100;
        const progressBar = document.getElementById('progressBar-' + modalId);
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
        progressBar.textContent = 'Paso ' + currentStep + ' de 5';

        // Update buttons
        document.getElementById('btn-prev-' + modalId).style.display = currentStep > 1 ? 'inline-block' : 'none';
        document.getElementById('btn-next-' + modalId).style.display = currentStep < 5 ? 'inline-block' : 'none';
        document.getElementById('btn-save-' + modalId).style.display = currentStep === 5 ? 'inline-block' : 'none';

        // Update resumen in step 3
        if (currentStep === 3) {
            updateResumen();
            updateLeaderSelect();
        }
    }

    function validateStep(step) {
        switch(step) {
            case 2:
                const nombre = document.getElementById('nombre_equipo-' + modalId).value.trim();
                const estado = document.getElementById('estado_id-' + modalId).value;
                
                if (!nombre) {
                    alert('Por favor, ingrese el nombre del equipo');
                    return false;
                }
                if (!estado) {
                    alert('Por favor, seleccione el estado del equipo');
                    return false;
                }
                return true;
            default:
                return true;
        }
    }

    function updateSelectedMembers() {
        selectedMembers = [];
        document.querySelectorAll('#members-tbody-' + modalId + ' .member-checkbox:checked').forEach(checkbox => {
            const row = checkbox.closest('tr');
            selectedMembers.push({
                id: checkbox.value,
                nombre: row.cells[1].textContent,
                entidad: row.cells[2].textContent,
                nivel: row.cells[3].textContent
            });
        });

        document.getElementById('selected-count-' + modalId).textContent = selectedMembers.length + ' seleccionados';
        document.getElementById('resumen-miembros-' + modalId).textContent = selectedMembers.length;
    }

    function updateResumen() {
        const nombre = document.getElementById('nombre_equipo-' + modalId).value || '-';
        const estadoSelect = document.getElementById('estado_id-' + modalId);
        const estado = estadoSelect.options[estadoSelect.selectedIndex]?.text || '-';

        document.getElementById('resumen-nombre-' + modalId).textContent = nombre;
        document.getElementById('resumen-estado-' + modalId).textContent = estado;
    }

    function updateLeaderSelect() {
        const liderSelect = document.getElementById('lider-equipo-' + modalId);
        liderSelect.innerHTML = '<option value="">Seleccione un líder</option>';

        selectedMembers.forEach(member => {
            const option = document.createElement('option');
            option.value = member.id;
            option.textContent = member.nombre;
            liderSelect.appendChild(option);
        });
    }

    function saveTeam() {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ $formAction }}';

        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        @if($isEditMode)
        // Method spoofing for PUT
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        @endif

        // Add form data
        const fields = ['nombre_equipo', 'estado_id', 'latitud', 'longitud'];
        fields.forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = field.replace('-' + modalId, '');
            input.value = document.getElementById(field + '-' + modalId).value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    function resetForm() {
        currentStep = 1;
        selectedMembers = [];
        updateStep();
    }
})();
</script>
@endpush
