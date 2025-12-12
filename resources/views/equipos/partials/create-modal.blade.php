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

                <!-- Form wrapper for team creation/editing -->
                <form id="team-form-{{ $modalId }}" action="{{ $formAction }}" method="POST">
                    @csrf
                    @if ($isEditMode)
                        @method('PUT')
                    @endif

                    {{-- Hidden Inputs for Location --}}
                    <input type="hidden" id="latitud-{{ $modalId }}" name="latitud"
                        value="{{ $isEditMode ? $equipo->latitud : '' }}">
                    <input type="hidden" id="longitud-{{ $modalId }}" name="longitud"
                        value="{{ $isEditMode ? $equipo->longitud : '' }}">


                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            role="progressbar" id="progressBar-{{ $modalId }}" style="width: 16%;"
                            aria-valuenow="16" aria-valuemin="0" aria-valuemax="100">
                            Paso 1 de 6
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="teamTabs-{{ $modalId }}" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-ubicacion-{{ $modalId }}" data-toggle="tab"
                                href="#step-ubicacion-{{ $modalId }}" role="tab">
                                <i class="fas fa-map-marker-alt"></i> 1. Reporte (Opcional)
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
                        <li class="nav-item">
                            <a class="nav-link disabled" id="tab-resumen-{{ $modalId }}" data-toggle="tab"
                                href="#step-resumen-{{ $modalId }}" role="tab">
                                <i class="fas fa-clipboard-check"></i> 6. Resumen
                            </a>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content mt-3" id="teamTabsContent-{{ $modalId }}">
                        <!-- PASO 1: Seleccionar Ubicación -->
                        <div class="tab-pane fade show active" id="step-ubicacion-{{ $modalId }}" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-map-marker-alt text-primary"></i> Vincular Reporte de
                                Incendio (Opcional)</h5>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Opcionalmente puede vincular el equipo a un reporte
                                haciendo clic en un marcador del
                                mapa o en la lista lateral. Puede omitir este paso si desea crear el equipo sin
                                asignarlo a
                                un reporte.
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    @php
                                        $mapLat = -17.8;
                                        $mapLng = -63.1;
                                        $mapZoom = 6;

                                        if ($isEditMode && isset($equipo) && $equipo->latitud && $equipo->longitud) {
                                            $mapLat = $equipo->latitud;
                                            $mapLng = $equipo->longitud;
                                            $mapZoom = 13;
                                        }
                                    @endphp
                                    <x-map.leaflet-map mapId="map-{{ $modalId }}" lat="{{ $mapLat }}"
                                        lng="{{ $mapLng }}" zoom="{{ $mapZoom }}" height="300px"
                                        :draggable="false" />
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
                                                        <a href="javascript:void(0);"
                                                            class="select-reporte d-block text-decoration-none"
                                                            data-id="{{ $r->id }}">
                                                            <strong
                                                                class="d-block">{{ $r->nombre_lugar ?? 'Reporte' }}</strong>
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
                                        <input type="text" class="form-control"
                                            id="nombre_equipo-{{ $modalId }}" name="nombre_equipo"
                                            placeholder="Ej: Equipo Alpha"
                                            value="{{ $isEditMode ? $equipo->nombre_equipo : '' }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estado_id-{{ $modalId }}">
                                            Estado del Equipo <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="estado_id-{{ $modalId }}"
                                            name="estado_id" required>
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
                                        <input type="text" class="form-control"
                                            id="search-member-{{ $modalId }}"
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
                                                        <input type="checkbox"
                                                            id="select-all-members-{{ $modalId }}">
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
                                                            <i class="fas fa-info-circle"></i> No hay usuarios
                                                            disponibles
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
                            <h4 class="mb-3"><i class="fas fa-user-tie text-primary"></i> Seleccionar Líder del
                                Equipo
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
                                                        <td class="font-weight-bold"><i
                                                                class="fas fa-tag text-primary"></i>
                                                            Nombre:</td>
                                                        <td id="resumen-nombre-{{ $modalId }}">
                                                            {{ $isEditMode ? $equipo->nombre_equipo : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold"><i
                                                                class="fas fa-map-marker-alt text-danger"></i>
                                                            Ubicación:
                                                        </td>
                                                        <td id="resumen-ubicacion-{{ $modalId }}">
                                                            {{ $isEditMode && $equipo->latitud ? $equipo->latitud . ', ' . $equipo->longitud : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold"><i
                                                                class="fas fa-info-circle text-success"></i> Estado:
                                                        </td>
                                                        <td id="resumen-estado-{{ $modalId }}">
                                                            {{ $isEditMode && $equipo->estados_sistema ? $equipo->estados_sistema->nombre : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold"><i
                                                                class="fas fa-users text-info"></i>
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

                                            <div id="lider-info-{{ $modalId }}"
                                                class="alert alert-success d-none mt-3">
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
                                                        <i class="fas fa-info-circle"></i> No hay miembros
                                                        seleccionados
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PASO 4: Mochila -->
                        <div class="tab-pane fade" id="step-mochila-{{ $modalId }}" role="tabpanel">
                            <h4 class="mb-3"><i class="fas fa-backpack text-primary"></i> Mochila de Equipos</h4>

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <label class="mr-2">Código de Seguimiento:</label>
                                    <input type="text" class="form-control d-inline-block w-auto bg-light"
                                        id="codigo_seguimiento-{{ $modalId }}" name="codigo_seguimiento"
                                        value="{{ $isEditMode && isset($codigoSeguimiento) ? $codigoSeguimiento : '' }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="fas fa-box"></i> Suministros y Productos
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0"
                                                    id="mochila-table-{{ $modalId }}">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width: 40%;">Artículo</th>
                                                            <th style="width: 15%;" class="text-center">Stock /
                                                                Sugerido</th>
                                                            <th style="width: 25%;" class="text-center">Cantidad</th>
                                                            <th style="width: 20%;" class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="mochila-tbody-{{ $modalId }}">
                                                        <!-- Items populated by JS -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="insumos_necesarios"
                                        id="insumos-necesarios-{{ $modalId }}">
                                </div>
                            </div>
                        </div>

                        <!-- PASO 5: Agregar Comunarios Locales -->
                        <div class="tab-pane fade" id="step-comunarios-{{ $modalId }}" role="tabpanel">
                            <h4 class="mb-3"><i class="fas fa-users text-primary"></i> Agregar Comunarios Locales
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Opcional:</strong> Agregue comunarios
                                locales
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
                                                <label for="nombre_comunario-{{ $modalId }}">Nombre
                                                    Completo</label>
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
                                            <div class="form-group">
                                                <label for="entidad_comunario-{{ $modalId }}">Entidad
                                                    (Opcional)</label>
                                                <input type="text" class="form-control"
                                                    id="entidad_comunario-{{ $modalId }}"
                                                    placeholder="Ej: Junta de Vecinos">
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
                                            <div class="table-responsive"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Edad</th>
                                                            <th>Entidad</th>
                                                            <th style="width: 50px;">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="comunarios-list-{{ $modalId }}">
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">
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
                        <!-- PASO 6: Resumen Final -->
                        <div class="tab-pane fade" id="step-resumen-{{ $modalId }}" role="tabpanel">
                            <h4 class="mb-3"><i class="fas fa-clipboard-check text-primary"></i> Resumen del Equipo
                            </h4>

                            <div class="alert alert-success py-2">
                                <i class="fas fa-check-circle"></i> Verifique los datos antes de guardar.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-primary mb-3">
                                        <div class="card-header py-1">
                                            <h3 class="card-title" style="font-size: 1rem;">Datos Generales</h3>
                                        </div>
                                        <div class="card-body py-2">
                                            <small><strong>Nombre:</strong> <span
                                                    id="final-nombre-{{ $modalId }}">-</span></small><br>
                                            <small><strong>Estado:</strong> <span
                                                    id="final-estado-{{ $modalId }}">-</span></small><br>
                                            <small><strong>Ubicación:</strong> <span
                                                    id="final-ubicacion-{{ $modalId }}">-</span></small><br>
                                            <small><strong>Código:</strong> <span
                                                    id="final-codigo-{{ $modalId }}">-</span></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-outline card-warning mb-3">
                                        <div class="card-header py-1">
                                            <h3 class="card-title" style="font-size: 1rem;">Líder y Miembros</h3>
                                        </div>
                                        <div class="card-body py-2">
                                            <small><strong>Líder:</strong> <span
                                                    id="final-lider-{{ $modalId }}">-</span></small><br>
                                            <small><strong>Miembros:</strong> <span
                                                    id="final-count-miembros-{{ $modalId }}">0</span></small>
                                            <div
                                                style="max-height: 80px; overflow-y: auto; border: 1px solid #eee; margin-top: 5px;">
                                                <ul class="list-unstyled mb-0 pl-1"
                                                    id="final-lista-miembros-{{ $modalId }}"
                                                    style="font-size: 0.85rem;">
                                                    <li>-</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-info mb-3">
                                        <div class="card-header py-1">
                                            <h3 class="card-title" style="font-size: 1rem;">Mochila</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive"
                                                style="max-height: 120px; overflow-y: auto;">
                                                <table class="table table-sm table-striped mb-0"
                                                    style="font-size: 0.85rem;">
                                                    <tbody id="final-lista-mochila-{{ $modalId }}"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-outline card-success mb-3">
                                        <div class="card-header py-1">
                                            <h3 class="card-title" style="font-size: 1rem;">Comunarios</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive"
                                                style="max-height: 120px; overflow-y: auto;">
                                                <table class="table table-sm table-striped mb-0"
                                                    style="font-size: 0.85rem;">
                                                    <tbody id="final-lista-comunarios-{{ $modalId }}"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> <!-- Close form wrapper -->
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

@php
    $initialComunarios = ($isEditMode && isset($equipo))
        ? $equipo->comunarios_apoyos
            ->map(function ($c) {
                return [
                    'nombre' => $c->nombre,
                    'edad' => $c->edad,
                    'entidad' => $c->entidad_perteneciente,
                ];
            })
            ->values()
            ->toArray()
        : [];
@endphp
@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function() {
            const modalId = '{{ $modalId }}';
            const isEditMode = {{ $isEditMode ? 'true' : 'false' }};

            // Variables globals
            let map = null;
            let marker = null;
            let editMarker = null;
            let selectedMarker = null;
            let currentStep = 1;

            // Arrays state
            let selectedMembers = [];
            let comunarios = [];
            const initialComunarios = @json($initialComunarios);

            // Available items
            // Donations (Insumos Mochila base)
            let donations = [{
                    id: 1,
                    name: 'Tanques de Agua (20L)',
                    stock: 50,
                    quantity: 0,
                    icon: 'fa-tint'
                },
                {
                    id: 2,
                    name: 'Extintores',
                    stock: 20,
                    quantity: 0,
                    icon: 'fa-fire-extinguisher'
                },
                {
                    id: 3,
                    name: 'Trajes Protectores',
                    stock: 15,
                    quantity: 0,
                    icon: 'fa-shield-alt'
                },
                {
                    id: 4,
                    name: 'Palas',
                    stock: 30,
                    quantity: 0,
                    icon: 'fa-tools'
                },
                {
                    id: 5,
                    name: 'Botiquines',
                    stock: 10,
                    quantity: 0,
                    icon: 'fa-medkit'
                }
            ];

            // Products list - will be loaded from API
            let products = [];
            let productsLoadingState = 'initial'; // 'initial', 'loading', 'loaded', 'failed'
            let productsLoadNote = '';

            // Fallback products when API fails
            const fallbackProducts = [{
                    id_producto: 999,
                    nombre: 'Agua',
                    descripcion: 'Agua potable',
                    unidad_medida: 'L',
                    stock_total: 0,
                    quantity: 0,
                    suggested: 0
                },
                {
                    id_producto: 998,
                    nombre: 'Extintores',
                    descripcion: 'Extintores adicionales',
                    unidad_medida: 'unidad',
                    stock_total: 0,
                    quantity: 0,
                    suggested: 0
                },
                {
                    id_producto: 997,
                    nombre: 'Botiquines',
                    descripcion: 'Botiquines médicos',
                    unidad_medida: 'unidad',
                    stock_total: 0,
                    quantity: 0,
                    suggested: 0
                }
            ];

            // --- PRODUCT API INTEGRATION ---
            async function loadProductsFromAPI() {
                if (productsLoadingState !== 'initial') {
                    return; // Already loaded or loading
                }

                productsLoadingState = 'loading';
                console.log('Loading products from inventory API...');

                try {
                    const response = await fetch('http://10.26.5.25:8000/api/inventario/por-producto', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        timeout: 5000 // 5 second timeout
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();

                    if (Array.isArray(data) && data.length > 0) {
                        // Transform API data to match our internal structure
                        products = data.map(item => ({
                            id_producto: item.id_producto,
                            nombre: item.nombre || 'Producto sin nombre',
                            descripcion: item.descripcion || '',
                            unidad_medida: item.unidad_medida || 'unidad',
                            stock_total: item.stock_total || 0,
                            quantity: 0, // User selection
                            suggested: Math.min(item.stock_total,
                                5) // Suggest up to 5 or available stock
                        }));

                        productsLoadingState = 'loaded';
                        productsLoadNote = '';
                        console.log(`Loaded ${products.length} products from API`);
                    } else {
                        throw new Error('API returned empty or invalid data');
                    }

                } catch (error) {
                    console.warn('Failed to load products from API:', error.message);

                    // Use fallback products
                    products = [...fallbackProducts];
                    productsLoadingState = 'failed';
                    productsLoadNote =
                        'Nota: No se obtuvieron productos del inventario, mostrando productos de reserva.';
                }

                // Re-render the table with new products
                renderMochilaTable();
            }

            // Helper to generate BRI code
            function generateBRICode() {
                const random = Math.floor(1000 + Math.random() * 9000);
                return `BRI-${random}`;
            }

            const assignedLeaderId = @json(isset($liderAsignadoId) ? $liderAsignadoId : null);

            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, initializing modal:', modalId);

                // Initialize event listeners early
                setTimeout(initializeEventListeners, 100);

                // Optimized: Initialize when modal is shown
                $('#' + modalId).on('shown.bs.modal', function() {
                    console.log('Modal shown:', modalId);

                    // Ensure event listeners are attached
                    initializeEventListeners();

                    // Generate tracking code if empty (different logic for edit vs create)
                    const codeField = document.getElementById('codigo_seguimiento-' + modalId);
                    if (codeField && !codeField.value) {
                        @if ($isEditMode)
                            // In edit mode, load existing code or generate new if none exists
                            const existingCode = @json(isset($codigoSeguimiento) ? $codigoSeguimiento : null);
                            codeField.value = existingCode || generateBRICode();
                        @else
                            // In create mode, always generate new code
                            codeField.value = generateBRICode();
                        @endif
                    }

                    // Initialize map with proper timing - wait for modal to be fully visible
                    setTimeout(function() {
                        console.log('Initializing map after modal is shown');
                        if (!map) {
                            initializeMap();
                        }
                        // Pre-cargar comunarios en modo edición
                        if (initialComunarios.length > 0) {
                            comunarios = JSON.parse(JSON.stringify(initialComunarios));
                        }
                        renderComunariosList();
                        populateSelectedMembersFromChecked();
                        renderMochilaTable();

                        // Fix map rendering after modal is fully shown - multiple attempts for reliability
                        setTimeout(function() {
                            if (map) {
                                console.log('First map invalidation attempt');
                                map.invalidateSize(true);
                                // Additional invalidation for stubborn cases
                                setTimeout(function() {
                                    if (map) {
                                        console.log(
                                            'Second map invalidation attempt'
                                            );
                                        map.invalidateSize(true);
                                    }
                                }, 200);
                            }
                        }, 500); // Increased timing for better reliability
                    }, 300); // Increased initial delay
                });

                // Reset on modal hide
                $('#' + modalId).on('hidden.bs.modal', function() {
                    console.log('Modal hidden, resetting form');
                    resetForm();
                });

                // Show modal if validation errors
                @if ($errors->any() && !$isEditMode)
                    $('#' + modalId).modal('show');
                @endif
            });

            // --- MAP LOGIC (Kept same as before) ---
            function initializeMap() {
                console.log('initializeMap called for modalId:', modalId);
                const mapIdStr = 'map-' + modalId;
                const safeMapId = mapIdStr.replace(/-/g, '_');
                const candidates = [
                    'map_' + safeMapId, 'mapInstance_' + safeMapId, 'map_' + mapIdStr.replace(/-/g, '_'),
                    'mapInstance_' + mapIdStr.replace(/-/g, '_'), 'map_' + mapIdStr, mapIdStr.replace(/-/g, '_'),
                    mapIdStr
                ];
                let mapInstance = null;
                for (const key of candidates) {
                    if (Object.prototype.hasOwnProperty.call(window, key) && window[key]) {
                        console.log('Found map instance with key:', key);
                        mapInstance = window[key];
                        break;
                    }
                }

                if (!mapInstance) {
                    console.log('Map instance not found, attempting to initialize...');
                    const initFuncKey = 'initLeafletMap_' + safeMapId;
                    if (window[initFuncKey] && typeof window[initFuncKey] === 'function') {
                        console.log('Found init function, calling:', initFuncKey);
                        try {
                            window[initFuncKey]();
                            // Try again to get the instance after initialization
                            for (const key of candidates) {
                                if (Object.prototype.hasOwnProperty.call(window, key) && window[key]) {
                                    console.log('Map instance found after init:', key);
                                    mapInstance = window[key];
                                    break;
                                }
                            }
                        } catch (e) {
                            console.error('Error initializing map:', e);
                        }
                    }

                    if (!mapInstance) {
                        console.error(
                            'Leaflet map instance not found. Container may not be visible or map not initialized.');
                        return;
                    }
                }
                map = mapInstance;
                map.off('click'); // Clear previous listeners

                // NEW: Generic Click Listener for Custom Location
                map.on('click', function(e) {
                    // Reset selected report marker if any
                    if (selectedMarker) {
                        selectedMarker.setStyle({
                            radius: 7,
                            color: '#dc3545',
                            fillColor: '#dc3545',
                            fillOpacity: 0.9
                        });
                        selectedMarker = null;
                    }
                    // Remove previous edit/custom marker
                    if (editMarker) {
                        map.removeLayer(editMarker);
                    }

                    // Place new Blue Marker (Custom Location)
                    editMarker = L.marker(e.latlng, {
                        draggable: true
                    }).addTo(map);

                    // Update Inputs
                    updateCoordinates(e.latlng.lat, e.latlng.lng);
                    document.getElementById('reporte_id-' + modalId).value = ''; // Clear report ID
                    document.getElementById('resumen-ubicacion-' + modalId).textContent =
                        'Punto Personalizado (' + e.latlng.lat.toFixed(4) + ', ' + e.latlng.lng.toFixed(4) +
                        ')';
                    setInputValue('ubicacion', 'Ubicación Personalizada');
                    setInputValue('latitud', e.latlng.lat.toFixed(6));
                    setInputValue('longitud', e.latlng.lng.toFixed(6));

                    // Drag event for the new marker
                    editMarker.on('dragend', function(ev) {
                        const position = editMarker.getLatLng();
                        updateCoordinates(position.lat, position.lng);
                        setInputValue('latitud', position.lat.toFixed(6));
                        setInputValue('longitud', position.lng.toFixed(6));
                    });
                });

                setTimeout(function() {
                    try {
                        map.invalidateSize(true);
                    } catch (e) {}
                }, 300);

                @if ($isEditMode && isset($equipo) && $equipo->latitud && $equipo->longitud)
                    editMarker = L.marker([{{ $equipo->latitud }}, {{ $equipo->longitud }}], {
                        draggable: true
                    }).addTo(map);
                    editMarker.on('dragend', function(e) {
                        const position = editMarker.getLatLng();
                        updateCoordinates(position.lat, position.lng);
                    });
                @endif

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

                    m.on('click', function(e) {
                        L.DomEvent.stopPropagation(e); // Prevent map click

                        if (selectedMarker && selectedMarker !== m) {
                            selectedMarker.setStyle({
                                radius: 7,
                                color: '#dc3545',
                                fillColor: '#dc3545',
                                fillOpacity: 0.9
                            });
                        }
                        // Remove custom marker if exists
                        if (editMarker) {
                            map.removeLayer(editMarker);
                            editMarker = null;
                        }

                        selectedReporteId = this.reporteId;
                        selectedMarker = m;
                        document.getElementById('reporte_id-' + modalId).value = selectedReporteId;
                        m.setStyle({
                            radius: 10,
                            color: '#007bff',
                            fillColor: '#007bff',
                            fillOpacity: 0.9
                        });
                        m.openPopup();
                        document.getElementById('resumen-ubicacion-' + modalId).textContent = (reporte
                                .nombre_lugar ?? '-') + ' (' + lat.toFixed(4) + ', ' + lng.toFixed(4) +
                            ')';

                        // Autofill hidden fields if they exist
                        if (reporte.nombre_lugar) setInputValue('ubicacion', reporte.nombre_lugar);
                        if (lat) setInputValue('latitud', lat);
                        if (lng) setInputValue('longitud', lng);
                    });
                });

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

                if (isEditMode && selectedReporteId && reporteMarkers[selectedReporteId]) {
                    const m = reporteMarkers[selectedReporteId];
                    m.openPopup();
                    map.setView(m.getLatLng(), 13);
                }
            }

            function setInputValue(name, value) {
                // Prefer ID selector if it follows the convention
                let el = document.getElementById(name + '-' + modalId);

                // Fallback: scope to the specific form
                if (!el) {
                    const form = document.getElementById('team-form-' + modalId);
                    if (form) {
                        el = form.querySelector(`input[name="${name}"]`);
                    }
                }

                if (el) el.value = value;
            }

            function updateCoordinates(lat, lng) {
                document.getElementById('latitud-' + modalId).value = lat.toFixed(6);
                document.getElementById('longitud-' + modalId).value = lng.toFixed(6);
                document.getElementById('resumen-ubicacion-' + modalId).textContent = lat.toFixed(4) + ', ' + lng
                    .toFixed(4);
            }

            // --- MOCHILA & PRODUCTS LOGIC ---

            // Render main table (merges donations + selected products)
            function renderMochilaTable() {
                const tbody = document.getElementById('mochila-tbody-' + modalId);
                tbody.innerHTML = '';

                // 1. Render Donations (Insumos Base)
                donations.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><i class="fas ${item.icon} text-primary mr-2"></i> ${item.name}</td>
                        <td class="text-center"><span class="badge badge-info">${item.stock}</span></td>
                        <td class="text-center">
                             <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-dec-mochila" type="button" data-type="donation" data-id="${item.id}">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-inc-mochila" type="button" data-type="donation" data-id="${item.id}">+</button>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success">En lista</span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // 2. Render Products Section
                if (productsLoadingState === 'loading') {
                    // Show loading state
                    const divider = document.createElement('tr');
                    divider.innerHTML =
                        '<td colspan="4" class="bg-light font-weight-bold pl-3"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando productos del inventario...</td>';
                    tbody.appendChild(divider);
                } else if (products.length > 0) {
                    // Show divider with note if applicable
                    const divider = document.createElement('tr');
                    let dividerText = 'Productos del Inventario';
                    if (productsLoadNote) {
                        dividerText += ` <small class="text-muted">(${productsLoadNote})</small>`;
                    }
                    divider.innerHTML = `<td colspan="4" class="bg-light font-weight-bold pl-3">${dividerText}</td>`;
                    tbody.appendChild(divider);

                    products.forEach(prod => {
                        const row = document.createElement('tr');
                        const stockBadgeClass = prod.stock_total > 0 ? 'badge-success' : 'badge-warning';
                        const statusBadgeClass = prod.stock_total > 0 ? 'badge-success' : 'badge-warning';
                        const statusText = prod.stock_total > 0 ? 'Disponible' : 'Sin stock';

                        row.innerHTML = `
                            <td>
                                <span class="font-weight-medium">${prod.nombre}</span>
                                ${prod.descripcion ? `<br><small class="text-muted">${prod.descripcion}</small>` : ''}
                            </td>
                            <td class="text-center">
                                <span class="badge ${stockBadgeClass}" title="Stock disponible">${prod.stock_total} ${prod.unidad_medida}</span>
                            </td>
                            <td class="text-center">
                                <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-dec-mochila" type="button" data-type="product" data-id="${prod.id_producto}">-</button>
                                    </div>
                                    <input type="text" class="form-control text-center" value="${prod.quantity}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-inc-mochila" type="button" data-type="product" data-id="${prod.id_producto}">+</button>
                                    </div>
                                </div>
                            </td>
                             <td class="text-center">
                                <span class="badge ${statusBadgeClass}">${statusText}</span>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }

                // Attach events for main table counters
                attachMochilaEvents();
            }

            // Remove unused Modal logic/rendering functions...

            function attachMochilaEvents() {
                // Decrease
                document.querySelectorAll('.btn-dec-mochila').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const type = this.getAttribute('data-type');
                        const id = parseInt(this.getAttribute('data-id'));
                        if (type === 'donation') {
                            const item = donations.find(d => d.id === id);
                            if (item && item.quantity > 0) {
                                item.quantity--;
                                renderMochilaTable();
                            }
                        } else {
                            const item = products.find(p => p.id_producto === id);
                            if (item && item.quantity > 0) {
                                item.quantity--;
                                renderMochilaTable();
                            }
                        }
                    });
                });

                // Increase
                document.querySelectorAll('.btn-inc-mochila').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const type = this.getAttribute('data-type');
                        const id = parseInt(this.getAttribute('data-id'));
                        if (type === 'donation') {
                            const item = donations.find(d => d.id === id);
                            if (item) {
                                item.quantity++;
                                renderMochilaTable();
                            }
                        } else {
                            const item = products.find(p => p.id_producto === id);
                            if (item && item.quantity < item
                                .stock_total) { // Don't exceed available stock
                                item.quantity++;
                                renderMochilaTable();
                            }
                        }
                    });
                });
            }

            // --- GENERAL EVENTS ---
            function initializeEventListeners() {
                // Safe element getter that checks if element exists
                const safeAddEventListener = (id, event, handler) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener(event, handler);
                        console.log('Added event listener to:', id);
                    } else {
                        console.warn('Element not found:', id);
                    }
                };

                // Navigation button event listeners
                safeAddEventListener('btn-next-' + modalId, 'click', nextStep);
                safeAddEventListener('btn-prev-' + modalId, 'click', prevStep);
                safeAddEventListener('btn-save-' + modalId, 'click', saveTeam);

                // Remove marker button
                safeAddEventListener('btn-remove-marker-' + modalId, 'click', function() {
                    document.getElementById('reporte_id-' + modalId).value = '';
                    const resumen = document.getElementById('resumen-ubicacion-' + modalId);
                    if (resumen) resumen.textContent = '-';
                    if (map) map.closePopup();
                    if (selectedMarker) {
                        selectedMarker.setStyle({
                            radius: 7,
                            color: '#dc3545',
                            fillColor: '#dc3545',
                            fillOpacity: 0.9
                        });
                        selectedMarker = null;
                    }
                    if (editMarker) {
                        try {
                            map.removeLayer(editMarker);
                        } catch (e) {}
                        editMarker = null;
                    }
                    // Clear inputs
                    setInputValue('ubicacion', '');
                    setInputValue('latitud', '');
                    setInputValue('longitud', '');
                });

                // Team configuration listeners
                safeAddEventListener('nombre_equipo-' + modalId, 'input', updateResumen);
                safeAddEventListener('estado_id-' + modalId, 'change', updateResumen);

                // Filter listeners
                const search = document.getElementById('search-member-' + modalId);
                const entFilter = document.getElementById('filter-entidad-' + modalId);
                const nivFilter = document.getElementById('filter-nivel-' + modalId);
                if (search) search.addEventListener('input', filterMembers);
                if (entFilter) entFilter.addEventListener('change', filterMembers);
                if (nivFilter) nivFilter.addEventListener('change', filterMembers);

                // Member selection listeners
                document.querySelectorAll('#members-tbody-' + modalId + ' .member-checkbox').forEach(cb => cb
                    .addEventListener('change', updateSelectedMembers));

                // Select all checkbox
                const selectAll = document.getElementById('select-all-members-' + modalId);
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        const visible = document.querySelectorAll('#members-tbody-' + modalId +
                            ' .member-row:not([style*="display: none"]) .member-checkbox');
                        visible.forEach(cb => cb.checked = this.checked);
                        updateSelectedMembers();
                    });
                }

                // Leader selection
                const liderSelect = document.getElementById('lider-equipo-' + modalId);
                if (liderSelect) {
                    liderSelect.addEventListener('change', function() {
                        const info = document.getElementById('lider-info-' + modalId);
                        const txt = document.getElementById('lider-nombre-display-' + modalId);
                        if (this.value) {
                            txt.textContent = this.options[this.selectedIndex].text;
                            info.classList.remove('d-none');
                        } else {
                            info.classList.add('d-none');
                        }
                    });
                }

                // Add comunario button
                safeAddEventListener('btn-add-comunario-' + modalId, 'click', addComunario);
            }

            // --- HELPERS (populateSelectedMembersFromChecked, filterMembers, etc - Keep minimal changes) ---
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

                if (assignedLeaderId) {
                    const ls = document.getElementById('lider-equipo-' + modalId);
                    const opt = Array.from(ls.options).some(o => o.value === assignedLeaderId);
                    if (opt) {
                        ls.value = assignedLeaderId;
                        const info = document.getElementById('lider-info-' + modalId);
                        const txt = document.getElementById('lider-nombre-display-' + modalId);
                        txt.textContent = ls.options[ls.selectedIndex].text;
                        info.classList.remove('d-none');
                    }
                }
            }

            function filterMembers() {
                const term = document.getElementById('search-member-' + modalId).value.toLowerCase();
                const ent = document.getElementById('filter-entidad-' + modalId).value.toLowerCase();
                const niv = document.getElementById('filter-nivel-' + modalId).value.toLowerCase();
                document.querySelectorAll('#members-tbody-' + modalId + ' .member-row').forEach(row => {
                    const n = row.cells[1].textContent.toLowerCase();
                    const e = row.getAttribute('data-entidad');
                    const l = row.getAttribute('data-nivel');
                    const match = n.includes(term) && (!ent || e.includes(ent)) && (!niv || l.includes(niv));
                    row.style.display = match ? '' : 'none';
                });
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
                updateSelectedMembersList();
                updateLeaderSelect();
            }

            function updateSelectedMembersList() {
                const tbody = document.getElementById('selected-members-list-' + modalId);
                if (selectedMembers.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-info-circle"></i> No hay miembros seleccionados</td></tr>';
                } else {
                    tbody.innerHTML = '';
                    selectedMembers.forEach((m, i) => {
                        const r = document.createElement('tr');
                        r.innerHTML =
                            `<td>${i+1}</td><td>${m.nombre}</td><td>${m.entidad}</td><td>${m.nivel}</td><td><span class="badge badge-secondary">Miembro</span></td>`;
                        tbody.appendChild(r);
                    });
                }
            }

            function updateResumen() {
                const n = document.getElementById('nombre_equipo-' + modalId).value || '-';
                const s = document.getElementById('estado_id-' + modalId);
                const t = s.options[s.selectedIndex]?.text || '-';
                document.getElementById('resumen-nombre-' + modalId).textContent = n;
                document.getElementById('resumen-estado-' + modalId).textContent = t;
            }

            function updateLeaderSelect() {
                const ls = document.getElementById('lider-equipo-' + modalId);
                const val = ls.value;
                ls.innerHTML = '<option value="">Seleccione un líder</option>';
                selectedMembers.forEach(m => {
                    const o = document.createElement('option');
                    o.value = m.id;
                    o.textContent = m.nombre;
                    ls.appendChild(o);
                });
                if (val && selectedMembers.some(m => m.id === val)) ls.value = val;
            }

            function addComunario() {
                const ni = document.getElementById('nombre_comunario-' + modalId);
                const ei = document.getElementById('edad_comunario-' + modalId);
                const enti = document.getElementById('entidad_comunario-' + modalId);
                const n = ni.value.trim();
                const e = ei.value.trim();
                const ent = enti ? enti.value.trim() : '';
                if (!n || !e) {
                    alert('Ingrese nombre y edad del comunario');
                    return;
                }
                comunarios.push({
                    nombre: n,
                    edad: e,
                    entidad: ent
                });
                ni.value = '';
                ei.value = '';
                if (enti) enti.value = '';
                renderComunariosList();
            }

            function renderComunariosList() {
                const tbody = document.getElementById('comunarios-list-' + modalId);
                if (comunarios.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="text-center text-muted py-3">No hay comunarios agregados</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                comunarios.forEach((c, i) => {
                    const r = document.createElement('tr');
                    r.innerHTML =
                        `<td>${c.nombre}</td><td>${c.edad}</td><td>${c.entidad || '-'}</td><td><button type="button" class="btn btn-danger btn-xs btn-del-com" data-index="${i}"><i class="fas fa-trash"></i></button></td>`;
                    tbody.appendChild(r);
                });
                document.querySelectorAll('.btn-del-com').forEach(b => b.addEventListener('click', function() {
                    comunarios.splice(this.getAttribute('data-index'), 1);
                    renderComunariosList();
                }));
            }

            function nextStep() {
                console.log('nextStep called, current step:', currentStep);
                if (validateStep(currentStep)) {
                    currentStep++;
                    console.log('Moving to step:', currentStep);
                    updateStep();
                } else {
                    console.log('Validation failed for step:', currentStep);
                }
            }

            function prevStep() {
                console.log('prevStep called, current step:', currentStep);
                currentStep--;
                console.log('Moving to step:', currentStep);
                updateStep();
            }

            function updateStep() {
                console.log('updateStep called, currentStep:', currentStep);
                const tabs = ['ubicacion', 'configurar', 'lider', 'mochila', 'comunarios', 'resumen'];

                tabs.forEach((t, i) => {
                    const tab = document.getElementById('tab-' + t + '-' + modalId);
                    const pane = document.getElementById('step-' + t + '-' + modalId);

                    if (tab && pane) {
                        if (i + 1 === currentStep) {
                            tab.classList.add('active');
                            tab.classList.remove('disabled');
                            pane.classList.add('show', 'active');
                        } else if (i + 1 < currentStep) {
                            tab.classList.remove('active', 'disabled');
                            pane.classList.remove('show', 'active');
                        } else {
                            tab.classList.remove('active');
                            tab.classList.add('disabled');
                            pane.classList.remove('show', 'active');
                        }
                    }
                });

                const p = (currentStep / 6) * 100;
                const pb = document.getElementById('progressBar-' + modalId);
                if (pb) {
                    pb.style.width = p + '%';
                    pb.setAttribute('aria-valuenow', p);
                    pb.textContent = 'Paso ' + currentStep + ' de 6';
                }

                const prevBtn = document.getElementById('btn-prev-' + modalId);
                const nextBtn = document.getElementById('btn-next-' + modalId);
                const saveBtn = document.getElementById('btn-save-' + modalId);

                if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                if (nextBtn) nextBtn.style.display = currentStep < 6 ? 'inline-block' : 'none';
                if (saveBtn) saveBtn.style.display = currentStep === 6 ? 'inline-block' : 'none';

                // Load products when entering Step 4 (Mochila)
                if (currentStep === 4 && productsLoadingState === 'initial') {
                    loadProductsFromAPI();
                }

                if (currentStep === 3) {
                    updateResumen();
                    updateLeaderSelect();
                }
                if (currentStep === 6) {
                    updateResumenFinal();
                }
            }

            function updateResumenFinal() {
                // 1. General Data
                const nombre = document.getElementById('nombre_equipo-' + modalId).value || '-';
                const estadoSelect = document.getElementById('estado_id-' + modalId);
                const estadoText = estadoSelect.options[estadoSelect.selectedIndex]?.text || '-';
                const ubicacionText = document.getElementById('resumen-ubicacion-' + modalId).textContent || '-';
                const codigo = document.getElementById('codigo_seguimiento-' + modalId).value || '-';

                document.getElementById('final-nombre-' + modalId).textContent = nombre;
                document.getElementById('final-estado-' + modalId).textContent = estadoText;
                document.getElementById('final-ubicacion-' + modalId).textContent = ubicacionText;
                document.getElementById('final-codigo-' + modalId).textContent = codigo;

                // 2. Leader
                const liderSelect = document.getElementById('lider-equipo-' + modalId);
                const liderText = liderSelect.options[liderSelect.selectedIndex]?.text || 'No seleccionado';
                document.getElementById('final-lider-' + modalId).textContent = liderText;

                // 3. Members
                document.getElementById('final-count-miembros-' + modalId).textContent = selectedMembers.length;
                const membersList = document.getElementById('final-lista-miembros-' + modalId);

                if (selectedMembers.length === 0) {
                    membersList.innerHTML = '<li>No hay miembros seleccionados</li>';
                } else {
                    membersList.innerHTML = '';
                    selectedMembers.forEach(m => {
                        const li = document.createElement('li');
                        li.textContent = m.nombre;
                        membersList.appendChild(li);
                    });
                }

                // 4. Mochila
                const mochilaList = document.getElementById('final-lista-mochila-' + modalId);
                const allItems = [
                    ...donations.filter(d => d.quantity > 0).map(d => ({
                        name: d.name,
                        quantity: d.quantity
                    })),
                    ...products.filter(p => p.quantity > 0).map(p => ({
                        name: p.nombre,
                        quantity: `${p.quantity} ${p.unidad_medida}`
                    }))
                ];

                if (allItems.length === 0) {
                    mochilaList.innerHTML = '<tr><td colspan="2" class="text-center">-</td></tr>';
                } else {
                    mochilaList.innerHTML = '';
                    allItems.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>${item.name}</td><td style="width:50px;">${item.quantity}</td>`;
                        mochilaList.appendChild(tr);
                    });
                }

                // 5. Comunarios
                const comunariosList = document.getElementById('final-lista-comunarios-' + modalId);
                if (comunarios.length === 0) {
                    comunariosList.innerHTML = '<tr><td colspan="3" class="text-center">None</td></tr>';
                } else {
                    comunariosList.innerHTML = '';
                    comunarios.forEach(c => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>${c.nombre}</td><td style="width:50px;">${c.edad}</td><td>${c.entidad || '-'}</td>`;
                        comunariosList.appendChild(tr);
                    });
                }
            }

            function validateStep(step) {
                console.log('validateStep called for step:', step);

                if (step === 2) {
                    const nombreEl = document.getElementById('nombre_equipo-' + modalId);
                    const estadoEl = document.getElementById('estado_id-' + modalId);
                    const n = nombreEl ? nombreEl.value.trim() : '';
                    const e = estadoEl ? estadoEl.value : '';

                    console.log('Step 2 validation - nombre:', n, 'estado:', e);

                    if (!n || !e) {
                        alert('Complete nombre y estado del equipo');
                        return false;
                    }
                }

                console.log('Step', step, 'validation passed');
                return true;
            }

            function resetForm() {
                currentStep = 1;
                selectedMembers = [];
                comunarios = initialComunarios.length ? JSON.parse(JSON.stringify(initialComunarios)) : [];
                donations.forEach(d => d.quantity = 0);
                products.forEach(p => p.quantity = 0);
                // Reset products loading state for next modal open
                productsLoadingState = 'initial';
                productsLoadNote = '';
                products = [];
                renderMochilaTable();
                renderComunariosList();
                updateStep();
            }

            // --- FINAL SUBMISSION ---
            function saveTeam() {
                const nombre = document.getElementById('nombre_equipo-' + modalId).value.trim();
                const estado = document.getElementById('estado_id-' + modalId).value;

                if (!nombre || !estado) {
                    alert('Por favor complete el nombre y estado del equipo');
                    return;
                }

                // Construct Insumos String
                const insumosParts = [];
                donations.forEach(d => {
                    if (d.quantity > 0) insumosParts.push(`${d.name}: ${d.quantity}`);
                });
                products.forEach(p => {
                    if (p.quantity > 0) insumosParts.push(`${p.nombre}: ${p.quantity} ${p.unidad_medida}`);
                });
                const insumosString = insumosParts.join(', ');

                // Update the insumos field in the existing form
                let insumosField = document.getElementById('insumos_necesarios-' + modalId);
                if (!insumosField) {
                    // Create the field if it doesn't exist
                    insumosField = document.createElement('input');
                    insumosField.type = 'hidden';
                    insumosField.id = 'insumos_necesarios-' + modalId;
                    insumosField.name = 'insumos_necesarios';
                    document.getElementById('team-form-' + modalId).appendChild(insumosField);
                }
                insumosField.value = insumosString;

                // Add selected members as hidden fields
                const form = document.getElementById('team-form-' + modalId);

                // Remove existing member fields
                const existingMembers = form.querySelectorAll('input[name="miembros[]"]');
                existingMembers.forEach(field => field.remove());

                // Add current selected members
                selectedMembers.forEach(m => {
                    const memberField = document.createElement('input');
                    memberField.type = 'hidden';
                    memberField.name = 'miembros[]';
                    memberField.value = m.id;
                    form.appendChild(memberField);
                });

                // Add leader field
                let liderField = document.getElementById('lider_id-hidden-' + modalId);
                if (!liderField) {
                    liderField = document.createElement('input');
                    liderField.type = 'hidden';
                    liderField.id = 'lider_id-hidden-' + modalId;
                    liderField.name = 'lider_id';
                    form.appendChild(liderField);
                }
                const liderSelect = document.getElementById('lider-equipo-' + modalId);
                liderField.value = liderSelect ? liderSelect.value : '';

                // Add comunarios as array inputs for backend validation
                // Always remove existing comunarios fields first
                form.querySelectorAll('input[name^="comunarios"]').forEach(field => field.remove());
                
                // Add a flag to indicate comunarios should be synced (for edit mode)
                if (isEditMode) {
                    const syncField = document.createElement('input');
                    syncField.type = 'hidden';
                    syncField.name = 'sync_comunarios';
                    syncField.value = '1';
                    form.appendChild(syncField);
                }
                
                // Add comunarios fields (even if empty array, backend will handle it)
                comunarios.forEach((c, idx) => {
                    const nameField = document.createElement('input');
                    nameField.type = 'hidden';
                    nameField.name = `comunarios[${idx}][nombre]`;
                    nameField.value = c.nombre;
                    form.appendChild(nameField);

                    const edadField = document.createElement('input');
                    edadField.type = 'hidden';
                    edadField.name = `comunarios[${idx}][edad]`;
                    edadField.value = c.edad;
                    form.appendChild(edadField);

                    const entField = document.createElement('input');
                    entField.type = 'hidden';
                    entField.name = `comunarios[${idx}][entidad]`;
                    entField.value = c.entidad || '';
                    form.appendChild(entField);
                });

                console.log('Submitting team form');
                form.submit();
            }
        })();
    </script>
@endpush
