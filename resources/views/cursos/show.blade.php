@extends('layouts.app')

@section('title', 'Detalle del Curso')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalle del Curso</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Mensajes de éxito/error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-ban"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap"></i> {{ $curso->nombre }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5><strong>Descripción:</strong></h5>
                                <p>{{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha de Inicio:</strong></p>
                                <p>{{ $curso->inicio_programado ? $curso->inicio_programado->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha de Fin:</strong></p>
                                <p>{{ $curso->fin_programado ? $curso->fin_programado->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <p><strong>Total de Asignados:</strong></p>
                                <p>
                                    <span class="badge badge-info" style="font-size: 1.1em;">
                                        <i class="fas fa-users"></i> {{ $asignaciones->total() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('cursos.asignar', $curso->id) }}" class="btn btn-success">
                                    <i class="fas fa-user-plus"></i> Asignar Personas
                                </a>
                                <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @endif
                        @endauth
                        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        {{-- Botón para que el usuario autenticado se inscriba a este curso (confirma mediante modal) --}}
                        @auth
                            @if (empty($authUsuarioAsignado) || !$authUsuarioAsignado)
                                <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                                    data-target="#inscribirmeModal">
                                    <i class="fas fa-sign-in-alt"></i> Inscribirme
                                </button>
                            @else
                                <button class="btn btn-outline-success ml-2" disabled>
                                    <i class="fas fa-check"></i> Ya estás inscrito
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary ml-2">
                                <i class="fas fa-sign-in-alt"></i> Inicia sesión para inscribirte
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Estadísticas</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios</span>
                                <span
                                    class="info-box-number">{{ $asignaciones->where('entidad_tipo', 'usuario')->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-friends"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Comunarios</span>
                                <span
                                    class="info-box-number">{{ $asignaciones->where('entidad_tipo', 'comunario')->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-user-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Inscritos</span>
                                <span
                                    class="info-box-number">{{ $asignaciones->where('entidad_tipo', 'inscrito')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal de confirmación para inscribirse al curso --}}
        @auth
            @if (empty($authUsuarioAsignado) || !$authUsuarioAsignado)
                <div class="modal fade" id="inscribirmeModal" tabindex="-1" role="dialog"
                    aria-labelledby="inscribirmeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inscribirmeModalLabel">Confirmar Inscripción</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>¿Deseas inscribirte al curso <strong>{{ $curso->nombre }}</strong>?</p>
                                <p class="text-muted">Una vez inscrito, podrás ver y gestionar tu participación en la sección de
                                    cursos.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <form method="POST" action="{{ route('cursos.inscribirme', $curso->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Confirmar Inscripción</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        {{-- Etapas del Curso con Progreso --}}
        @include('cursos.partials.stages-with-progress')

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Personas Asignadas</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th>Información Adicional</th>
                                    <th>Fecha Asignación</th>
                                    <th width="100px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($asignaciones as $asignacion)
                                    <tr>
                                        <td>
                                            @if ($asignacion->entidad_tipo === 'usuario')
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-user"></i> Usuario
                                                </span>
                                            @elseif ($asignacion->entidad_tipo === 'comunario')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-friends"></i> Comunario
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-user-tag"></i> Inscrito
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($asignacion->entidad)
                                                @if ($asignacion->entidad_tipo === 'usuario')
                                                    <strong>{{ $asignacion->entidad->nombre }}
                                                        {{ $asignacion->entidad->apellido }}</strong><br>
                                                    <small class="text-muted">CI: {{ $asignacion->entidad->ci }}</small>
                                                @elseif ($asignacion->entidad_tipo === 'comunario')
                                                    <strong>{{ $asignacion->entidad->nombre }}</strong>
                                                @else
                                                    <strong>{{ $asignacion->entidad->nombres }}
                                                        {{ $asignacion->entidad->apellidos }}</strong><br>
                                                    <small class="text-muted">CI: {{ $asignacion->entidad->ci }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">No disponible</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($asignacion->entidad)
                                                @if ($asignacion->entidad_tipo === 'usuario')
                                                    <small class="text-muted">Email:
                                                        {{ $asignacion->entidad->email }}</small>
                                                @elseif ($asignacion->entidad_tipo === 'comunario')
                                                    <small class="text-muted">Edad:
                                                        {{ $asignacion->entidad->edad ?? 'N/A' }}</small>
                                                @else
                                                    <small class="text-muted">Email:
                                                        {{ $asignacion->entidad->correo }}</small><br>
                                                    <small class="text-muted">Tel:
                                                        {{ $asignacion->entidad->telefono ?? 'N/A' }}</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('cursos.remover-asignacion', [$curso->id, $asignacion->id]) }}"
                                                method="POST" class="d-inline form-delete-asignacion">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="text-muted">No hay personas asignadas a este curso.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($asignaciones->hasPages())
                        <div class="card-footer clearfix">
                            {{ $asignaciones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            console.log('Script cargado correctamente');

            // Manejador para botones de eliminar
            $('.form-delete-asignacion').on('submit', function(e) {
                e.preventDefault();
                console.log('Formulario submit interceptado');

                const form = this;

                if (typeof Swal === 'undefined') {
                    if (confirm('¿Está seguro de remover a esta persona del curso?')) {
                        form.submit();
                    }
                    return;
                }

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Esta acción removerá a esta persona del curso",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, remover',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    console.log('SweetAlert resultado:', result);
                    if (result.isConfirmed) {
                        console.log('Enviando formulario...');
                        form.submit();
                    } else {
                        console.log('Cancelado por el usuario');
                    }
                });
            });
        });
    </script>
@stop
