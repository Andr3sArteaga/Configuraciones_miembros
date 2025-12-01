@extends('layouts.app')

@section('title', 'Mi Equipo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mi Equipo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mi Equipo</li>
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
                            <i class="fas fa-users mr-2"></i>{{ $equipo->nombre_equipo }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Información del Equipo -->
                            <div class="col-md-6">
                                <h5><i class="fas fa-info-circle text-primary"></i> Información del Equipo</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 150px;">Nombre:</th>
                                        <td>{{ $equipo->nombre_equipo }}</td>
                                    </tr>
                                    <tr>
                                        <th>Integrantes:</th>
                                        <td>{{ $equipo->cantidad_integrantes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td>
                                            @if($equipo->estados_sistema)
                                                <span class="badge" style="background-color: {{ $equipo->estados_sistema->color ?? '#6c757d' }}">
                                                    {{ $equipo->estados_sistema->nombre }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Sin estado</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($equipo->ubicacion)
                                        <tr>
                                            <th>Ubicación:</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    <i class="fas fa-map-marker-alt"></i> Asignada
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- Reporte Asignado -->
                            <div class="col-md-6">
                                <h5><i class="fas fa-bullhorn text-warning"></i> Reporte Asignado</h5>
                                @if($equipo->reporte)
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $equipo->reporte->nombre_lugar ?? 'Sin nombre' }}</h6>
                                            <p class="card-text">
                                                <strong>Fecha:</strong> {{ $equipo->reporte->fecha_hora->format('d/m/Y H:i') }}<br>
                                                <strong>Reportante:</strong> {{ $equipo->reporte->nombre_reportante }}
                                            </p>
                                            <a href="{{ route('reportes.show', $equipo->reporte->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i> Ver Reporte
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No hay reporte asignado actualmente.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Miembros del Equipo -->
                        <h5><i class="fas fa-user-friends text-success"></i> Miembros del Equipo</h5>
                        <div class="row">
                            @forelse($equipo->miembros as $usuario)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-user text-primary"></i>
                                                {{ $usuario->nombre }} {{ $usuario->apellido }}
                                            </h6>
                                            <p class="card-text small">
                                                @if($usuario->niveles_entrenamiento)
                                                    <span class="badge badge-info">
                                                        {{ $usuario->niveles_entrenamiento->nombre }}
                                                    </span>
                                                @endif
                                                @if($usuario->role)
                                                    <span class="badge badge-secondary">
                                                        {{ $usuario->role->nombre }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> No hay miembros asignados a este equipo.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
