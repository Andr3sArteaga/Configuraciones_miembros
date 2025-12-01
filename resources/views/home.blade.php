@extends('layouts.app')

@section('title', 'Dashboard - Alas Chiquitanas')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Info boxes --}}
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-fire"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Incendios Activos</span>
                        <span class="info-box-number" id="incendios-activos-count">
                            {{ $incendiosActivos ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-bullhorn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Reportes Pendientes</span>
                        <span class="info-box-number">{{ $reportesPendientes ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-friends"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Equipos Desplegados</span>
                        <span class="info-box-number">{{ $equiposDesplegados ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-boxes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Recursos Solicitados</span>
                        <span class="info-box-number">{{ $recursosSolicitados ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Mapa de Operaciones --}}
            <div class="col-md-8">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-1"></i>
                            Mapa de Operaciones
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Map Filters -->
                    <div class="card-body pb-2 pt-2" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="mb-1" style="font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-satellite text-danger"></i> Focos de Calor (NASA FIRMS)
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="mb-1" style="font-size: 0.85rem;">Días Recientes:</label>
                                            <select id="nasa-days-filter" class="form-control form-control-sm">
                                                <option value="1">Último día</option>
                                                <option value="2" selected>Últimos 2 días</option>
                                                <option value="7">Última semana</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-1" style="font-size: 0.85rem;">Confianza:</label>
                                            <div class="d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input confidence-filter" 
                                                           id="conf-high" value="high" checked>
                                                    <label class="custom-control-label" for="conf-high" style="font-size: 0.85rem;">
                                                        <span class="badge" style="background-color: #FF0000;">Alta</span>
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input confidence-filter" 
                                                           id="conf-medium" value="medium" checked>
                                                    <label class="custom-control-label" for="conf-medium" style="font-size: 0.85rem;">
                                                        <span class="badge" style="background-color: #FFA500;">Media</span>
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input confidence-filter" 
                                                           id="conf-low" value="low" checked>
                                                    <label class="custom-control-label" for="conf-low" style="font-size: 0.85rem;">
                                                        <span class="badge" style="background-color: #00CED1;">Baja</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="mb-1" style="font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-bullhorn text-warning"></i> Reportes
                                    </label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="mb-1" style="font-size: 0.85rem;">Mostrar:</label>
                                            <div class="d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input reporte-type-filter" 
                                                           id="reportes-pendientes" value="pendiente" checked>
                                                    <label class="custom-control-label" for="reportes-pendientes" style="font-size: 0.85rem;">
                                                        Pendientes
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input reporte-type-filter" 
                                                           id="reportes-proceso" value="en_proceso" checked>
                                                    <label class="custom-control-label" for="reportes-proceso" style="font-size: 0.85rem;">
                                                        En Proceso
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input reporte-type-filter" 
                                                           id="reportes-atendidos" value="atendido">
                                                    <label class="custom-control-label" for="reportes-atendidos" style="font-size: 0.85rem;">
                                                        Atendidos
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <x-map.leaflet-map mapId="home-map" lat="-17.8" lng="-63.1" zoom="6" minZoom="5"
                            maxZoom="12" height="400px" />
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-danger"><i class="fas fa-satellite"></i></span>
                                    <h5 class="description-header" id="focos-calor-count">{{ $focosCalor ?? 0 }}</h5>
                                    <span class="description-text">FOCOS DE CALOR</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-warning"><i class="fas fa-bullhorn"></i></span>
                                    <h5 class="description-header">{{ $reportesHoy ?? 0 }}</h5>
                                    <span class="description-text">REPORTES HOY</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success"><i class="fas fa-check"></i></span>
                                    <h5 class="description-header">{{ $incendiosControlados ?? 0 }}</h5>
                                    <span class="description-text">CONTROLADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-info"><i class="fas fa-users"></i></span>
                                    <h5 class="description-header">{{ $bomberosActivos ?? 0 }}</h5>
                                    <span class="description-text">BOMBEROS ACTIVOS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reportes Recientes --}}
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell mr-1"></i>
                            Reportes Recientes
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @forelse($reportesRecientes ?? [] as $reporte)
                                <li class="nav-item">
                                    <a href="{{ route('reportes.show', $reporte->id) }}" class="nav-link">
                                        <i class="fas fa-fire text-danger"></i>
                                        {{ Str::limit($reporte->nombre_lugar ?? 'Sin ubicación', 30) }}
                                        <span
                                            class="float-right text-muted text-sm">{{ $reporte->creado?->diffForHumans() ?? 'Sin fecha' }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="nav-item p-3 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No hay reportes recientes</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-warning">Ver Todos los Reportes</a>
                    </div>
                </div>

                {{-- Noticias Recientes --}}
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-newspaper mr-1"></i>
                            Últimas Noticias
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @forelse($noticiasRecientes ?? [] as $noticia)
                                <li class="nav-item">
                                    <a href="{{ route('noticias.show', $noticia->id) }}" class="nav-link">
                                        <i class="fas fa-newspaper text-info"></i> {{ Str::limit($noticia->title, 30) }}
                                        <span
                                            class="float-right text-muted text-sm">{{ $noticia->date->format('d/m') }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="nav-item p-3 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No hay noticias</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('noticias.index') }}" class="btn btn-sm btn-info">Ver Todas las Noticias</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos de Estadísticas --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Incendios por Mes
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="incendiosPorMes" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Tipos de Incidente
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="tiposIncidente" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Equipos y Estado --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">
                            <i class="fas fa-user-friends mr-1"></i>
                            Estado de Equipos
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Equipo</th>
                                        <th>Integrantes</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipos ?? [] as $equipo)
                                        <tr>
                                            <td><a
                                                    href="{{ route('equipos.show', $equipo->id) }}">{{ $equipo->nombre_equipo }}</a>
                                            </td>
                                            <td>{{ $equipo->cantidad_integrantes }}</td>
                                            <td>
                                                @if ($equipo->latitud && $equipo->longitud)
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
                                                        style="background-color: {{ $equipo->estados_sistema->color ?? '#6c757d' }}">
                                                        {{ $equipo->estados_sistema->nombre }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Sin estado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('equipos.show', $equipo->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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
                    <div class="card-footer clearfix">
                        <a href="{{ route('equipos.create') }}" class="btn btn-sm btn-info float-left">
                            <i class="fas fa-plus"></i> Nuevo Equipo
                        </a>
                        <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-secondary float-right">
                            Ver Todos los Equipos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    
    <style>
        .info-box-number {
            font-size: 2rem;
            font-weight: 700;
        }
    </style>
@stop

@section('js')
    <!-- Leaflet MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    
    <script>
        // Esperar a que el componente mapa esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for all libraries to load
            setTimeout(function() {
                const map = window.mapInstance_home_map;
                
                if (!map) {
                    console.error('Map instance not found');
                    return;
                }

            // Inicializar capas / capas por tipo
            const focosLayer = L.layerGroup();
            const equiposLayer = L.layerGroup();
            const reportesLayer = L.layerGroup();

            // NASA FIRMS layer with clustering
            const nasaFirmsLayer = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color: #FF0000; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #fff;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'marker-cluster-nasa',
                        iconSize: L.point(40, 40)
                    });
                }
            });

            // NASA FIRMS API configuration
            const NASA_API_KEY = '1ae0346a287432156ada4abb791d57cd';
            const NASA_API_BASE = 'https://firms.modaps.eosdis.nasa.gov/api/area/csv';
            const BOLIVIA_BOUNDS = {
                minLat: -22.9,
                maxLat: -9.7,
                minLng: -69.6,
                maxLng: -57.5
            };

            // Añadir control de capas para poder alternar
            const overlays = {
                'Focos NASA FIRMS': nasaFirmsLayer,
                'Equipos': equiposLayer,
                'Reportes': reportesLayer
            };
            L.control.layers(null, overlays, {
                collapsed: false
            }).addTo(map);

            // Note: Database saved focos are NOT shown on the map
            // Only real-time NASA FIRMS data (circles) are displayed
            // The saved focos are for historical reference only

            // Agregar marcadores de equipos (desde variable $equipos)
            @if (isset($equipos) && count($equipos) > 0)
                @foreach ($equipos as $equipo)
                    @if ($equipo->latitud && $equipo->longitud)
                        (function() {
                            const lng = {{ $equipo->longitud }};
                            const lat = {{ $equipo->latitud }};

                            const equipoIcon = L.divIcon({
                                html: `<div style="background-color: #007bff; color: white; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;"><i class="fas fa-users" style="font-size: 14px;"></i></div>`,
                                className: 'equipo-marker',
                                iconSize: [34, 34]
                            });

                            const equipoNombre = {!! json_encode($equipo->nombre_equipo ?? 'Equipo') !!};
                            const equipoIntegrantes = {{ $equipo->cantidad_integrantes ?? 0 }};
                            const equipoUrl = {!! json_encode(route('equipos.show', $equipo->id)) !!};
                            const popupHtml = `<div class="equipo-popup"><strong>${equipoNombre}</strong><br/>Integrantes: ${equipoIntegrantes}<br/><a href="${equipoUrl}" target="_blank">Ver equipo</a></div>`;
                            const marker = L.marker([lat, lng], {
                                icon: equipoIcon
                            }).bindPopup(popupHtml);
                            equiposLayer.addLayer(marker);
                        })();
                    @endif
                @endforeach
            @endif

            // Agregar marcadores de reportes (disponibles)
            @if (isset($reportesDisponibles) && count($reportesDisponibles) > 0)
                @foreach ($reportesDisponibles as $reporte)
                    @if ($reporte->ubicacion && isset($reporte->ubicacion['coordinates']))
                        (function() {
                            const lng = {{ $reporte->ubicacion['coordinates'][0] }};
                            const lat = {{ $reporte->ubicacion['coordinates'][1] }};

                            const reporteIcon = L.divIcon({
                                html: `<div style="background-color: #ff9800; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;"><i class="fas fa-bullhorn" style="font-size: 12px;"></i></div>`,
                                className: 'reporte-marker',
                                iconSize: [30, 30]
                            });

                            const reporteNombre = {!! json_encode($reporte->nombre_lugar ?? 'Reporte') !!};
                            const reporteFecha = {!! json_encode(optional($reporte->fecha_hora)->format('d/m/Y H:i') ?? 'N/A') !!};
                            const reporteUrl = {!! json_encode(route('reportes.show', $reporte->id)) !!};
                            const popupHtml = `<div class="reporte-popup"><strong>${reporteNombre}</strong><br/>Fecha: ${reporteFecha}<br/><a href="${reporteUrl}" target="_blank">Ver reporte</a></div>`;
                            const marker = L.marker([lat, lng], {
                                icon: reporteIcon
                            }).bindPopup(popupHtml);
                            reportesLayer.addLayer(marker);
                        })();
                    @endif
                @endforeach
            @endif

            // Añadir capas al mapa para visualizarlas inicialmente
            map.addLayer(equiposLayer);
            map.addLayer(reportesLayer);
            map.addLayer(nasaFirmsLayer); // Add NASA FIRMS layer by default
            
            // Load NASA FIRMS data automatically on page load
            loadNASAFirmsData(2);

            // Load NASA FIRMS data function
            function loadNASAFirmsData(days = 2) {
                // Clear existing markers
                nasaFirmsLayer.clearLayers();

                const apiUrl = `${NASA_API_BASE}/${NASA_API_KEY}/VIIRS_NOAA21_NRT/world/${days}`;

                console.log('Fetching NASA FIRMS data...');

                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) throw new Error('NASA FIRMS API failed');
                        return response.text();
                    })
                    .then(csvData => {
                        const lines = csvData.trim().split('\n');
                        if (lines.length < 2) {
                            console.log('No NASA FIRMS data available');
                            return;
                        }

                        const headers = lines[0].split(',');
                        let boliviaFireCount = 0;

                        for (let i = 1; i < lines.length; i++) {
                            const values = lines[i].split(',');
                            const fire = {};
                            headers.forEach((header, index) => {
                                fire[header.trim()] = values[index] ? values[index].trim() : '';
                            });

                            const lat = parseFloat(fire.latitude);
                            const lng = parseFloat(fire.longitude);

                            // Filter for Bolivia
                            if (lat >= BOLIVIA_BOUNDS.minLat && lat <= BOLIVIA_BOUNDS.maxLat &&
                                lng >= BOLIVIA_BOUNDS.minLng && lng <= BOLIVIA_BOUNDS.maxLng) {

                                boliviaFireCount++;

                                // Determine color and size based on confidence
                                let markerColor, markerSize, confidenceText;
                                const confidence = fire.confidence;

                                if (confidence === 'h' || parseFloat(confidence) >= 80) {
                                    confidenceText = 'Alta Confianza';
                                    markerColor = '#FF0000';
                                    markerSize = 12;
                                } else if (confidence === 'n' || (parseFloat(confidence) >= 50 && parseFloat(
                                            confidence) <
                                        80)) {
                                    confidenceText = 'Media Confianza';
                                    markerColor = '#FFA500';
                                    markerSize = 10;
                                } else {
                                    confidenceText = 'Baja Confianza';
                                    markerColor = '#00CED1';
                                    markerSize = 8;
                                }

                                const fireIcon = L.divIcon({
                                    html: `<div style="background-color: ${markerColor}; width: ${markerSize}px; height: ${markerSize}px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.6);"></div>`,
                                    className: 'nasa-fire-marker',
                                    iconSize: [markerSize, markerSize]
                                });

                                // Determine confidence level for filtering
                                let confidenceLevel;
                                if (confidence === 'h' || parseFloat(confidence) >= 80) {
                                    confidenceLevel = 'high';
                                } else if (confidence === 'n' || (parseFloat(confidence) >= 50 && parseFloat(confidence) < 80)) {
                                    confidenceLevel = 'medium';
                                } else {
                                    confidenceLevel = 'low';
                                }

                                const marker = L.marker([lat, lng], {
                                    icon: fireIcon
                                });
                                marker.confidenceLevel = confidenceLevel; // Store for filtering

                                const acqDate = fire.acq_date || 'N/A';
                                const acqTime = fire.acq_time || 'N/A';
                                const formattedTime = acqTime !== 'N/A' ? acqTime.substring(0, 2) + ':' +
                                    acqTime.substring(
                                        2, 4) : 'N/A';

                                const popupContent = `
                                <div>
                                    <h6 style="margin: 0 0 10px 0; font-weight: bold; color: ${markerColor};">
                                        <i class="fas fa-satellite"></i> NASA FIRMS - ${confidenceText}
                                    </h6>
                                    <div style="font-size: 12px;">
                                        <p><strong>Fecha:</strong> ${acqDate}</p>
                                        <p><strong>Hora:</strong> ${formattedTime}</p>
                                        <p><strong>FRP:</strong> ${fire.frp ? parseFloat(fire.frp).toFixed(2) + ' MW' : 'N/A'}</p>
                                        <p><strong>Coordenadas:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                                    </div>
                                </div>
                            `;

                                marker.bindPopup(popupContent);
                                nasaFirmsLayer.addLayer(marker);
                            }
                        }

                        console.log(`Loaded ${boliviaFireCount} NASA FIRMS hotspots`);

                        // Update FOCOS DE CALOR counter in the UI
                        document.getElementById('focos-calor-count').textContent = boliviaFireCount;
                    })
                    .catch(error => {
                        console.error('Error loading NASA FIRMS:', error);
                    });
            }

            // Listen for layer add/remove events
            map.on('overlayadd', function(e) {
                if (e.name === 'Focos NASA FIRMS') {
                    const days = parseInt(document.getElementById('nasa-days-filter').value) || 2;
                    loadNASAFirmsData(days);
                }
            });

            // Event listeners for NASA FIRMS filters
            document.getElementById('nasa-days-filter').addEventListener('change', function() {
                if (map.hasLayer(nasaFirmsLayer)) {
                    loadNASAFirmsData(parseInt(this.value));
                }
            });

            // Event listeners for confidence filters
            document.querySelectorAll('.confidence-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    filterNASAMarkers();
                });
            });

            // Function to filter NASA FIRMS markers by confidence
            function filterNASAMarkers() {
                const selectedConfidences = Array.from(document.querySelectorAll('.confidence-filter:checked'))
                    .map(cb => cb.value);
                
                nasaFirmsLayer.eachLayer(marker => {
                    if (selectedConfidences.includes(marker.confidenceLevel)) {
                        marker.setOpacity(1);
                    } else {
                        marker.setOpacity(0);
                    }
                });
            }

            // Event listeners for reporte type filters
            document.querySelectorAll('.reporte-type-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    filterReporteMarkers();
                });
            });

            // Function to filter reporte markers
            function filterReporteMarkers() {
                // This would need the estado_codigo stored in each marker
                // For now, we'll just show/hide all reportes based on if any filter is checked
                const anyChecked = document.querySelectorAll('.reporte-type-filter:checked').length > 0;
                
                if (anyChecked) {
                    map.addLayer(reportesLayer);
                } else {
                    map.removeLayer(reportesLayer);
                }
            }

            // Ajustar vista del mapa si hay datos
            (function adjustBounds() {
                const layers = [focosLayer, equiposLayer, reportesLayer, nasaFirmsLayer];
                const bounds = L.latLngBounds();
                let hasBounds = false;
                layers.forEach(layer => {
                    if (layer.getLayers && layer.getLayers().length > 0) {
                        layer.eachLayer(marker => {
                            if (marker.getLatLng) {
                                bounds.extend(marker.getLatLng());
                                hasBounds = true;
                            }
                        });
                    }
                });
                if (hasBounds) {
                    map.fitBounds(bounds.pad(0.1));
                }
            })();

            // Gráfico de Incendios por Mes
            const ctxIncendios = document.getElementById('incendiosPorMes').getContext('2d');
            new Chart(ctxIncendios, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        $mesesLabels ?? ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    ) !!},
                    datasets: [{
                        label: 'Incendios Reportados',
                        data: {!! json_encode($incendiosPorMesData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Gráfico de Tipos de Incidente
            const ctxTipos = document.getElementById('tiposIncidente').getContext('2d');
            new Chart(ctxTipos, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($tiposLabels ?? ['Incendio Forestal', 'Incendio Urbano', 'Rescate', 'Otro']) !!},
                    datasets: [{
                        data: {!! json_encode($tiposData ?? [65, 20, 10, 5]) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
            }, 300); // Close setTimeout
        }); // Close DOMContentLoaded
    </script>
@stop
