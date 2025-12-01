@extends('layouts.app')

@section('title', 'Detalles del Equipo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $equipo->nombre_equipo }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipos.index') }}">Equipos</a></li>
                    <li class="breadcrumb-item active">{{ $equipo->nombre_equipo }}</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Información General -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i>
                            Información General
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Nombre del Equipo:</dt>
                            <dd class="col-sm-8">{{ $equipo->nombre_equipo }}</dd>

                            <dt class="col-sm-4">Estado:</dt>
                            <dd class="col-sm-8">
                                @if ($equipo->estados_sistema)
                                    <span class="badge"
                                        style="background-color: {{ $equipo->estados_sistema->color ?? '#6c757d' }}; color: white;">
                                        {{ $equipo->estados_sistema->nombre }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Cantidad de Integrantes:</dt>
                            <dd class="col-sm-8">
                                <span class="badge badge-info">{{ $equipo->cantidad_integrantes ?? 0 }}</span>
                            </dd>

                            <dt class="col-sm-4">Líder:</dt>
                            <dd class="col-sm-8">
                                @php
                                    $equipoLider = $equipo->miembros->firstWhere('pivot.es_lider', true);
                                @endphp
                                @if ($equipoLider)
                                    <i class="fas fa-user-tie text-primary"></i>
                                    {{ $equipoLider->nombre }} {{ $equipoLider->apellido }}
                                @else
                                    <span class="text-muted">Sin líder asignado</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Ubicación GPS:</dt>
                            <dd class="col-sm-8">
                                @if ($equipo->latitud && $equipo->longitud)
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    {{ $equipo->latitud }}, {{ $equipo->longitud }}
                                @else
                                    <span class="text-muted">Sin ubicación registrada</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Reporte Asociado:</dt>
                            <dd class="col-sm-8">
                                @if ($equipo->reporte)
                                    <a href="{{ route('reportes.show', $equipo->reporte->id) }}" target="_blank">
                                        {{ $equipo->reporte->nombre_lugar ?? 'Reporte' }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Fecha de Creación:</dt>
                            <dd class="col-sm-8">{{ $equipo->creado ? $equipo->creado->format('d/m/Y H:i') : 'N/A' }}</dd>

                            <dt class="col-sm-4">Última Actualización:</dt>
                            <dd class="col-sm-8">
                                {{ $equipo->actualizado ? $equipo->actualizado->format('d/m/Y H:i') : 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Mapa de Ubicación -->
                @if ($equipo->latitud && $equipo->longitud)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marked-alt mr-1"></i>
                                Ubicación en Mapa
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $markers = [];
                                if ($equipo->latitud && $equipo->longitud) {
                                    $markers[] = [
                                        'lat' => $equipo->latitud,
                                        'lng' => $equipo->longitud,
                                        'popup' => $equipo->nombre_equipo ?? 'Equipo',
                                    ];
                                }
                            @endphp
                            <x-map.leaflet-map mapId="equipo-show-map" lat="{{ $equipo->latitud ?? -17.8 }}"
                                lng="{{ $equipo->longitud ?? -63.1 }}"
                                zoom="{{ $equipo->latitud && $equipo->longitud ? 13 : 6 }}" minZoom="5" maxZoom="18"
                                height="400px" :markers="$markers" />
                        </div>
                    </div>
                @endif
            </div>

            <!-- Estadísticas -->
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Integrantes</span>
                        <span class="info-box-number">{{ $equipo->cantidad_integrantes ?? 0 }}</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks mr-1"></i>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar Equipo
                        </a>
                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                        <form class="btn-block" action="{{ route('equipos.destroy', $equipo->id) }}" method="POST"
                            onsubmit="return confirm('¿Está seguro de eliminar este equipo? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Eliminar Equipo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
