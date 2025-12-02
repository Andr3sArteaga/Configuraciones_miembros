{{-- Modal: Create Course with Stages --}}
<div class="modal fade" id="createCourseStagesModal" tabindex="-1" role="dialog" aria-labelledby="createCourseStagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createCourseStagesModalLabel">
                    <i class="fas fa-graduation-cap mr-1"></i> Nuevo Curso con Etapas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                {{-- Course Name --}}
                <div class="form-group">
                    <label for="courseName">
                        Nombre del Curso <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="courseName" 
                           placeholder="Ingrese el nombre del curso"
                           maxlength="200">
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Requerido. Máximo 200 caracteres.
                    </small>
                </div>

                {{-- Course Description --}}
                <div class="form-group">
                    <label for="courseDescription">
                        Descripción del Curso
                    </label>
                    <textarea class="form-control" 
                              id="courseDescription" 
                              rows="4" 
                              placeholder="Ingrese una descripción detallada del curso"></textarea>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Opcional. Describa los objetivos y contenido del curso.
                    </small>
                </div>

                {{-- Stages Section --}}
                <div class="form-group">
                    <label>
                        Etapas <span class="text-danger">*</span>
                        <span class="badge badge-danger ml-1" id="stageCountBadge">0/3 mínimo</span>
                    </label>
                    <small class="form-text text-muted mb-2">
                        <i class="fas fa-layer-group"></i> Agregue entre 3 y 4 etapas para el curso.
                    </small>

                    {{-- Add Stage Input --}}
                    <div class="input-group mb-3">
                        <input type="text" 
                               class="form-control" 
                               id="stageInput" 
                               placeholder="Nombre de la etapa"
                               maxlength="100">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="addStageBtn">
                                <i class="fas fa-plus"></i> Agregar Etapa
                            </button>
                        </div>
                    </div>

                    {{-- Stages List --}}
                    <div class="list-group" id="stagesList">
                        {{-- Stages will be dynamically added here --}}
                    </div>

                    {{-- Empty State Message --}}
                    <div class="alert alert-warning" id="stagesEmptyMessage">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Debe agregar al menos 3 etapas para continuar.
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveCourseBtn" disabled>
                    <i class="fas fa-save mr-1"></i> Guardar Curso
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS for this modal --}}
<style>
    /* Override global input-group styling for this modal */
    #createCourseStagesModal .input-group .form-control {
        background-color: #ffffff !important;
    }
    
    #createCourseStagesModal .input-group-append .btn {
        background-color: #007bff !important; /* Blue for primary button */
    }
    
    /* Ensure list items have white background */
    #createCourseStagesModal .list-group-item {
        background-color: #ffffff !important;
        border: 1px solid rgba(0,0,0,.125);
    }
    
    /* Remove the yellow/warning alert background, keep it subtle */
    #createCourseStagesModal .alert-warning {
        background-color: #fff3cd;
        border-color: #ffc107;
        color: #856404;
    }
</style>

{{-- JavaScript for Stage Management --}}
@section('course-stages-modal-scripts')
<script>
    $(document).ready(function() {
        // Stage management variables
        let stages = [];
        const MIN_STAGES = 3;
        const MAX_STAGES = 4;

        // DOM elements
        const $stageInput = $('#stageInput');
        const $addStageBtn = $('#addStageBtn');
        const $stagesList = $('#stagesList');
        const $stageCountBadge = $('#stageCountBadge');
        const $saveCourseBtn = $('#saveCourseBtn');
        const $stagesEmptyMessage = $('#stagesEmptyMessage');
        const $courseName = $('#courseName');
        const $courseDescription = $('#courseDescription');

        // Add stage function
        function addStage() {
            const stageName = $stageInput.val().trim();

            // Validate input
            if (stageName === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor ingrese un nombre para la etapa.',
                    confirmButtonColor: '#28a745'
                });
                return;
            }

            // Check if max stages reached
            if (stages.length >= MAX_STAGES) {
                Swal.fire({
                    icon: 'info',
                    title: 'Límite alcanzado',
                    text: `Solo puede agregar un máximo de ${MAX_STAGES} etapas.`,
                    confirmButtonColor: '#28a745'
                });
                return;
            }

            // Add stage to array
            stages.push(stageName);

            // Clear input
            $stageInput.val('');

            // Update UI
            updateStagesUI();
        }

        // Remove stage function
        function removeStage(index) {
            stages.splice(index, 1);
            updateStagesUI();
        }

        // Update stages UI
        function updateStagesUI() {
            // Clear list
            $stagesList.empty();

            // Add each stage to the list
            stages.forEach((stage, index) => {
                const stageItem = `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-layer-group text-success mr-2"></i>
                            <strong>Etapa ${index + 1}:</strong> ${escapeHtml(stage)}
                        </div>
                        <button type="button" 
                                class="btn btn-danger btn-sm remove-stage-btn" 
                                data-index="${index}"
                                title="Eliminar etapa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                $stagesList.append(stageItem);
            });

            // Update badge
            const stageCount = stages.length;
            if (stageCount < MIN_STAGES) {
                $stageCountBadge.removeClass('badge-success badge-info').addClass('badge-danger');
                $stageCountBadge.text(`${stageCount}/${MIN_STAGES} mínimo`);
            } else if (stageCount === MAX_STAGES) {
                $stageCountBadge.removeClass('badge-danger badge-info').addClass('badge-success');
                $stageCountBadge.text(`${stageCount}/${MAX_STAGES} máximo`);
            } else {
                $stageCountBadge.removeClass('badge-danger badge-success').addClass('badge-info');
                $stageCountBadge.text(`${stageCount}/${MIN_STAGES} mínimo`);
            }

            // Show/hide empty message
            if (stageCount === 0) {
                $stagesEmptyMessage.show();
            } else {
                $stagesEmptyMessage.hide();
            }

            // Enable/disable input and button based on max stages
            if (stageCount >= MAX_STAGES) {
                $stageInput.prop('disabled', true);
                $addStageBtn.prop('disabled', true);
            } else {
                $stageInput.prop('disabled', false);
                $addStageBtn.prop('disabled', false);
            }

            // Enable/disable save button based on min stages
            if (stageCount >= MIN_STAGES) {
                $saveCourseBtn.prop('disabled', false);
            } else {
                $saveCourseBtn.prop('disabled', true);
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Event: Add stage button click
        $addStageBtn.on('click', function() {
            addStage();
        });

        // Event: Add stage on Enter key
        $stageInput.on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                addStage();
            }
        });

        // Event: Remove stage button click (delegated)
        $stagesList.on('click', '.remove-stage-btn', function() {
            const index = $(this).data('index');
            removeStage(index);
        });

        // Event: Save course button click
        $saveCourseBtn.on('click', function() {
            const courseName = $courseName.val().trim();
            const courseDescription = $courseDescription.val().trim();

            // Validate course name
            if (courseName === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor ingrese el nombre del curso.',
                    confirmButtonColor: '#28a745'
                });
                $courseName.focus();
                return;
            }

            // Validate stages
            if (stages.length < MIN_STAGES) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Etapas insuficientes',
                    text: `Debe agregar al menos ${MIN_STAGES} etapas.`,
                    confirmButtonColor: '#28a745'
                });
                return;
            }

            // Show success confirmation (visual only, no backend)
            Swal.fire({
                icon: 'success',
                title: '¡Curso creado!',
                html: `
                    <div class="text-left">
                        <p><strong>Nombre:</strong> ${escapeHtml(courseName)}</p>
                        <p><strong>Descripción:</strong> ${courseDescription ? escapeHtml(courseDescription) : 'Sin descripción'}</p>
                        <p><strong>Etapas (${stages.length}):</strong></p>
                        <ol class="mb-0">
                            ${stages.map(stage => `<li>${escapeHtml(stage)}</li>`).join('')}
                        </ol>
                    </div>
                `,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Close modal
                $('#createCourseStagesModal').modal('hide');
            });
        });

        // Event: Reset form when modal is closed
        $('#createCourseStagesModal').on('hidden.bs.modal', function() {
            // Clear form fields
            $courseName.val('');
            $courseDescription.val('');
            $stageInput.val('');
            
            // Clear stages array
            stages = [];
            
            // Update UI
            updateStagesUI();
        });

        // Initialize UI on load
        updateStagesUI();
    });
</script>
@endsection
