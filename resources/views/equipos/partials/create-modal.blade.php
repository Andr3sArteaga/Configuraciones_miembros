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
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
            <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                <!-- Alert para errores de validación -->
                @if ($errors->any() && !$isEditMode)
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h6><i class="icon fas fa-ban"></i> Error en la validación</h6>
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Progress Bar -->
                <div class="progress mb-4" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                        id="progressBar-{{ $modalId }}" style="width: 20%;" aria-valuenow="20" aria-valuemin="0"
                        aria-valuemax="100">
                        Paso 1 de 5
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="teamTabs-{{ $modalId }}" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-ubicacion-{{ $modalId }}" data-toggle="tab"
                            href="#step-ubicacion-{{ $modalId }}" role="tab">
                            <i class="fas fa-map-marker-alt"></i> 1. Seleccionar Reporte
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-configurar-{{ $modalId }}" data-toggle="tab"
                            href="#step-configurar-{{ $modalId }}" role="tab">
                            <i class="fas fa-cog"></i> 2. Configurar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-lider-{{ $modalId }}" data-toggle="tab"
                            href="#step-lider-{{ $modalId }}" role="tab">
                            <i class="fas fa-user-tie"></i> 3. Líder
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-mochila-{{ $modalId }}" data-toggle="tab"
                            href="#step-mochila-{{ $modalId }}" role="tab">
                            <i class="fas fa-backpack"></i> 4. Mochila
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" id="tab-comunarios-{{ $modalId }}" data-toggle="tab"
                            href="#step-comunarios-{{ $modalId }}" role="tab">
                            <i class="fas fa-users"></i> 5. Comunarios
                        </a>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content mt-3" id="teamTabsContent-{{ $modalId }}">
                    <!-- PASO 1: Seleccionar Ubicación -->
                    <div class="tab-pane fade show active" id="step-ubicacion-{{ $modalId }}" role="tabpanel">
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt text-primary"></i> Seleccionar Reporte de Incendio</h5>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Seleccione un reporte haciendo clic en un marcador del mapa o en la lista lateral.
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div id="map-{{ $modalId }}"
                                    style="height: 300px; border-radius: 0.25rem; border: 2px solid #007bff;"></div>
                                <input type="hidden" id="reporte_id-{{ $modalId }}" name="reporte_id"
                                    value="{{ $isEditMode ? $equipo->reporte_id ?? '' : '' }}">
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-list"></i> Reportes Disponibles</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @forelse($reportes ?? [] as $r)
                                                <li class="list-group-item list-group-item-action p-2">
                                                    <a href="javascript:void(0);" class="select-reporte d-block text-decoration-none"
                                                        data-id="{{ $r->id }}">
                                                        <strong class="d-block">{{ $r->nombre_lugar ?? 'Reporte' }}</strong>
                                                        <small class="text-muted">
                                                            {{ $r->fecha_hora?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                                                        </small>
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-center text-muted">
                                                    No hay reportes disponibles
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" id="btn-remove-marker-{{ $modalId }}"
                                    class="btn btn-danger btn-sm btn-block mt-2">
                                    <i class="fas fa-trash"></i> Deseleccionar
                                </button>
                            </div>
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
                                <input type="text" class="form-control" id="nombre_equipo-{{ $modalId }}"
                                    name="nombre_equipo" placeholder="Ej: Equipo Alpha"
                                    value="{{ $isEditMode ? $equipo->nombre_equipo : '' }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado_id-{{ $modalId }}">
                                    Estado del Equipo <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="estado_id-{{ $modalId }}" name="estado_id"
                                    required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ $isEditMode && $equipo->estado_id == $estado->id ? 'selected' : '' }}>
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
                                <span class="badge badge-primary float-right"
                                    id="selected-count-{{ $modalId }}">0 seleccionados</span>
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-hover table-sm mb-0"
                                    id="members-table-{{ $modalId }}">
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
                                        @forelse($usuarios as $usuario)
                                            @php
                                                $isChecked =
                                                    $isEditMode &&
                                                    isset($miembrosAsignados) &&
                                                    in_array($usuario->id, $miembrosAsignados);
                                            @endphp
                                            <tr class="member-row"
                                                data-entidad="{{ strtolower($usuario->entidad_perteneciente ?? '') }}"
                                                data-nivel="{{ strtolower($usuario->niveles_entrenamiento->nivel ?? '') }}">
                                                <td><input type="checkbox" class="member-checkbox"
                                                        value="{{ $usuario->id }}" name="miembros[]"
                                                        {{ $isChecked ? 'checked' : '' }}></td>
                                                <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                                                <td>
                                                    @if ($usuario->entidad_perteneciente)
                                                        <span
                                                            class="badge badge-info">{{ $usuario->entidad_perteneciente }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">Sin entidad</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($usuario->niveles_entrenamiento)
                                                        <span
                                                            class="badge badge-success">{{ $usuario->niveles_entrenamiento->nivel }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($usuario->estados_sistema)
                                                        <span class="badge"
                                                            style="background-color: {{ $usuario->estados_sistema->color ?? '#6c757d' }}; color: white;">
                                                            {{ $usuario->estados_sistema->nombre }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    <i class="fas fa-info-circle"></i> No hay usuarios disponibles
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Seleccione los miembros que
                        formarán parte del equipo. Puede usar los filtros para encontrar usuarios específicos.
                    </div>
                </div>

                <!-- PASO 3: Seleccionar Líder -->
                <div class="tab-pane fade" id="step-lider-{{ $modalId }}" role="tabpanel">
                    <h4 class="mb-3"><i class="fas fa-user-tie text-primary"></i> Seleccionar Líder del Equipo
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-clipboard-list"></i> Resumen del
                                        Equipo Actual</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold"><i class="fas fa-tag text-primary"></i>
                                                    Nombre:</td>
                                                <td id="resumen-nombre-{{ $modalId }}">
                                                    {{ $isEditMode ? $equipo->nombre_equipo : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold"><i
                                                        class="fas fa-map-marker-alt text-danger"></i> Ubicación:
                                                </td>
                                                <td id="resumen-ubicacion-{{ $modalId }}">
                                                    {{ $isEditMode && $equipo->latitud ? $equipo->latitud . ', ' . $equipo->longitud : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold"><i
                                                        class="fas fa-info-circle text-success"></i> Estado:</td>
                                                <td id="resumen-estado-{{ $modalId }}">
                                                    {{ $isEditMode && $equipo->estados_sistema ? $equipo->estados_sistema->nombre : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold"><i class="fas fa-users text-info"></i>
                                                    Miembros:</td>
                                                <td id="resumen-miembros-{{ $modalId }}">
                                                    {{ $isEditMode ? $equipo->cantidad_integrantes : 0 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-crown"></i> Seleccionar Líder del
                                        Equipo</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="lider-equipo-{{ $modalId }}">
                                            Líder <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="lider-equipo-{{ $modalId }}"
                                            name="lider_id" required>
                                            <option value="">Seleccione un líder</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Seleccione un miembro del equipo
                                            para ser el líder
                                        </small>
                                    </div>

                                    <div id="lider-info-{{ $modalId }}" class="alert alert-success d-none mt-3">
                                        <h6 class="alert-heading"><i class="fas fa-user-check"></i> Líder
                                            Seleccionado</h6>
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
                        <i class="fas fa-info-circle"></i> <strong>Asigna suministros al equipo según su
                            disponibilidad</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Nota:</strong> Esta sección es
                                opcional y está en desarrollo. Los suministros pueden asignarse posteriormente.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PASO 5: Agregar Comunarios Locales -->
                <div class="tab-pane fade" id="step-comunarios-{{ $modalId }}" role="tabpanel">
                    <h4 class="mb-3"><i class="fas fa-users text-primary"></i> Agregar Comunarios Locales</h4>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Opcional:</strong> Agregue comunarios locales
                        que apoyarán al equipo en la zona.
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Nuevo Comunario</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nombre_comunario-{{ $modalId }}">Nombre Completo</label>
                                        <input type="text" class="form-control"
                                            id="nombre_comunario-{{ $modalId }}"
                                            placeholder="Nombre del comunario">
                                    </div>
                                    <div class="form-group">
                                        <label for="edad_comunario-{{ $modalId }}">Edad</label>
                                        <input type="number" class="form-control"
                                            id="edad_comunario-{{ $modalId }}" placeholder="Edad"
                                            min="18" max="100">
                                    </div>
                                    <button type="button" class="btn btn-success btn-block"
                                        id="btn-add-comunario-{{ $modalId }}">
                                        <i class="fas fa-plus"></i> Agregar Comunario
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Comunarios Agregados</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Edad</th>
                                                    <th style="width: 50px;">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="comunarios-list-{{ $modalId }}">
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-3">
                                                        No hay comunarios agregados
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btn-prev-{{ $modalId }}"
                style="display: none;">
                <i class="fas fa-arrow-left"></i> Anterior
            </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="btn btn-primary" id="btn-next-{{ $modalId }}">
                Siguiente <i class="fas fa-arrow-right"></i>
            </button>
            <button type="button" class="btn btn-success" id="btn-save-{{ $modalId }}"
                style="display: none;">
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

        .select-reporte {
            cursor: pointer;
            color: inherit;
        }

        .select-reporte:hover {
            color: #007bff;
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
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
            let comunarios = [];

            const assignedLeaderId = @json(isset($liderAsignadoId) ? $liderAsignadoId : null);

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize when modal is shown
                $('#' + modalId).on('shown.bs.modal', function() {
                    if (!map) {
                        initializeMap();
                    }
                    // Populate selected members from any pre-checked checkboxes (edit mode)
                    populateSelectedMembersFromChecked();
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);
                });

                // Reset on modal hide
                $('#' + modalId).on('hidden.bs.modal', function() {
                    resetForm();
                });

                initializeEventListeners();

                // Show modal if there are validation errors
                @if ($errors->any() && !$isEditMode)
                    $('#' + modalId).modal('show');
                @endif
            });

            function initializeMap() {
                const defaultLat =
                    {{ $isEditMode && isset($equipo) && $equipo->latitud ? $equipo->latitud : -16.5 }};
                const defaultLng =
                    {{ $isEditMode && isset($equipo) && $equipo->longitud ? $equipo->longitud : -64.5 }};
                const defaultZoom = {{ $isEditMode && isset($equipo) && $equipo->latitud ? 13 : 6 }};

                map = L.map('map-' + modalId).setView([defaultLat, defaultLng], defaultZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Add existing marker if in edit mode
                @if ($isEditMode && isset($equipo) && $equipo->latitud && $equipo->longitud)
                    marker = L.marker([defaultLat, defaultLng], {
                        draggable: true
                    }).addTo(map);
                    marker.on('dragend', function(e) {
                        const position = marker.getLatLng();
                        updateCoordinates(position.lat, position.lng);
                    });
                @endif

                // Mostrar reportes como marcadores y dar opción de selección
                const reportesData = @json(isset($reportes) ? $reportes : []);
                const reporteMarkers = {};
                let selectedReporteId = document.getElementById('reporte_id-' + modalId).value || null;

                reportesData.forEach(function(reporte) {
                    if (!reporte.ubicacion || !reporte.ubicacion.coordinates) return;

                    const lng = reporte.ubicacion.coordinates[0];
                    const lat = reporte.ubicacion.coordinates[1];

                    const m = L.circleMarker([lat, lng], {
                        radius: 7,
                        color: '#dc3545',
                        fillColor: '#dc3545',
                        fillOpacity: 0.9
                    }).addTo(map);
                    m.bindPopup(
                        `<strong>${reporte.nombre_lugar ?? 'Reporte'}</strong><br/><small>${reporte.nombre_reportante ?? ''}</small>`
                    );
                    m.reporteId = reporte.id;
                    reporteMarkers[reporte.id] = m;

                    m.on('click', function() {
                        // Seleccionar reporte
                        selectedReporteId = this.reporteId;
                        document.getElementById('reporte_id-' + modalId).value = selectedReporteId;
                        // Resaltar marcador seleccionado (simple bounce)
                        m.openPopup();
                        // Update resumen with report info
                        document.getElementById('resumen-ubicacion-' + modalId).textContent = (reporte
                                .nombre_lugar ?? '-') + ' (' + lat.toFixed(4) + ', ' + lng.toFixed(4) +
                            ')';
                    });
                });

                // Listener para seleccionar reporte desde la lista lateral
                document.querySelectorAll('#' + modalId + ' .select-reporte').forEach(function(anchor) {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        if (reporteMarkers[id]) {
                            const m = reporteMarkers[id];
                            m.fire('click');
                            map.setView(m.getLatLng(), 13);
                        }
                    });
                });

                // Si estamos en modo edición y ya hay reporte asignado, abrir popup
                if (isEditMode && selectedReporteId && reporteMarkers[selectedReporteId]) {
                    const m = reporteMarkers[selectedReporteId];
                    m.openPopup();
                    map.setView(m.getLatLng(), 13);
                }

                // Remover selección de reporte
                document.getElementById('btn-remove-marker-' + modalId).addEventListener('click', function() {
                    document.getElementById('reporte_id-' + modalId).value = '';
                    selectedReporteId = null;
                    document.getElementById('resumen-ubicacion-' + modalId).textContent = '-';
                });
            }

            function populateSelectedMembersFromChecked() {
                selectedMembers = [];
                document.querySelectorAll('#members-tbody-' + modalId + ' .member-checkbox:checked').forEach(
                    checkbox => {
                        const row = checkbox.closest('tr');
                        selectedMembers.push({
                            id: checkbox.value,
                            nombre: row.cells[1].textContent.trim(),
                            entidad: row.cells[2].textContent.trim(),
                            nivel: row.cells[3].textContent.trim()
                        });
                    });

                document.getElementById('selected-count-' + modalId).textContent = selectedMembers.length +
                    ' seleccionados';
                document.getElementById('resumen-miembros-' + modalId).textContent = selectedMembers.length;
                updateSelectedMembersList();
                updateLeaderSelect();

                // If we have an assigned leader id (edit mode), preselect it
                if (assignedLeaderId) {
                    const liderSelect = document.getElementById('lider-equipo-' + modalId);
                    // Try to set the value if the option exists
                    const optionExists = Array.from(liderSelect.options).some(opt => opt.value === assignedLeaderId);
                    if (optionExists) {
                        liderSelect.value = assignedLeaderId;
                        // Show leader info box
                        const liderInfo = document.getElementById('lider-info-' + modalId);
                        const liderNombre = document.getElementById('lider-nombre-display-' + modalId);
                        const selectedOption = liderSelect.options[liderSelect.selectedIndex];
                        if (selectedOption) {
                            liderNombre.textContent = selectedOption.text;
                            liderInfo.classList.remove('d-none');
                        }
                    }
                }
            }

            function updateCoordinates(lat, lng) {
                document.getElementById('latitud-' + modalId).value = lat.toFixed(6);
                document.getElementById('longitud-' + modalId).value = lng.toFixed(6);
                document.getElementById('resumen-ubicacion-' + modalId).textContent = lat.toFixed(4) + ', ' + lng
                    .toFixed(4);
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

                // Search and filter functionality
                const searchInput = document.getElementById('search-member-' + modalId);
                const filterEntidad = document.getElementById('filter-entidad-' + modalId);
                const filterNivel = document.getElementById('filter-nivel-' + modalId);

                if (searchInput) {
                    searchInput.addEventListener('input', filterMembers);
                }
                if (filterEntidad) {
                    filterEntidad.addEventListener('change', filterMembers);
                }
                if (filterNivel) {
                    filterNivel.addEventListener('change', filterMembers);
                }

                // Select all functionality
                const selectAllCheckbox = document.getElementById('select-all-members-' + modalId);
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        const visibleCheckboxes = document.querySelectorAll('#members-tbody-' + modalId +
                            ' .member-row:not([style*="display: none"]) .member-checkbox');
                        visibleCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateSelectedMembers();
                    });
                }

                // Leader selection change
                const liderSelect = document.getElementById('lider-equipo-' + modalId);
                if (liderSelect) {
                    liderSelect.addEventListener('change', function() {
                        const liderInfo = document.getElementById('lider-info-' + modalId);
                        const liderNombre = document.getElementById('lider-nombre-display-' + modalId);

                        if (this.value) {
                            const selectedOption = this.options[this.selectedIndex];
                            liderNombre.textContent = selectedOption.text;
                            liderInfo.classList.remove('d-none');
                        } else {
                            liderInfo.classList.add('d-none');
                        }
                    });
                }

                // Add Comunario
                document.getElementById('btn-add-comunario-' + modalId).addEventListener('click', addComunario);
            }

            function addComunario() {
                const nombreInput = document.getElementById('nombre_comunario-' + modalId);
                const edadInput = document.getElementById('edad_comunario-' + modalId);

                const nombre = nombreInput.value.trim();
                const edad = edadInput.value.trim();

                if (!nombre) {
                    alert('Por favor ingrese el nombre del comunario');
                    return;
                }
                if (!edad) {
                    alert('Por favor ingrese la edad del comunario');
                    return;
                }

                comunarios.push({
                    nombre,
                    edad
                });

                nombreInput.value = '';
                edadInput.value = '';

                renderComunariosList();
            }

            function renderComunariosList() {
                const tbody = document.getElementById('comunarios-list-' + modalId);

                if (comunarios.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                No hay comunarios agregados
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = '';
                comunarios.forEach((comunario, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${comunario.nombre}</td>
                        <td>${comunario.edad}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs btn-delete-comunario" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Add delete event listeners
                document.querySelectorAll('.btn-delete-comunario').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        comunarios.splice(index, 1);
                        renderComunariosList();
                    });
                });
            }

            function filterMembers() {
                const searchTerm = document.getElementById('search-member-' + modalId).value.toLowerCase();
                const entidadFilter = document.getElementById('filter-entidad-' + modalId).value.toLowerCase();
                const nivelFilter = document.getElementById('filter-nivel-' + modalId).value.toLowerCase();

                const rows = document.querySelectorAll('#members-tbody-' + modalId + ' .member-row');

                rows.forEach(row => {
                    const nombre = row.cells[1].textContent.toLowerCase();
                    const entidad = row.getAttribute('data-entidad');
                    const nivel = row.getAttribute('data-nivel');

                    const matchesSearch = nombre.includes(searchTerm);
                    const matchesEntidad = !entidadFilter || entidad.includes(entidadFilter);
                    const matchesNivel = !nivelFilter || nivel.includes(nivelFilter);

                    if (matchesSearch && matchesEntidad && matchesNivel) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
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
                document.getElementById('btn-prev-' + modalId).style.display = currentStep > 1 ? 'inline-block' :
                    'none';
                document.getElementById('btn-next-' + modalId).style.display = currentStep < 5 ? 'inline-block' :
                    'none';
                document.getElementById('btn-save-' + modalId).style.display = currentStep === 5 ? 'inline-block' :
                    'none';

                // Update resumen in step 3
                if (currentStep === 3) {
                    updateResumen();
                    updateLeaderSelect();
                }
            }

            function validateStep(step) {
                switch (step) {
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
                document.querySelectorAll('#members-tbody-' + modalId + ' .member-checkbox:checked').forEach(
                    checkbox => {
                        const row = checkbox.closest('tr');
                        selectedMembers.push({
                            id: checkbox.value,
                            nombre: row.cells[1].textContent.trim(),
                            entidad: row.cells[2].textContent.trim(),
                            nivel: row.cells[3].textContent.trim()
                        });
                    });

                document.getElementById('selected-count-' + modalId).textContent = selectedMembers.length +
                    ' seleccionados';
                document.getElementById('resumen-miembros-' + modalId).textContent = selectedMembers.length;

                // Update the selected members list in step 3
                updateSelectedMembersList();
            }

            function updateSelectedMembersList() {
                const tbody = document.getElementById('selected-members-list-' + modalId);

                if (selectedMembers.length === 0) {
                    tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-info-circle"></i> No hay miembros seleccionados
                    </td>
                </tr>
            `;
                } else {
                    tbody.innerHTML = '';
                    selectedMembers.forEach((member, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${member.nombre}</td>
                    <td>${member.entidad}</td>
                    <td>${member.nivel}</td>
                    <td><span class="badge badge-secondary">Miembro</span></td>
                `;
                        tbody.appendChild(row);
                    });
                }
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
                // Validar campos requeridos
                const nombre = document.getElementById('nombre_equipo-' + modalId).value.trim();
                const estado = document.getElementById('estado_id-' + modalId).value;

                if (!nombre) {
                    alert('Por favor, ingrese el nombre del equipo');
                    return;
                }

                if (!estado) {
                    alert('Por favor, seleccione el estado del equipo');
                    return;
                }

                // Verificar reporte seleccionado
                const reporteId = document.getElementById('reporte_id-' + modalId).value;
                if (!reporteId) {
                    alert('Por favor, seleccione un reporte en el mapa para vincular el equipo.');
                    return;
                }

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

                @if ($isEditMode)
                    // Method spoofing for PUT
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                @endif

                // Add basic form data
                const fields = ['nombre_equipo', 'estado_id', 'reporte_id'];
                fields.forEach(field => {
                    const element = document.getElementById(field + '-' + modalId);
                    if (element && element.value) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = field;
                        input.value = element.value;
                        form.appendChild(input);
                    }
                });

                // Add selected members
                selectedMembers.forEach(member => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'miembros[]';
                    input.value = member.id;
                    form.appendChild(input);
                });

                // Add leader if selected
                // Add leader if selected
                const liderSelect = document.getElementById('lider-equipo-' + modalId);
                if (liderSelect && liderSelect.value) {
                    const liderInput = document.createElement('input');
                    liderInput.type = 'hidden';
                    liderInput.name = 'lider_id';
                    liderInput.value = liderSelect.value;
                    form.appendChild(liderInput);
                }

                // Add comunarios
                comunarios.forEach((comunario, index) => {
                    const nombreInput = document.createElement('input');
                    nombreInput.type = 'hidden';
                    nombreInput.name = `comunarios[${index}][nombre]`;
                    nombreInput.value = comunario.nombre;
                    form.appendChild(nombreInput);

                    const edadInput = document.createElement('input');
                    edadInput.type = 'hidden';
                    edadInput.name = `comunarios[${index}][edad]`;
                    edadInput.value = comunario.edad;
                    form.appendChild(edadInput);
                });

                document.body.appendChild(form);
                form.submit();
            }

            function resetForm() {
                currentStep = 1;
                currentStep = 1;
                selectedMembers = [];
                comunarios = [];
                renderComunariosList();
                updateStep();
            }
        })();
    </script>
@endpush
