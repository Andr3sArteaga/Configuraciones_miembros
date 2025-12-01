@extends('layouts.app')

@section('title', 'Detalle del Reporte')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detalle del Reporte</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Información General -->
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Información del Reporte
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('reportes.edit', $reporte->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Fecha y Hora:</dt>
                                    <dd class="col-sm-7">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                        {{ $reporte->fecha_hora->format('d/m/Y H:i') }}
                                    </dd>

                                    <dt class="col-sm-5">Reportante:</dt>
                                    <dd class="col-sm-7">
                                        <i class="fas fa-user text-primary"></i>
                                        {{ $reporte->nombre_reportante }}
                                    </dd>

                                    <dt class="col-sm-5">Teléfono:</dt>
                                    <dd class="col-sm-7">
                                        @if ($reporte->telefono_contacto)
                                            <i class="fas fa-phone text-success"></i>
                                            <a
                                                href="tel:{{ $reporte->telefono_contacto }}">{{ $reporte->telefono_contacto }}</a>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-5">Lugar:</dt>
                                    <dd class="col-sm-7">
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        {{ $reporte->nombre_lugar ?? 'Sin especificar' }}
                                    </dd>
                                </dl>
                            </div>

                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Tipo:</dt>
                                    <dd class="col-sm-7">
                                        @if ($reporte->tipos_incidente)
                                            <span class="badge badge-lg"
                                                style="background-color: {{ $reporte->tipos_incidente->color ?? '#6c757d' }}">
                                                @if ($reporte->tipos_incidente->icono)
                                                    <i class="fas fa-{{ $reporte->tipos_incidente->icono }}"></i>
                                                @endif
                                                {{ $reporte->tipos_incidente->nombre }}
                                            </span>
                                        @else
                                            <span class="text-muted">Sin especificar</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-5">Gravedad:</dt>
                                    <dd class="col-sm-7">
                                        @if ($reporte->niveles_gravedad)
                                            <span class="badge badge-lg"
                                                style="background-color: {{ $reporte->niveles_gravedad->color ?? '#ffc107' }}">
                                                {{ $reporte->niveles_gravedad->nombre }}
                                            </span>
                                        @else
                                            <span class="text-muted">No especificada</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-5">Estado:</dt>
                                    <dd class="col-sm-7">
                                        @if ($reporte->estados_sistema)
                                            <span class="badge badge-lg"
                                                style="background-color: {{ $reporte->estados_sistema->color ?? '#6c757d' }}">
                                                {{ $reporte->estados_sistema->nombre }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sin estado</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-5">Creado:</dt>
                                    <dd class="col-sm-7">
                                        <small class="text-muted">
                                            {{ $reporte->creado ? $reporte->creado->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        @if ($reporte->comentario_adicional)
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h5><i class="fas fa-comment-dots"></i> Comentarios:</h5>
                                    <p class="text-muted">{{ $reporte->comentario_adicional }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recursos Solicitados -->
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i> Recursos Solicitados
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-fire-extinguisher"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Bomberos</span>
                                        <span class="info-box-number">{{ $reporte->cant_bomberos }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-ambulance"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Paramédicos</span>
                                        <span class="info-box-number">{{ $reporte->cant_paramedicos }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-paw"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Veterinarios</span>
                                        <span class="info-box-number">{{ $reporte->cant_veterinarios }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-shield-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Autoridades</span>
                                        <span class="info-box-number">{{ $reporte->cant_autoridades }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa de Ubicación -->
            <div class="col-md-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt"></i> Ubicación
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $markers = [];
                            if ($reporte->ubicacion && is_array($reporte->ubicacion)) {
                                $coords = $reporte->ubicacion['coordinates'] ?? null;
                                if ($coords && count($coords) >= 2) {
                                    $markers[] = [
                                        'lat' => $coords[1],
                                        'lng' => $coords[0],
                                        'popup' => $reporte->nombre_lugar ?? 'Ubicación del incidente',
                                    ];
                                }
                            }
                        @endphp
                        <x-map.leaflet-map mapId="reporte-show-map"
                            lat="{{ $reporte->ubicacion ? $reporte->ubicacion['coordinates'][1] : -17.8 }}"
                            lng="{{ $reporte->ubicacion ? $reporte->ubicacion['coordinates'][0] : -63.1 }}"
                            zoom="{{ $reporte->ubicacion ? 13 : 6 }}" minZoom="5" maxZoom="18" height="500px"
                            :markers="$markers" />
                        <p class="text-muted text-center mt-2">
                            <small>Ubicación del incidente reportado</small>
                        </p>
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
    </style>
@stop

@section('js')
@stop
