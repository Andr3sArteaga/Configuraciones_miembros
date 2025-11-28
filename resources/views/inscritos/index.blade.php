@extends('layouts.app')

@section('title', 'Gestión de Inscritos')

@section('content_header')
    <h1>Gestión de Inscritos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Inscritos</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearInscrito">
                    <i class="fas fa-plus"></i> Nuevo Inscrito
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="tablaInscritos" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>CI</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Cursos Asignados</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inscritos as $inscrito)
                        <tr>
                            <td>{{ $inscrito->nombres }}</td>
                            <td>{{ $inscrito->apellidos }}</td>
                            <td>{{ $inscrito->ci }}</td>
                            <td>{{ $inscrito->telefono ?? 'N/A' }}</td>
                            <td>{{ $inscrito->correo }}</td>
                            <td>
                                @php
                                    $cursosAsignados = $inscrito->cursos_asignados;
                                @endphp
                                @if ($cursosAsignados->count() > 0)
                                    @foreach ($cursosAsignados as $asignacion)
                                        <span class="badge badge-info">{{ $asignacion->curso->nombre ?? 'N/A' }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary">Sin cursos</span>
                                @endif
                            </td>
                            <td>{{ $inscrito->fecha_registro ? $inscrito->fecha_registro->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editarInscrito('{{ $inscrito->id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarInscrito('{{ $inscrito->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Inscrito -->
    <div class="modal fade" id="modalCrearInscrito" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Crear Nuevo Inscrito</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCrearInscrito">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    <span class="text-danger error-nombres"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    <span class="text-danger error-apellidos"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ci">CI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ci" name="ci" required>
                                    <span class="text-danger error-ci"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                    <span class="text-danger error-telefono"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="correo">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="correo" name="correo" required>
                                    <span class="text-danger error-correo"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="curso_id">Curso <span class="text-danger">*</span></label>
                                    <select class="form-control" id="curso_id" name="curso_id" required>
                                        <option value="">Seleccione un curso</option>
                                        @foreach ($cursos ?? [] as $curso)
                                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-curso_id"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Inscrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Inscrito -->
    <div class="modal fade" id="modalEditarInscrito" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar Inscrito</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditarInscrito">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_inscrito_id" name="inscrito_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_nombres" name="nombres"
                                        required>
                                    <span class="text-danger error-edit-nombres"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_apellidos">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_apellidos" name="apellidos"
                                        required>
                                    <span class="text-danger error-edit-apellidos"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_ci">CI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_ci" name="ci" required>
                                    <span class="text-danger error-edit-ci"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="edit_telefono" name="telefono">
                                    <span class="text-danger error-edit-telefono"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="edit_correo">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="edit_correo" name="correo" required>
                                    <span class="text-danger error-edit-correo"></span>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Para cambiar los cursos asignados, hazlo desde la vista del curso
                            correspondiente.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Actualizar Inscrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#tablaInscritos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                order: [
                    [6, 'desc']
                ] // Ordenar por fecha de registro descendente
            });

            // Crear Inscrito
            $('#formCrearInscrito').on('submit', function(e) {
                e.preventDefault();
                console.log('Formulario enviado');

                // Limpiar errores previos
                $('.text-danger[class^="error-"]').text('');

                let formData = $(this).serialize();
                console.log('Datos del formulario:', formData);

                $.ajax({
                    url: '{{ route('inscritos.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        console.log('Enviando petición AJAX...');
                    },
                    success: function(response) {
                        console.log('Respuesta exitosa:', response);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $('.error-' + field).text(errors[field][0]);
                            }
                            Swal.fire({
                                icon: 'warning',
                                title: 'Errores de validación',
                                text: 'Por favor revisa los campos marcados en rojo'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Error al crear inscrito: ' + error
                            });
                        }
                    }
                });
            });

            // Editar Inscrito
            $('#formEditarInscrito').on('submit', function(e) {
                e.preventDefault();

                let inscritoId = $('#edit_inscrito_id').val();

                // Limpiar errores previos
                $('.text-danger[class^="error-edit-"]').text('');

                $.ajax({
                    url: '/inscritos/' + inscritoId,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $('.error-edit-' + field).text(errors[field][0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message ||
                                    'Error al actualizar inscrito'
                            });
                        }
                    }
                });
            });
        });

        // Función para editar inscrito
        function editarInscrito(id) {
            $.ajax({
                url: '/inscritos/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#edit_inscrito_id').val(response.inscrito.id);
                    $('#edit_nombres').val(response.inscrito.nombres);
                    $('#edit_apellidos').val(response.inscrito.apellidos);
                    $('#edit_ci').val(response.inscrito.ci);
                    $('#edit_telefono').val(response.inscrito.telefono);
                    $('#edit_correo').val(response.inscrito.correo);

                    $('#modalEditarInscrito').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la información del inscrito'
                    });
                }
            });
        }

        // Función para eliminar inscrito
        function eliminarInscrito(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción eliminará al inscrito y sus asignaciones de cursos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/inscritos/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Error al eliminar inscrito'
                            });
                        }
                    });
                }
            });
        }
    </script>
@stop
