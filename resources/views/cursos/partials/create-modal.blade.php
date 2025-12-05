<div class="modal fade" id="createCursoModal" tabindex="-1" role="dialog" aria-labelledby="createCursoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="createCursoModalLabel">
                    <i class="fas fa-graduation-cap mr-1"></i> Nuevo Curso
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cursos.store') }}" method="POST" id="createCursoForm">
                @csrf
                <div class="modal-body">
                    {{-- Alertas de error --}}
                    @if ($errors->any())
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombre">Nombre del Curso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" required maxlength="200" value="{{ old('nombre') }}"
                                    placeholder="Ingrese el nombre del curso">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                    rows="4" placeholder="Ingrese una descripción detallada del curso">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Opcional. Describa los objetivos y contenido del
                                    curso.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="objetivos">Objetivos</label>
                                <textarea class="form-control @error('objetivos') is-invalid @enderror" id="objetivos" name="objetivos" rows="3"
                                    placeholder="Ingrese los objetivos del curso">{{ old('objetivos') }}</textarea>
                                @error('objetivos')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Opcional. Defina los objetivos de aprendizaje.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inicio_programado">Fecha de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('inicio_programado') is-invalid @enderror"
                                    id="inicio_programado" name="inicio_programado" required min="{{ date('Y-m-d') }}"
                                    value="{{ old('inicio_programado') }}" title="Seleccione una fecha a partir de hoy"
                                    pattern="\d{4}-\d{2}-\d{2}" autocomplete="off">
                                @error('inicio_programado')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-calendar-alt"></i> La fecha debe ser hoy o posterior
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fin_programado">Fecha de Finalización <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fin_programado') is-invalid @enderror"
                                    id="fin_programado" name="fin_programado" required min="{{ date('Y-m-d') }}"
                                    value="{{ old('fin_programado') }}"
                                    title="Seleccione una fecha posterior a la fecha de inicio"
                                    pattern="\d{4}-\d{2}-\d{2}" autocomplete="off">
                                @error('fin_programado')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-calendar-alt"></i> Debe ser posterior a la fecha de inicio
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('modal-scripts')
    <script>
        $(document).ready(function() {
            const $form = $('#createCursoForm');
            const $submitBtn = $form.find('button[type="submit"]');
            const $inicio = $('#inicio_programado');
            const $fin = $('#fin_programado');
            
            // Obtener fecha actual en formato YYYY-MM-DD
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const today = `${year}-${month}-${day}`;

            // Inicializar fechas mínimas
            $inicio.attr('min', today);
            $fin.attr('min', today);

            function validateDates() {
                const inicioVal = $inicio.val();
                const finVal = $fin.val();
                let isValid = true;
                let errorMessage = '';

                // Resetear estados visuales
                $inicio.removeClass('is-invalid');
                $fin.removeClass('is-invalid');
                
                // Validar Fecha Inicio
                if (inicioVal) {
                    if (inicioVal < today) {
                        $inicio.addClass('is-invalid');
                        isValid = false;
                    }
                    // Actualizar min de fecha fin
                    $fin.attr('min', inicioVal);
                } else {
                    $fin.attr('min', today);
                }

                // Validar Fecha Fin
                if (finVal) {
                    // No puede ser menor a hoy
                    if (finVal < today) {
                        $fin.addClass('is-invalid');
                        isValid = false;
                    }
                    // No puede ser menor a fecha inicio (si existe)
                    if (inicioVal && finVal < inicioVal) {
                        $fin.addClass('is-invalid');
                        isValid = false;
                    }
                }

                // Controlar estado del botón
                $submitBtn.prop('disabled', !isValid);
            }

            // Event Listeners para validación en tiempo real
            $inicio.on('change input blur', validateDates);
            $fin.on('change input blur', validateDates);

            // Validación al enviar el formulario
            $form.on('submit', function(e) {
                validateDates();
                
                // Verificar si el botón está deshabilitado o hay clases invalidas
                if ($submitBtn.prop('disabled') || $('.is-invalid').length > 0) {
                    e.preventDefault();
                    return false;
                }

                const inicioVal = $inicio.val();
                const finVal = $fin.val();

                // Validación final de seguridad
                if (inicioVal < today || (finVal && finVal < today) || (inicioVal && finVal && finVal < inicioVal)) {
                    e.preventDefault();
                    validateDates(); // Esto marcará los campos en rojo
                    return false;
                }
            });

            // Resetear formulario al cerrar modal
            $('#createCursoModal').on('hidden.bs.modal', function() {
                $form[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $submitBtn.prop('disabled', false);
                $inicio.attr('min', today);
                $fin.attr('min', today);
            });
            
            // Validar al abrir (por si hay valores pre-cargados por error de validación de Laravel)
            $('#createCursoModal').on('shown.bs.modal', function() {
                 validateDates();
            });
        });
    </script>
@endsection
