@extends('layouts.app')

@section('title', 'Reportes de Incendios')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte de usuario</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Mensajes de éxito/error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire mr-1"></i>
                            Listado de Reportes
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('reportes-incendio.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Nuevo Reporte
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nombre Incidente</th>
                                        <th>Extensión (ha)</th>
                                        <th>Condición Climática</th>
                                        <th class="text-center">N° Bomberos</th>
                                        <th class="text-center">Necesita Apoyo</th>
                                        <th class="text-center">Controlado</th>
                                        <th>Reportado por</th>
                                        <th>Fecha Creación</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportes as $reporte)
                                        <tr>
                                            <td>
                                                <strong>{{ $reporte->nombre_incidente }}</strong>
                                            </td>
                                            <td>
                                                @if ($reporte->extension)
                                                    <span class="badge badge-info">
                                                        {{ number_format($reporte->extension, 2) }} ha
                                                    </span>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($reporte->condiciones_climatica)
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-cloud-sun mr-1"></i>
                                                        {{ $reporte->condiciones_climatica->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->numero_bomberos)
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-users mr-1"></i>
                                                        {{ $reporte->numero_bomberos }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->necesita_mas_bomberos)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Sí
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->controlado)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Sí
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-fire mr-1"></i>
                                                        No
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($reporte->usuario)
                                                    <i class="fas fa-user-circle mr-1"></i>
                                                    {{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}
                                                @else
                                                    <span class="text-muted">Sistema</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ $reporte->fecha_creacion?->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $reporte->fecha_creacion?->format('H:i') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('reportes-incendio.show', $reporte->id) }}" 
                                                       class="btn btn-info" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('reportes-incendio.edit', $reporte->id) }}" 
                                                       class="btn btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('reportes-incendio.destroy', $reporte->id) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este reporte?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No hay reportes de incendios registrados
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

        @if ($reportes->hasPages())
            <div class="row mt-3">
                <div class="col-md-12">
                    {{ $reportes->links() }}
                </div>
            </div>
        @endif
    </div>
@stop
