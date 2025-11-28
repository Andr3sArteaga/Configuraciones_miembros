@extends('layouts.app')

@section('title', 'Asignar Curso')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Asignar Curso</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active">Asignar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap"></i> {{ $curso->nombre }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Descripción:</strong> {{ $curso->descripcion ?? 'Sin descripción' }}</p>
                        <hr>

                        <form action="{{ route('cursos.store-asignacion', $curso->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="entidad_tipo">Tipo de Entidad <span class="text-danger">*</span></label>
                                        <select class="form-control @error('entidad_tipo') is-invalid @enderror"
                                            id="entidad_tipo" name="entidad_tipo" required>
                                            <option value="">Seleccione...</option>
                                            <option value="usuario"
                                                {{ old('entidad_tipo') == 'usuario' ? 'selected' : '' }}>
                                                Usuario
                                            </option>
                                            <option value="comunario"
                                                {{ old('entidad_tipo') == 'comunario' ? 'selected' : '' }}>
                                                Comunario de Apoyo
                                            </option>
                                            <option value="inscrito"
                                                {{ old('entidad_tipo') == 'inscrito' ? 'selected' : '' }}>
                                                Inscrito
                                            </option>
                                        </select>
                                        @error('entidad_tipo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" id="usuario-group" style="display: none;">
                                        <label for="usuario_select">Usuario <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="usuario_select" name="entidad_id_usuario">
                                            <option value="">Seleccione un usuario...</option>
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}"
                                                    {{ in_array($usuario->id, $usuariosAsignados) ? 'disabled' : '' }}>
                                                    {{ $usuario->nombre }} {{ $usuario->apellido }} (CI:
                                                    {{ $usuario->ci }})
                                                    {{ in_array($usuario->id, $usuariosAsignados) ? '- Ya asignado' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="comunario-group" style="display: none;">
                                        <label for="comunario_select">Comunario <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="comunario_select"
                                            name="entidad_id_comunario">
                                            <option value="">Seleccione un comunario...</option>
                                            @foreach ($comunarios as $comunario)
                                                <option value="{{ $comunario->id }}"
                                                    {{ in_array($comunario->id, $comunariosAsignados) ? 'disabled' : '' }}>
                                                    {{ $comunario->nombre }}
                                                    @if ($comunario->edad)
                                                        (Edad: {{ $comunario->edad }})
                                                    @endif
                                                    {{ in_array($comunario->id, $comunariosAsignados) ? '- Ya asignado' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="inscrito-group" style="display: none;">
                                        <label>Seleccione una opción <span class="text-danger">*</span></label>
                                        <div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
                                            <label class="btn btn-outline-primary active">
                                                <input type="radio" name="inscrito_option" id="inscrito_existente"
                                                    value="existente" checked> Inscrito Existente
                                            </label>
                                            <label class="btn btn-outline-success">
                                                <input type="radio" name="inscrito_option" id="inscrito_nuevo"
                                                    value="nuevo"> Crear Nuevo
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" id="inscrito-select-group" style="display: none;">
                                        <label for="inscrito_select">Inscrito <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="inscrito_select"
                                            name="entidad_id_inscrito">
                                            <option value="">Seleccione un inscrito...</option>
                                            @foreach ($inscritos as $inscrito)
                                                <option value="{{ $inscrito->id }}"
                                                    {{ in_array($inscrito->id, $inscritosAsignados) ? 'disabled' : '' }}>
                                                    {{ $inscrito->nombres }} {{ $inscrito->apellidos }} (CI:
                                                    {{ $inscrito->ci }})
                                                    {{ in_array($inscrito->id, $inscritosAsignados) ? '- Ya asignado' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de Inscrito (oculto inicialmente) -->
                            <div id="inscrito-form-fields" style="display: none;">
                                <hr>
                                <h5 class="text-success"><i class="fas fa-user-plus"></i> Datos del Nuevo Inscrito</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inscrito_nombres">Nombres <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inscrito_nombres"
                                                name="inscrito_nombres">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inscrito_apellidos">Apellidos <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inscrito_apellidos"
                                                name="inscrito_apellidos">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inscrito_ci">CI <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inscrito_ci"
                                                name="inscrito_ci">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inscrito_telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="inscrito_telefono"
                                                name="inscrito_telefono">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inscrito_correo">Correo <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="inscrito_correo"
                                                name="inscrito_correo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="entidad_id" id="entidad_id_hidden">

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-user-plus"></i> Asignar
                                    </button>
                                    <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver al Detalle
                                    </a>
                                    <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-list"></i> Ver Todos los Cursos
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Personas ya Asignadas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Usuarios Asignados ({{ count($usuariosAsignados) }})</h5>
                                @if (count($usuariosAsignados) > 0)
                                    <ul class="list-group">
                                        @foreach ($usuarios->whereIn('id', $usuariosAsignados) as $usuario)
                                            <li class="list-group-item">
                                                <i class="fas fa-user text-primary"></i>
                                                {{ $usuario->nombre }} {{ $usuario->apellido }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No hay usuarios asignados aún.</p>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <h5>Comunarios Asignados ({{ count($comunariosAsignados) }})</h5>
                                @if (count($comunariosAsignados) > 0)
                                    <ul class="list-group">
                                        @foreach ($comunarios->whereIn('id', $comunariosAsignados) as $comunario)
                                            <li class="list-group-item">
                                                <i class="fas fa-user-friends text-success"></i>
                                                {{ $comunario->nombre }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No hay comunarios asignados aún.</p>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <h5>Inscritos Asignados ({{ count($inscritosAsignados ?? []) }})</h5>
                                @if (isset($inscritos) && count($inscritosAsignados ?? []) > 0)
                                    <ul class="list-group">
                                        @foreach ($inscritos->whereIn('id', $inscritosAsignados) as $inscrito)
                                            <li class="list-group-item">
                                                <i class="fas fa-user-tag text-warning"></i>
                                                {{ $inscrito->nombres }} {{ $inscrito->apellidos }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No hay inscritos asignados aún.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap',
                width: '100%'
            });

            // Mostrar/ocultar campos según el tipo seleccionado
            $('#entidad_tipo').on('change', function() {
                const tipo = $(this).val();

                // Ocultar todos los grupos
                $('#usuario-group').hide();
                $('#comunario-group').hide();
                $('#inscrito-group').hide();
                $('#inscrito-select-group').hide();
                $('#inscrito-form-fields').hide();

                // Remover required de todos los campos
                $('#usuario_select').removeAttr('required');
                $('#comunario_select').removeAttr('required');
                $('#inscrito_select').removeAttr('required');
                $('#inscrito_nombres, #inscrito_apellidos, #inscrito_ci, #inscrito_correo').removeAttr(
                    'required');

                // Mostrar el grupo correspondiente
                if (tipo === 'usuario') {
                    $('#usuario-group').show();
                    $('#usuario_select').attr('required', true);
                } else if (tipo === 'comunario') {
                    $('#comunario-group').show();
                    $('#comunario_select').attr('required', true);
                } else if (tipo === 'inscrito') {
                    $('#inscrito-group').show();
                    // Por defecto mostrar inscrito existente
                    if ($('#inscrito_existente').is(':checked')) {
                        $('#inscrito-select-group').show();
                        $('#inscrito_select').attr('required', true);
                    } else {
                        $('#inscrito-form-fields').show();
                        $('#inscrito_nombres, #inscrito_apellidos, #inscrito_ci, #inscrito_correo').attr(
                            'required', true);
                    }
                }
            });

            // Manejar cambio entre inscrito existente y nuevo
            $('input[name="inscrito_option"]').on('change', function() {
                const option = $(this).val();

                if (option === 'existente') {
                    // Mostrar select de inscritos existentes
                    $('#inscrito-select-group').show();
                    $('#inscrito-form-fields').hide();

                    // Actualizar required
                    $('#inscrito_select').attr('required', true);
                    $('#inscrito_nombres, #inscrito_apellidos, #inscrito_ci, #inscrito_correo').removeAttr(
                        'required');
                } else {
                    // Mostrar formulario para crear nuevo
                    $('#inscrito-select-group').hide();
                    $('#inscrito-form-fields').show();

                    // Actualizar required
                    $('#inscrito_select').removeAttr('required');
                    $('#inscrito_nombres, #inscrito_apellidos, #inscrito_ci, #inscrito_correo').attr(
                        'required', true);
                }
            });

            // Al enviar el formulario
            $('form').on('submit', function(e) {
                const tipo = $('#entidad_tipo').val();

                if (tipo === 'usuario') {
                    const usuarioId = $('#usuario_select').val();
                    $('#entidad_id_hidden').val(usuarioId);
                } else if (tipo === 'comunario') {
                    const comunarioId = $('#comunario_select').val();
                    $('#entidad_id_hidden').val(comunarioId);
                } else if (tipo === 'inscrito') {
                    const inscritoOption = $('input[name="inscrito_option"]:checked').val();

                    if (inscritoOption === 'existente') {
                        // Si es inscrito existente, usar el ID seleccionado
                        const inscritoId = $('#inscrito_select').val();
                        $('#entidad_id_hidden').val(inscritoId);
                    } else {
                        // Para inscrito nuevo, el ID se generará en el backend
                        // No necesitamos establecer entidad_id_hidden
                    }
                }
            });
        });
    </script>
@stop
