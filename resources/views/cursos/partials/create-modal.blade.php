<div class="modal fade" id="createCursoModal" tabindex="-1" role="dialog" aria-labelledby="createCursoModalLabel" aria-hidden="true">
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
                                <input type="text"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       id="nombre"
                                       name="nombre"
                                       required
                                       maxlength="200"
                                       value="{{ old('nombre') }}"
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
                                <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                          id="descripcion"
                                          name="descripcion"
                                          rows="4"
                                          placeholder="Ingrese una descripción detallada del curso">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Opcional. Describa los objetivos y contenido del curso.
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
        // Mostrar modal si hay errores de validación
        @if ($errors->any())
            $('#createCursoModal').modal('show');
        @endif

        // Limpiar formulario al cerrar modal
        $('#createCursoModal').on('hidden.bs.modal', function () {
            $('#createCursoForm')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.alert').alert('close');
        });
    });
</script>
@endsection
