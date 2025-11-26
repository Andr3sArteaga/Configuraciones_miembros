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
                                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#editTeamModal">
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
                            <div id="equipo-map" style="height: 400px;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('equipos.partials.create-modal')
@stop

@section('css')
    @if ($equipo->latitud && $equipo->longitud)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endif
@stop

@section('js')
    @if ($equipo->latitud && $equipo->longitud)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lat = {{ $equipo->latitud }};
                const lng = {{ $equipo->longitud }};

                // Create the map
                const map = L.map('equipo-map').setView([lat, lng], 13);

                // Add base layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 18
                }).addTo(map);

                // Add team marker
                const equipoIcon = L.divIcon({
                    html: '<div style="background-color: #007bff; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.5);"><i class="fas fa-users" style="font-size: 20px;"></i></div>',
                    className: 'equipo-marker',
                    iconSize: [40, 40]
                });

                const marker = L.marker([lat, lng], {
                    icon: equipoIcon
                }).addTo(map);

                marker.bindPopup(`
                <div class="equipo-popup">
                    <h6><i class="fas fa-users"></i> {{ $equipo->nombre_equipo }}</h6>
                    <p><strong>Integrantes:</strong> {{ $equipo->cantidad_integrantes ?? 0 }}</p>
                    <p><strong>Estado:</strong> {{ $equipo->estados_sistema->nombre ?? 'N/A' }}</p>
                </div>
            `).openPopup();
            });
        </script>
    @endif
@stop
