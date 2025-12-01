@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Equipo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipos.index') }}">Equipos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                            <i class="fas fa-users mr-1"></i>
                            {{ $equipo->nombre_equipo }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">Nombre:</dt>
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

                                    <dt class="col-sm-4">Integrantes:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-info">{{ $equipo->cantidad_integrantes ?? 0 }}</span>
                                    </dd>

                                    <dt class="col-sm-4">Ubicación:</dt>
                                    <dd class="col-sm-8">
                                        @if ($equipo->latitud && $equipo->longitud)
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            {{ $equipo->latitud }}, {{ $equipo->longitud }}
                                        @else
                                            <span class="text-muted">Sin ubicación</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal"
                                    data-target="#editTeamModal">
                                    <i class="fas fa-edit"></i> Editar Equipo
                                </button>
                                <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

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
                            <x-map.leaflet-map mapId="equipo-edit-map" lat="{{ $equipo->latitud ?? -17.8 }}"
                                lng="{{ $equipo->longitud ?? -63.1 }}"
                                zoom="{{ $equipo->latitud && $equipo->longitud ? 13 : 6 }}" minZoom="5" maxZoom="18"
                                height="400px" :markers="$markers" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('equipos.partials.create-modal', ['equipo' => $equipo])
@stop

@section('css')
@stop

@section('js')
@stop
