@extends('layouts.app')

@section('title', 'Recursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Recursos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Recursos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (!$apiAvailable && $errorMessage)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Servicio de inventario no disponible:</strong> {{ $errorMessage }}. Mostrando suministros de emergencia.
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
                            <i class="fas fa-toolbox mr-1"></i>
                            Listado de Recursos del Inventario
                        </h3>
                        @if ($apiAvailable)
                            <span class="badge badge-success float-right">
                                <i class="fas fa-check-circle"></i> API Conectada
                            </span>
                        @else
                            <span class="badge badge-warning float-right">
                                <i class="fas fa-exclamation-triangle"></i> Modo Emergencia
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Stock Disponible</th>
                                        <th>Unidad de Medida</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recursos as $recurso)
                                        <tr>
                                            <td>
                                                <strong>{{ $recurso['nombre'] }}</strong>
                                            </td>
                                            <td>
                                                @if (!empty($recurso['descripcion']))
                                                    <small class="text-muted">{{ $recurso['descripcion'] }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($recurso['stock_total'] > 0)
                                                    <span class="badge badge-success">
                                                        {{ $recurso['stock_total'] }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        Sin stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $recurso['unidad_medida'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($recurso['stock_total'] > 10)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Disponible
                                                    </span>
                                                @elseif ($recurso['stock_total'] > 0)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-exclamation"></i> Stock Bajo
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> Agotado
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                <i class="fas fa-inbox"></i> No hay recursos disponibles
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($recursos) > 0)
                        <div class="card-footer">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Mostrando {{ count($recursos) }} producto(s) del inventario
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
