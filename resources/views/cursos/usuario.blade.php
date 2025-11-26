@extends('layouts.app')

@section('title', 'Cursos del Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Cursos del Usuario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active">Cursos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> {{ $usuario->nombre }} {{ $usuario->apellido }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>CI:</strong> {{ $usuario->ci }}</p>
                                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'N/A' }}</p>
                                <p><strong>Total de Cursos:</strong>
                                    <span class="badge badge-info">{{ $cursosAsignados->total() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cursos Asignados</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Descripción</th>
                                    <th>Fecha Asignación</th>
                                    <th width="150px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cursosAsignados as $asignacion)
                                    <tr>
                                        <td><strong>{{ $asignacion->curso->nombre ?? 'N/A' }}</strong></td>
                                        <td>{{ Str::limit($asignacion->curso->descripcion ?? 'Sin descripción', 80) }}
                                        </td>
                                        <td>{{ $asignacion->fecha_asignacion ? $asignacion->fecha_asignacion->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($asignacion->curso)
                                                <a href="{{ route('cursos.show', $asignacion->curso->id) }}"
                                                    class="btn btn-sm btn-primary" title="Ver curso">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <p class="text-muted">Este usuario no tiene cursos asignados.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($cursosAsignados->hasPages())
                        <div class="card-footer clearfix">
                            {{ $cursosAsignados->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Usuario
                </a>
                <a href="{{ route('cursos.index') }}" class="btn btn-info">
                    <i class="fas fa-graduation-cap"></i> Ver Todos los Cursos
                </a>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
