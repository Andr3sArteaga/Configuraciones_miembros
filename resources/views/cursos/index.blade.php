@extends('layouts.app')

@section('title', 'Cursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cursos</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCursoWizardModal">
                                <i class="fas fa-plus"></i> Nuevo Curso
                            </button>
                        @endif
                    @endauth
                    <button type="button" class="btn btn-secondary" id="toggleViewBtn">
                        <i class="fas fa-table"></i> Vista Tabla
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Mensajes de éxito/error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon fas fa-ban"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Vista de Cards (Diseño de tu compañero) --}}
        <div id="cardsView">
            <div class="row">
                <div class="col-md-12">
                    @forelse($cursos as $curso)
                        <div class="callout callout-warning">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5>
                                        <i class="fas fa-graduation-cap text-warning mr-1"></i>
                                        {{ $curso->nombre }}
                                    </h5>
                                </div>
                                <div class="ml-3">
                                    <span class="badge badge-warning text-white">
                                        <i class="fas fa-users"></i> {{ $curso->cursos_asignados_count ?? 0 }} Asignados
                                    </span>
                                </div>
                            </div>

                            <p class="mb-2">{{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>

                            <small class="text-muted">
                                <i class="far fa-calendar-alt"></i>
                                Creado el {{ $curso->creado ? $curso->creado->format('d/m/Y') : 'N/A' }}
                            </small>

                            <div class="mt-3">
                                <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-warning text-white btn-sm">
                                    <i class="fas fa-info-circle"></i> Ver detalle
                                </a>
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('cursos.asignar', $curso->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-plus"></i> Asignar personas
                                        </a>
                                        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"
                                            style="display: inline-block;" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info-circle"></i> No hay cursos registrados</h5>
                            <p>Haz clic en "Nuevo Curso" para crear el primer curso de capacitación.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($cursos->hasPages())
                <div class="row mt-3">
                    <div class="col-md-12">
                        {{ $cursos->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Vista de Tabla (Mi implementación) --}}
        <div id="tableView" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Cursos</h3>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createCursoWizardModal">
                                    <i class="fas fa-plus"></i> Nuevo Curso
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    <table id="cursos-table" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Asignados</th>
                                <th>Fecha Creación</th>
                                <th width="200px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursos as $curso)
                                <tr>
                                    <td><strong>{{ $curso->nombre }}</strong></td>
                                    <td>{{ Str::limit($curso->descripcion, 60) ?? 'Sin descripción' }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="fas fa-users"></i> {{ $curso->cursos_asignados_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>{{ $curso->creado ? $curso->creado->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-sm btn-primary"
                                            title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @auth
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('cursos.asignar', $curso->id) }}" class="btn btn-sm btn-success"
                                                    title="Asignar">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                                <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-info"
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"
                                                    style="display: inline-block;" class="form-delete-table">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .callout {
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            background-color: #fff;
            border-left: 5px solid #e9ecef;
            margin-bottom: 1rem;
            padding: 1rem;
        }
        .callout.callout-warning {
            border-left-color: #ffc107;
        }
        .callout.callout-info {
            border-left-color: #17a2b8;
        }
        .callout h5 {
            margin-top: 0;
            font-weight: 600;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            let isTableView = false;
            let dataTableInitialized = false;

            // Toggle entre vista de cards y tabla
            $('#toggleViewBtn').on('click', function() {
                isTableView = !isTableView;

                if (isTableView) {
                    $('#cardsView').hide();
                    $('#tableView').show();
                    $(this).html('<i class="fas fa-th-large"></i> Vista Cards');

                    // Inicializar DataTable solo la primera vez
                    if (!dataTableInitialized) {
                        $('#cursos-table').DataTable({
                            responsive: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                            },
                            order: [[3, 'desc']]
                        });
                        dataTableInitialized = true;
                    }
                } else {
                    $('#tableView').hide();
                    $('#cardsView').show();
                    $(this).html('<i class="fas fa-table"></i> Vista Tabla');
                }
            });

            // Confirmación antes de eliminar (vista cards)
            $('.form-delete').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Esta acción eliminará el curso y todas sus asignaciones",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Confirmación antes de eliminar (vista tabla)
            $('.form-delete-table').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Esta acción eliminará el curso y todas sus asignaciones",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @yield('modal-scripts')
@stop

@include('cursos.partials.create-wizard-modal')
@include('cursos.partials.create-modal')
