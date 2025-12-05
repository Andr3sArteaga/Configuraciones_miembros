<div class="modal fade" id="createCursoWizardModal" tabindex="-1" role="dialog"
     aria-labelledby="createCursoWizardModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCursoWizardModalLabel">
                    <i class="fas fa-graduation-cap mr-1"></i> Crear Nuevo Curso
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('cursos.store') }}" method="POST" id="createCursoWizardForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body bg-light" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Barra de progreso (similar a Crear Nuevo Equipo) -->
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                             role="progressbar"
                             id="cursoProgressBar"
                             style="width: 33%; font-size: 14px; line-height: 25px;"
                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                            <span style="white-space: nowrap;">Paso <span data-step-indicator>1</span> de 3</span>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-start">
                        <i class="fas fa-info-circle mt-1 mr-2"></i>
                        <div>
                            <strong>Recordatorio:</strong> cada curso debe tener entre 3 y 4 etapas y la fecha de inicio
                            debe ser posterior a hoy.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Navegación de pasos (tabs como en Crear Nuevo Equipo) -->
                            <ul class="nav nav-tabs" id="cursoTabsNav" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-curso-info"
                                       data-toggle="tab" href="#curso-step-1" role="tab">
                                        <i class="fas fa-info-circle"></i> 1. Información
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" id="tab-curso-etapas"
                                       data-toggle="tab" href="#curso-step-2" role="tab">
                                        <i class="fas fa-layer-group"></i> 2. Etapas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" id="tab-curso-contenido"
                                       data-toggle="tab" href="#curso-step-3" role="tab">
                                        <i class="fas fa-folder-open"></i> 3. Contenido
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- Paso 1: Información del curso -->
                                <div class="tab-pane fade show active" id="curso-step-1">
                                    <div class="card rounded shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="curso_nombre" class="font-weight-semibold">
                                                    Nombre del Curso <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       id="curso_nombre"
                                                       name="nombre"
                                                       class="form-control @error('nombre') is-invalid @enderror"
                                                       value="{{ old('nombre') }}"
                                                       maxlength="200"
                                                       required
                                                       placeholder="Ej: Entrenamiento Avanzado de Brigadas">
                                                @error('nombre')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="curso_inicio_programado" class="font-weight-semibold">
                                                        Fecha de inicio <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date"
                                                           id="curso_inicio_programado"
                                                           name="inicio_programado"
                                                           min="{{ now()->toDateString() }}"
                                                           class="form-control @error('inicio_programado') is-invalid @enderror"
                                                           value="{{ old('inicio_programado') }}">
                                                    @error('inicio_programado')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="curso_fin_programado" class="font-weight-semibold">
                                                        Fecha de finalización <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date"
                                                           id="curso_fin_programado"
                                                           name="fin_programado"
                                                           class="form-control @error('fin_programado') is-invalid @enderror"
                                                           value="{{ old('fin_programado') }}">
                                                    @error('fin_programado')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="curso_descripcion" class="font-weight-semibold">
                                                    Descripción
                                                </label>
                                                <textarea id="curso_descripcion"
                                                          name="descripcion"
                                                          rows="3"
                                                          class="form-control @error('descripcion') is-invalid @enderror"
                                                          placeholder="Objetivos, contenido y resultados esperados">{{ old('descripcion') }}</textarea>
                                                @error('descripcion')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-0">
                                                <label for="curso_objetivos" class="font-weight-semibold">
                                                    Objetivos del curso
                                                </label>
                                                <textarea id="curso_objetivos"
                                                          name="objetivos"
                                                          rows="3"
                                                          class="form-control @error('objetivos') is-invalid @enderror"
                                                          placeholder="Ej: Mejorar la coordinación en incendios forestales, estandarizar protocolos, etc.">{{ old('objetivos') }}</textarea>
                                                @error('objetivos')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paso 2: Etapas -->
                                <div class="tab-pane fade" id="curso-step-2">
                                    <div class="card rounded shadow-sm border-0 mb-3">
                                        <div class="card-body">
                                            <div class="form-row align-items-end">
                                                <div class="form-group col-md-8">
                                                    <label for="curso_module_name" class="font-weight-semibold">
                                                        Nombre del módulo
                                                    </label>
                                                    <input type="text"
                                                           id="curso_module_name"
                                                           class="form-control"
                                                           placeholder="Ej: Módulo I: Evaluación Inicial">
                                                </div>
                                                <div class="form-group col-md-4 text-right">
                                                    <button type="button" class="btn btn-primary" id="cursoAddStageBtn">
                                                        <i class="fas fa-plus mr-1"></i> Agregar
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                Debes registrar al menos <strong>3</strong> y como máximo <strong>4</strong> etapas.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card rounded shadow-sm border-0">
                                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                            <span class="font-weight-semibold">Etapas actuales</span>
                                            <span class="badge badge-primary" id="cursoStageCount">0</span>
                                        </div>
                                        <div class="card-body" id="cursoStageCards">
                                            <div class="text-center text-muted py-4" data-empty-placeholder>
                                                No hay etapas registradas. Agrega al menos 3.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paso 3: Contenido & Recursos -->
                                <div class="tab-pane fade" id="curso-step-3">
                                    <div class="card rounded shadow-sm border-0">
                                        <div class="card-body">
                                            <div id="cursoResourcesAccordion">
                                                {{-- Paneles de recursos por etapa se generan dinámicamente vía JS --}}
                                                <div class="text-muted text-center py-4" id="cursoResourcesPlaceholder">
                                                    Primero define las etapas para configurar sus recursos.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna de resumen a la derecha -->
                        <div class="col-lg-4">
                            <div class="card rounded shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted text-uppercase mb-2">Resumen del Curso</h6>
                                    <p class="mb-1">
                                        <strong>Nombre:</strong>
                                        <span data-summary-name>—</span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fechas:</strong>
                                        <span data-summary-dates>—</span>
                                    </p>
                                </div>
                            </div>

                            <div class="card rounded shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted text-uppercase d-flex justify-content-between mb-2">
                                        Etapas
                                        <span class="badge badge-primary" data-summary-stage-count>0</span>
                                    </h6>
                                    <div class="small text-muted" data-summary-stage-list>
                                        Sin etapas registradas.
                                    </div>
                                </div>
                            </div>

                            <div class="card rounded shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="text-muted text-uppercase mb-2">Recursos</h6>
                                    <ul class="list-unstyled small mb-0" data-summary-resources>
                                        <li>Videos: —</li>
                                        <li>Documentos: —</li>
                                        <li>Lecturas: —</li>
                                        <li>Material extra: —</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cursoPrevStepBtn" style="display: none;">
                        <i class="fas fa-arrow-left mr-1"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="cursoNextStepBtn">
                        Siguiente <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="cursoSubmitBtn" style="display: none;">
                        <i class="fas fa-save mr-1"></i> Guardar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        (function () {
            let currentStep = 1;
            const minStages = 3;
            const maxStages = 4;
            let stageCounter = 0;

            const $modal = $('#createCursoWizardModal');
            const $form = $('#createCursoWizardForm');
            
            // Elementos de fecha
            const $inicio = $('#curso_inicio_programado');
            const $fin = $('#curso_fin_programado');

            // Calcular fecha actual YYYY-MM-DD
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const today = `${year}-${month}-${day}`;

            function updateStepUI() {
                // Indicador numérico
                $('[data-step-indicator]').text(currentStep);

                // Actualizar tabs (similar al modal de equipos)
                const $tabs = $('#cursoTabsNav .nav-link');
                $tabs.each(function (index) {
                    const stepIndex = index + 1;
                    if (stepIndex === currentStep) {
                        $(this).addClass('active').removeClass('disabled');
                    } else if (stepIndex < currentStep) {
                        $(this).removeClass('disabled').removeClass('active');
                    } else {
                        $(this).removeClass('active').addClass('disabled');
                    }
                });

                // Contenido de tabs
                $('.tab-pane', $modal).removeClass('show active');
                $('#curso-step-' + currentStep).addClass('show active');

                // Barra de progreso
                const progress = (currentStep / 3) * 100;
                const $progressBar = $('#cursoProgressBar');
                $progressBar.css('width', progress + '%');
                $progressBar.attr('aria-valuenow', progress);
                $progressBar.html('<span style="white-space: nowrap;">Paso <span data-step-indicator>' + currentStep + '</span> de 3</span>');

                // Botones de pie
                const $prevBtn = $('#cursoPrevStepBtn');
                const $nextBtn = $('#cursoNextStepBtn');
                const $submitBtn = $('#cursoSubmitBtn');

                $prevBtn.toggle(currentStep > 1);
                if (currentStep === 3) {
                    $nextBtn.hide();
                    $submitBtn.show();
                } else {
                    $nextBtn.show();
                    $submitBtn.hide();
                }
            }

            function validateCurrentStep() {
                if (currentStep === 1) {
                    const nombre = $('#curso_nombre').val().trim();
                    const inicio = $inicio.val();
                    const fin = $fin.val();

                    // 1. Validar campos vacíos
                    if (!nombre || !inicio || !fin) {
                        alert('Por favor complete nombre y fechas del curso.');
                        return false;
                    }

                    // 2. Validar que las fechas no sean pasadas (Validación manual estricta)
                    if (inicio < today) {
                        alert(`La fecha de inicio no puede ser anterior a hoy (${today}).`);
                        $inicio.focus();
                        return false;
                    }

                    if (fin < today) {
                        alert(`La fecha de finalización no puede ser anterior a hoy (${today}).`);
                        $fin.focus();
                        return false;
                    }

                    // 3. Validar coherencia entre fechas
                    if (fin < inicio) {
                        alert('La fecha de finalización debe ser posterior o igual a la fecha de inicio.');
                        $fin.focus();
                        return false;
                    }
                }

                if (currentStep === 2) {
                    const count = parseInt($('#cursoStageCount').text(), 10) || 0;
                    if (count < minStages || count > maxStages) {
                        alert('Debe registrar entre ' + minStages + ' y ' + maxStages + ' etapas.');
                        return false;
                    }
                }

                return true;
            }

            function refreshSummary() {
                const nombre = $('#curso_nombre').val() || '—';
                const inicio = $inicio.val();
                const fin = $fin.val();

                $('[data-summary-name]').text(nombre);
                if (inicio && fin) {
                    const formatDate = (dateStr) => {
                        const [year, month, day] = dateStr.split('-');
                        return `${day}/${month}/${year}`;
                    };
                    $('[data-summary-dates]').text(formatDate(inicio) + ' al ' + formatDate(fin));
                } else {
                    $('[data-summary-dates]').text('—');
                }
            }

            function refreshStageSummary() {
                const $cards = $('#cursoStageCards .curso-stage-card');
                const count = $cards.length;
                $('[data-summary-stage-count]').text(count);

                if (!count) {
                    $('[data-summary-stage-list]').text('Sin etapas registradas.');
                    return;
                }

                const items = [];
                $cards.each(function () {
                    const titulo = $(this).find('[data-stage-title]').text();
                    const modulo = $(this).find('[data-stage-module]').text();
                    items.push(titulo + ' · ' + modulo);
                });

                $('[data-summary-stage-list]').html(
                    items.map(function (txt) {
                        return '<div>' + txt + '</div>';
                    }).join('')
                );
            }

            function refreshResourcesSummary() {
                let videos = 0, docs = 0, readings = 0, extras = 0;

                $('#cursoResourcesAccordion').find('input[type="url"], input[type="file"], textarea').each(function () {
                    const name = $(this).attr('name') || '';
                    if (!$(this).val()) return;

                    if (name.includes('[video]')) videos++;
                    if (name.includes('[doc]')) docs++;
                    if (name.includes('[reading]')) readings++;
                    if (name.includes('[extra]')) extras++;
                });

                const $res = $('[data-summary-resources]');
                $res.html(
                    '<li>Videos: ' + (videos || '—') + '</li>' +
                    '<li>Documentos: ' + (docs || '—') + '</li>' +
                    '<li>Lecturas: ' + (readings || '—') + '</li>' +
                    '<li>Material extra: ' + (extras || '—') + '</li>'
                );
            }

            function rebuildResourcesPanels() {
                const $cards = $('#cursoStageCards .curso-stage-card');
                const $accordion = $('#cursoResourcesAccordion');
                const $placeholder = $('#cursoResourcesPlaceholder');

                $accordion.empty();

                if (!$cards.length) {
                    $accordion.append($placeholder);
                    $placeholder.show();
                    refreshResourcesSummary();
                    return;
                }

                $placeholder.hide();

                $cards.each(function (index) {
                    const stageNumber = index + 1;
                    const stageId = $(this).data('stage-id');
                    const panelId = 'cursoStageResources-' + stageId;
                    const headingId = 'heading-' + stageId;

                    const isFinal = $(this).data('final-stage') === 1;
                    const title = $(this).find('[data-stage-title]').text();

                    const panel = `
                        <div class="card mb-2">
                            <div class="card-header p-2" id="${headingId}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#${panelId}" aria-expanded="${index === 0 ? 'true' : 'false'}">
                                        ${title} · Videos / Documentos / Lecturas
                                        ${isFinal ? '<span class="badge badge-success ml-2">Etapa final</span>' : ''}
                                    </button>
                                </h5>
                            </div>
                            <div id="${panelId}" class="collapse ${index === 0 ? 'show' : ''}"
                                 aria-labelledby="${headingId}" data-parent="#cursoResourcesAccordion">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label class="font-weight-semibold">Video</label>
                                            <input type="url" class="form-control"
                                                   name="stages[${stageNumber}][video]"
                                                   placeholder="https://...">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="font-weight-semibold">Documento</label>
                                            <div class="input-group input-group-sm file-uploader-wrapper mb-2">
                                                <input type="text" class="form-control file-uploader-display" placeholder="Ningún archivo seleccionado" readonly style="background-color: white !important;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary file-uploader-trigger"
                                                            type="button"
                                                            data-target="wizard_stage_doc_${stageId}"
                                                            style="background-color: #e9ecef; border-color: #ced4da; color: #007bff;">
                                                        Buscar
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none file-uploader-input"
                                                   id="wizard_stage_doc_${stageId}"
                                                   name="stages[${stageNumber}][doc]"
                                                   accept=".pdf,.ppt,.pptx,.doc,.docx">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="font-weight-semibold">Lectura</label>
                                            <input type="url" class="form-control"
                                                   name="stages[${stageNumber}][reading]"
                                                   placeholder="https://...">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="font-weight-semibold">Material extra</label>
                                        <textarea class="form-control" rows="2"
                                                  name="stages[${stageNumber}][extra]"
                                                  placeholder="Notas, ejercicios o material adicional"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $accordion.append(panel);
                });

                refreshResourcesSummary();
            }

            function addStage() {
                const $cardsContainer = $('#cursoStageCards');
                let currentCount = $('#cursoStageCards .curso-stage-card').length;

                if (currentCount >= maxStages) {
                    alert('Solo se permiten hasta ' + maxStages + ' etapas por curso.');
                    return;
                }

                const moduleName = $('#curso_module_name').val().trim() || ('Módulo ' + (currentCount + 1));
                stageCounter++;
                const stageLabel = 'Etapa ' + (currentCount + 1);
                const stageId = 's' + stageCounter;

                if ($cardsContainer.find('[data-empty-placeholder]').length) {
                    $cardsContainer.find('[data-empty-placeholder]').remove();
                }

                const card = `
                    <div class="card mb-2 curso-stage-card" data-stage-id="${stageId}">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="font-weight-bold" data-stage-title="${stageLabel}">${stageLabel}</div>
                                    <div class="small text-muted" data-stage-module>${moduleName}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger curso-remove-stage-btn ml-auto">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="stages_order[]" value="${moduleName}">
                    </div>
                `;

                $cardsContainer.append(card);
                currentCount++;

                $('#cursoStageCount').text(currentCount);
                $('#curso_module_name').val('');

                refreshStageSummary();
                rebuildResourcesPanels();
            }

            function removeStage(button) {
                const $card = $(button).closest('.curso-stage-card');
                $card.remove();

                const currentCount = $('#cursoStageCards .curso-stage-card').length;
                $('#cursoStageCount').text(currentCount);

                if (!currentCount) {
                    $('#cursoStageCards').html(
                        '<div class="text-center text-muted py-4" data-empty-placeholder>' +
                        'No hay etapas registradas. Agrega al menos 3.' +
                        '</div>'
                    );
                }

                // Recalcular labels
                $('#cursoStageCards .curso-stage-card').each(function (index) {
                    const stageLabel = 'Etapa ' + (index + 1);
                    $(this).find('[data-stage-title]').text(stageLabel).attr('data-stage-title', stageLabel);
                });

                refreshStageSummary();
                rebuildResourcesPanels();
            }

            $(document).ready(function () {
                // Inicializar fechas mínimas
                $inicio.attr('min', today);
                $fin.attr('min', today);

                // Abrir modal si hubo errores en validación de cursos
                @if ($errors->any() && session('modal') === 'curso-wizard')
                    $modal.modal('show');
                @endif

                $('#cursoNextStepBtn').on('click', function () {
                    if (!validateCurrentStep()) {
                        return;
                    }
                    currentStep = Math.min(3, currentStep + 1);
                    updateStepUI();
                });

                $('#cursoPrevStepBtn').on('click', function () {
                    currentStep = Math.max(1, currentStep - 1);
                    updateStepUI();
                });

                // Lógica de fechas dinámica
                $inicio.on('change', function() {
                    const val = $(this).val();
                    refreshSummary();
                    if(val) {
                        // Al cambiar inicio, el fin no puede ser menor
                        $fin.attr('min', val);
                        // Si fin ya tenía valor y es menor, limpiarlo o avisar
                        if($fin.val() && $fin.val() < val) {
                            $fin.val(val); // Ajustar automáticamente
                        }
                    } else {
                        $fin.attr('min', today);
                    }
                });

                $fin.on('change keyup', function () {
                    refreshSummary();
                });
                
                $('#curso_nombre').on('change keyup', function () {
                    refreshSummary();
                });

                $('#cursoAddStageBtn').on('click', function () {
                    addStage();
                });

                $('#cursoStageCards').on('click', '.curso-remove-stage-btn', function () {
                    removeStage(this);
                });

                $('#cursoResourcesAccordion').on('change', 'input, textarea', function () {
                    refreshResourcesSummary();
                });

                document.addEventListener('click', function (event) {
                    if (event.target.classList.contains('file-uploader-trigger')) {
                        const targetId = event.target.getAttribute('data-target');
                        const input = document.getElementById(targetId);
                        if (input) {
                            input.click();
                        }
                    }
                });

                document.addEventListener('change', function (event) {
                    if (event.target.classList.contains('file-uploader-input')) {
                        const wrapper = event.target.closest('.file-uploader-wrapper');
                        if (wrapper) {
                            const display = wrapper.querySelector('.file-uploader-display');
                            if (display) {
                                display.value = event.target.files.length ? event.target.files[0].name : '';
                            }
                        }
                    }
                });

                $modal.on('shown.bs.modal', function () {
                    currentStep = 1;
                    updateStepUI();
                    refreshSummary();
                    refreshStageSummary();
                    refreshResourcesSummary();
                    // Re-asegurar min dates al abrir
                    $inicio.attr('min', today);
                    $fin.attr('min', today);
                });
            });
        })();
    </script>
@endpush


