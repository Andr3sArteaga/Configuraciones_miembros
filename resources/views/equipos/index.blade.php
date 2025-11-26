@extends('layouts.app')

@section('title', 'Equipos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Equipos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Equipos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Listado de Equipos
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createTeamModal">
                                <i class="fas fa-plus"></i> Nuevo Equipo
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Integrantes</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipos as $equipo)
                                        <tr>
                                            <td>
                                                <strong>{{ $equipo->nombre_equipo }}</strong>
                                            </td>
                                            <td>
                                                {{ $equipo->cantidad_integrantes ?? 0 }}
                                            </td>
                                            <td>
                                                @if ($equipo->ubicacion)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-map-marker-alt"></i> Ubicación registrada
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Sin ubicación</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($equipo->estados_sistema)
                                                    <span class="badge"
                                                        style="background-color: {{ $equipo->estados_sistema->color ?? '#6c757d' }}; color: white;">
                                                        {{ $equipo->estados_sistema->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('equipos.show', $equipo->id) }}"
                                                    class="btn btn-sm btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('equipos.edit', $equipo->id) }}"
                                                    class="btn btn-sm btn-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST"
                                                    style="display: inline-block;"
                                                    onsubmit="return confirm('¿Está seguro de eliminar este equipo?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                No hay equipos registrados
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($equipos->hasPages())
            <div class="row mt-3">
                <div class="col-md-12">
                    {{ $equipos->links() }}
                </div>
            </div>
        @endif
    </div>

    @include('equipos.partials.create-modal', ['equipo' => null])
@stop
