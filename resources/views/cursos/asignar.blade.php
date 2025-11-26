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
                                        <label for="entidad_tipo">Tipo de Entidad <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('entidad_tipo') is-invalid @enderror"
                                            id="entidad_tipo" name="entidad_tipo" required>
                                            <option value="">Seleccione...</option>
                                            <option value="usuario" {{ old('entidad_tipo') == 'usuario' ? 'selected' : '' }}>
                                                Usuario (Bombero)
                                            </option>
                                            <option value="comunario"
                                                {{ old('entidad_tipo') == 'comunario' ? 'selected' : '' }}>
                                                Comunario de Apoyo
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
                            <div class="col-md-6">
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

                            <div class="col-md-6">
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

                if (tipo === 'usuario') {
                    $('#usuario-group').show();
                    $('#comunario-group').hide();
                    $('#usuario_select').attr('required', true);
                    $('#comunario_select').removeAttr('required');
                } else if (tipo === 'comunario') {
                    $('#usuario-group').hide();
                    $('#comunario-group').show();
                    $('#comunario_select').attr('required', true);
                    $('#usuario_select').removeAttr('required');
                } else {
                    $('#usuario-group').hide();
                    $('#comunario-group').hide();
                    $('#usuario_select').removeAttr('required');
                    $('#comunario_select').removeAttr('required');
                }
            });

            // Al enviar el formulario, consolidar el ID de la entidad
            $('form').on('submit', function(e) {
                const tipo = $('#entidad_tipo').val();

                if (tipo === 'usuario') {
                    const usuarioId = $('#usuario_select').val();
                    $('#entidad_id_hidden').val(usuarioId);
                } else if (tipo === 'comunario') {
                    const comunarioId = $('#comunario_select').val();
                    $('#entidad_id_hidden').val(comunarioId);
                }
            });
        });
    </script>
@stop
