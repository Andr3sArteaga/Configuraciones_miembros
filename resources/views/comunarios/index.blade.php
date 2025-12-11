@extends('adminlte::page')

@section('title', 'Comunarios de Apoyo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Comunarios de Apoyo</h1>
        <a href="{{ route('comunarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Comunario
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Comunarios</h3>
        </div>
        <div class="card-body">
            @if ($comunarios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Edad</th>
                                <th>Entidad</th>
                                <th>Equipo</th>
                                <th>Fecha Registro</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comunarios as $comunario)
                                <tr>
                                    <td>{{ $comunario->nombre }}</td>
                                    <td>{{ $comunario->edad }} años</td>
                                    <td>{{ $comunario->entidad_perteneciente ?? 'N/A' }}</td>
                                    <td>
                                        @if ($comunario->equipo)
                                            <a href="{{ route('equipos.show', $comunario->equipo->id) }}">
                                                {{ $comunario->equipo->nombre_equipo }}
                                            </a>
                                        @else
                                            <span class="text-muted">Sin equipo</span>
                                        @endif
                                    </td>
                                    <td>{{ $comunario->creado ? $comunario->creado->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('comunarios.show', $comunario->id) }}"
                                                class="btn btn-info btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('comunarios.edit', $comunario->id) }}"
                                                class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('comunarios.destroy', $comunario->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar este comunario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $comunarios->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay comunarios de apoyo registrados.
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin: 0;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@stop
