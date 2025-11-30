@extends('layouts.app')

@section('title', 'Detalles del Reporte')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalles del Reporte</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reportes-incendio.index') }}">Reportes</a></li>
                    <li class="breadcrumb-item active">Detalles</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Información Principal -->
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire mr-1"></i>
                            Información del Incidente
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre del Incidente</label>
                                    <p class="form-control-static"><strong>{{ $reporte->nombre_incidente }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Condición Climática</label>
                                    <p class="form-control-static">
                                        @if ($reporte->condiciones_climatica)
                                            <span class="badge badge-secondary badge-lg">
                                                <i class="fas fa-cloud-sun mr-1"></i>
                                                {{ $reporte->condiciones_climatica->nombre }}
                                                @if($reporte->condiciones_climatica->factor_riesgo)
                                                    (Riesgo: {{ $reporte->condiciones_climatica->factor_riesgo }})
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Extensión</label>
                                    <p class="form-control-static">
                                        @if ($reporte->extension)
                                            <span class="badge badge-info badge-lg">
                                                {{ number_format($reporte->extension, 2) }} hectáreas
                                            </span>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número de Bomberos</label>
                                    <p class="form-control-static">
                                        @if ($reporte->numero_bomberos)
                                            <span class="badge badge-primary badge-lg">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $reporte->numero_bomberos }}
                                            </span>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Necesita Más Bomberos</label>
                                    <p class="form-control-static">
                                        @if ($reporte->necesita_mas_bomberos)
                                            <span class="badge badge-warning badge-lg">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Sí
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado del Incendio</label>
                                    <p class="form-control-static">
                                        @if ($reporte->controlado)
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Controlado
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-fire mr-1"></i>
                                                No Controlado
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($reporte->equipos_en_uso)
                            <div class="form-group">
                                <label>Equipos en Uso</label>
                                <p class="form-control-static">{{ $reporte->equipos_en_uso }}</p>
                            </div>
                        @endif

                        @if ($reporte->apoyo_externo)
                            <div class="form-group">
                                <label>Apoyo Externo</label>
                                <p class="form-control-static">{{ $reporte->apoyo_externo }}</p>
                            </div>
                        @endif

                        @if ($reporte->comentario_adicional)
                            <div class="form-group">
                                <label>Comentarios Adicionales</label>
                                <p class="form-control-static">{{ $reporte->comentario_adicional }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i>
                            Información del Reporte
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label><i class="fas fa-user-circle text-primary mr-1"></i> Reportado por</label>
                            <p class="form-control-static">
                                @if ($reporte->usuario)
                                    {{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}
                                @else
                                    <span class="text-muted">Sistema</span>
                                @endif
                            </p>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt text-success mr-1"></i> Fecha de Creación</label>
                            <p class="form-control-static">
                                {{ $reporte->fecha_creacion?->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $reporte->fecha_creacion?->format('H:i') }}</small>
                            </p>
                        </div>

                        <div class="form-group mb-0">
                            <label><i class="fas fa-fingerprint text-info mr-1"></i> ID del Reporte</label>
                            <p class="form-control-static">
                                <small class="text-muted font-monospace">{{ $reporte->id }}</small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-1"></i>
                            Acciones
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('reportes-incendio.edit', $reporte->id) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit mr-1"></i> Editar Reporte
                        </a>
                        <a href="{{ route('reportes-incendio.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }
        .form-control-static {
            padding-top: 7px;
            padding-bottom: 7px;
            margin-bottom: 0;
            min-height: 34px;
        }
        .font-monospace {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
@stop
