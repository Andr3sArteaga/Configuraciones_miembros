@extends('layouts.app')

@section('title', 'Focos de Calor')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Focos de Calor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Focos de Calor</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Mapa Principal -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-1"></i>
                            Mapa de Focos de Calor y Equipos
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Time Filter Controls for NASA FIRMS Data -->
                        <div class="p-3 bg-light border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-2 mb-md-0">
                                        <i class="fas fa-satellite mr-1"></i>
                                        Datos NASA FIRMS - Filtro Temporal:
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-sm float-md-right" role="group">
                                        <button type="button" class="btn btn-outline-primary" id="filter-24h"
                                            onclick="loadNASAFirmsData(1)">
                                            <i class="fas fa-clock"></i> Últimas 24 Horas
                                        </button>
                                        <button type="button" class="btn btn-outline-primary active" id="filter-2d"
                                            onclick="loadNASAFirmsData(2)">
                                            <i class="fas fa-calendar-day"></i> Últimos 2 Días
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="filter-7d"
                                            onclick="loadNASAFirmsData(7)">
                                            <i class="fas fa-calendar-week"></i> Últimos 7 Días
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="focos-map" style="height: 600px;"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="d-flex align-items-center flex-wrap">
                                    <i class="fas fa-satellite mr-2" style="color: #FF0000;"></i>
                                    <span class="mr-3">NASA FIRMS</span>
                                    <i class="fas fa-users text-primary mr-2"></i>
                                    <span class="mr-3">Equipos</span>
                                    <i class="fas fa-bullhorn text-warning mr-2"></i>
                                    <span>Reportes</span>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <small class="text-muted">
                                    Última actualización: {{ now()->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Estadísticas -->
        <div class="row">

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Equipos Desplegados</span>
                        <span class="info-box-number" id="equipos-count">
                            {{ $countEquiposDesplegados ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-bullhorn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Reportes en Mapa</span>
                        <span class="info-box-number" id="reportes-count">
                            {{ $reportes->count() ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color: #FF0000;"><i class="fas fa-satellite"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Focos NASA FIRMS</span>
                        <span class="info-box-number" id="nasa-firms-count">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Último Reporte</span>
                        <span class="info-box-number" style="font-size: 14px;">
                            @if ($focos->count() > 0)
                                {{ $focos->first()->acq_date->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-popup-content {
            min-width: 200px;
        }

        .foco-popup h6 {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #dc3545;
        }

        .equipo-popup h6 {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #007bff;
        }

        .reporte-popup h6 {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #ff9800;
        }

        .popup-info {
            font-size: 12px;
        }

        .popup-info strong {
            display: inline-block;
            width: 80px;
        }

        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }

        .legend .circle {
            border-radius: 50%;
        }
    </style>
@stop

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <script>
        let map;
        let focosLayer;
        let equiposLayer;
        let reportesLayer;
        let nasaFirmsLayer; // New layer for NASA FIRMS data

        // NASA FIRMS API configuration
        const NASA_API_KEY = '1ae0346a287432156ada4abb791d57cd';
        const NASA_API_BASE = 'https://firms.modaps.eosdis.nasa.gov/api/area/csv';

        // Bolivia boundaries
        const BOLIVIA_BOUNDS = {
            minLat: -22.9,
            maxLat: -9.7,
            minLng: -69.6,
            maxLng: -57.5
        };

        // Inicializar el mapa
        function initMap() {
            // Coordenadas de Bolivia (centro aproximado)
            map = L.map('focos-map').setView([-16.5, -64.5], 6);

            // Agregar capa base de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            // Crear grupos de marcadores con clustering
            focosLayer = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color: #dc3545; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'marker-cluster',
                        iconSize: L.point(40, 40)
                    });
                }
            });

            equiposLayer = L.layerGroup();
            reportesLayer = L.layerGroup();

            // Create NASA FIRMS layer with clustering
            nasaFirmsLayer = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: '<div style="background-color: #FF0000; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #fff;">' +
                            cluster.getChildCount() + '</div>',
                        className: 'marker-cluster-nasa',
                        iconSize: L.point(40, 40)
                    });
                }
            });

            // Agregar capas al mapa
            // Note: focosLayer is NOT added - we don't show database saved focos
            // Only real-time NASA FIRMS data is displayed
            map.addLayer(equiposLayer);
            map.addLayer(reportesLayer);
            map.addLayer(nasaFirmsLayer); // Add NASA FIRMS layer

            // Agregar leyenda
            addLegend();

            // Cargar datos
            // loadFocosData(); // REMOVED - Don't show database saved focos
            loadEquiposData();
            loadReportesData();
            loadNASAFirmsData(2); // Load NASA FIRMS data for last 2 days by default
        }

        // Note: loadFocosData() function removed - we don't display database saved focos
        // Only real-time NASA FIRMS data is shown on the map

        // Cargar equipos de bomberos
        function loadEquiposData() {
            fetch('/equipos/data/map')
                .then(response => response.json())
                .then(data => {
                    // Actualizar contador
                    document.getElementById('equipos-count').textContent = data.length;

                    data.forEach(equipo => {
                        if (equipo.ubicacion && equipo.ubicacion.coordinates) {
                            const [lng, lat] = equipo.ubicacion.coordinates;

                            // Crear icono de equipo
                            const equipoIcon = L.divIcon({
                                html: '<div style="background-color: #007bff; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.5);"><i class="fas fa-users" style="font-size: 14px;"></i></div>',
                                className: 'equipo-marker',
                                iconSize: [30, 30]
                            });

                            const marker = L.marker([lat, lng], {
                                icon: equipoIcon
                            });

                            // Crear popup
                            const popupContent = `
                                <div class="equipo-popup">
                                    <h6><i class="fas fa-users"></i> ${equipo.nombre_equipo}</h6>
                                    <div class="popup-info">
                                        <p><strong>Integrantes:</strong> ${equipo.cantidad_integrantes}</p>
                                        <p><strong>Estado:</strong> <span class="badge badge-success">${equipo.estado?.nombre || 'Activo'}</span></p>
                                        <p><strong>Coordenadas:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                                    </div>
                                    <div class="mt-2">
                                        <a href="/equipos/${equipo.id}" class="btn btn-sm btn-primary btn-block">
                                            <i class="fas fa-info-circle"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            `;

                            marker.bindPopup(popupContent);
                            equiposLayer.addLayer(marker);
                        }
                    });

                    // Ajustar vista del mapa si hay datos
                    adjustMapBounds();
                })
                .catch(error => {
                    console.error('Error al cargar equipos:', error);
                });
        }

        // Cargar reportes
        function loadReportesData() {
            const reportes = @json($reportes ?? []);

            reportes.forEach(reporte => {
                if (reporte.ubicacion && reporte.ubicacion.coordinates) {
                    const [lng, lat] = reporte.ubicacion.coordinates;

                    // Crear icono de reporte
                    const reporteIcon = L.divIcon({
                        html: '<div style="background-color: #ff9800; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.5);"><i class="fas fa-bullhorn" style="font-size: 12px;"></i></div>',
                        className: 'reporte-marker',
                        iconSize: [30, 30]
                    });

                    const marker = L.marker([lat, lng], {
                        icon: reporteIcon
                    });

                    // Crear popup
                    const fechaHora = reporte.fecha_hora ? new Date(reporte.fecha_hora).toLocaleString('es-BO') :
                        'N/A';
                    const gravedadBadge = reporte.niveles_gravedad ?
                        `<span class="badge badge-danger">${reporte.niveles_gravedad.nombre}</span>` : '';
                    const estadoBadge = reporte.estados_sistema ?
                        `<span class="badge" style="background-color: ${reporte.estados_sistema.color || '#6c757d'}">${reporte.estados_sistema.nombre}</span>` :
                        '';

                    const popupContent = `
                        <div class="reporte-popup">
                            <h6><i class="fas fa-bullhorn"></i> ${reporte.nombre_lugar || 'Reporte sin nombre'}</h6>
                            <div class="popup-info">
                                <p><strong>Fecha/Hora:</strong> ${fechaHora}</p>
                                ${reporte.tipos_incidente ? `<p><strong>Tipo:</strong> ${reporte.tipos_incidente.nombre}</p>` : ''}
                                ${gravedadBadge ? `<p><strong>Gravedad:</strong> ${gravedadBadge}</p>` : ''}
                                ${estadoBadge ? `<p><strong>Estado:</strong> ${estadoBadge}</p>` : ''}
                                ${reporte.nombre_reportante ? `<p><strong>Reportante:</strong> ${reporte.nombre_reportante}</p>` : ''}
                                <p><strong>Coordenadas:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                            </div>
                            <div class="mt-2">
                                <a href="/reportes/${reporte.id}" class="btn btn-sm btn-warning btn-block">
                                    <i class="fas fa-info-circle"></i> Ver Detalles
                                </a>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    reportesLayer.addLayer(marker);
                }
            });

            // Ajustar vista del mapa si hay datos
            adjustMapBounds();
        }

        // Load NASA FIRMS fire hotspot data
        function loadNASAFirmsData(days) {
            // Update button states
            document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
            if (days === 1) {
                document.getElementById('filter-24h').classList.add('active');
            } else if (days === 2) {
                document.getElementById('filter-2d').classList.add('active');
            } else if (days === 7) {
                document.getElementById('filter-7d').classList.add('active');
            }

            // Clear existing NASA FIRMS markers
            nasaFirmsLayer.clearLayers();

            // Build API URL
            const apiUrl = `${NASA_API_BASE}/${NASA_API_KEY}/VIIRS_NOAA21_NRT/world/${days}`;

            // Show loading notification
            $(document).Toasts('create', {
                class: 'bg-info',
                title: 'Capturando Datos',
                autohide: true,
                delay: 3000,
                body: 'Obteniendo datos de NASA FIRMS...'
            });

            console.log('Fetching NASA FIRMS data for last', days, 'days...');

            // Fetch data from NASA FIRMS API
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('NASA FIRMS API request failed');
                    }
                    return response.text();
                })
                .then(csvData => {
                    // Parse CSV data
                    const lines = csvData.trim().split('\n');
                    if (lines.length < 2) {
                        console.log('No fire data available');
                        document.getElementById('nasa-firms-count').textContent = '0';

                        // Show no data notification
                        $(document).Toasts('create', {
                            class: 'bg-warning',
                            title: 'Sin Datos',
                            autohide: true,
                            delay: 4000,
                            body: 'No se detectaron focos de calor en este rango de tiempo'
                        });
                        return;
                    }

                    // Get headers
                    const headers = lines[0].split(',');

                    // Parse data rows
                    let boliviaFireCount = 0;

                    for (let i = 1; i < lines.length; i++) {
                        const values = lines[i].split(',');

                        // Create object from CSV row
                        const fire = {};
                        headers.forEach((header, index) => {
                            fire[header.trim()] = values[index] ? values[index].trim() : '';
                        });

                        // Parse coordinates
                        const lat = parseFloat(fire.latitude);
                        const lng = parseFloat(fire.longitude);

                        // Filter for Bolivia boundaries
                        if (lat >= BOLIVIA_BOUNDS.minLat && lat <= BOLIVIA_BOUNDS.maxLat &&
                            lng >= BOLIVIA_BOUNDS.minLng && lng <= BOLIVIA_BOUNDS.maxLng) {

                            boliviaFireCount++;

                            // Determine confidence level and color
                            let confidenceLevel = '';
                            let confidenceText = '';
                            let markerColor = '';
                            let markerSize = 10;

                            // NASA FIRMS uses 'l', 'n', 'h' for low, nominal, high confidence
                            // Or numeric values
                            const confidence = fire.confidence;

                            if (confidence === 'h' || parseFloat(confidence) >= 80) {
                                confidenceLevel = 'high';
                                confidenceText = 'Foco Alta Confianza';
                                markerColor = '#FF0000'; // Red
                                markerSize = 12;
                            } else if (confidence === 'n' || (parseFloat(confidence) >= 50 && parseFloat(confidence) <
                                    80)) {
                                confidenceLevel = 'nominal';
                                confidenceText = 'Foco Media Confianza';
                                markerColor = '#FFA500'; // Orange
                                markerSize = 10;
                            } else {
                                confidenceLevel = 'low';
                                confidenceText = 'Foco Baja Confianza';
                                markerColor = '#00CED1'; // Turquoise/Cyan
                                markerSize = 8;
                            }

                            // Create marker icon
                            const fireIcon = L.divIcon({
                                html: `<div style="background-color: ${markerColor}; width: ${markerSize}px; height: ${markerSize}px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.6);"></div>`,
                                className: 'nasa-fire-marker',
                                iconSize: [markerSize, markerSize]
                            });

                            // Create marker
                            const marker = L.marker([lat, lng], {
                                icon: fireIcon
                            });

                            // Format date and time
                            const acqDate = fire.acq_date || 'N/A';
                            const acqTime = fire.acq_time || 'N/A';
                            const formattedTime = acqTime !== 'N/A' ? acqTime.substring(0, 2) + ':' + acqTime.substring(
                                2, 4) : 'N/A';

                            // Create popup content
                            const popupContent = `
                                <div class="nasa-fire-popup">
                                    <h6 style="margin: 0 0 10px 0; font-weight: bold; color: ${markerColor};">
                                        <i class="fas fa-satellite"></i> NASA FIRMS - ${confidenceText}
                                    </h6>
                                    <div class="popup-info" style="font-size: 12px;">
                                        <p><strong style="display: inline-block; width: 100px;">Fecha:</strong> ${acqDate}</p>
                                        <p><strong style="display: inline-block; width: 100px;">Hora:</strong> ${formattedTime}</p>
                                        <p><strong style="display: inline-block; width: 100px;">Confianza:</strong> <span class="badge" style="background-color: ${markerColor};">${confidenceText}</span></p>
                                        <p><strong style="display: inline-block; width: 100px;">FRP:</strong> ${fire.frp ? parseFloat(fire.frp).toFixed(2) + ' MW' : 'N/A'}</p>
                                        <p><strong style="display: inline-block; width: 100px;">Brillo (TI4):</strong> ${fire.bright_ti4 ? parseFloat(fire.bright_ti4).toFixed(2) + ' K' : 'N/A'}</p>
                                        <p><strong style="display: inline-block; width: 100px;">Satélite:</strong> ${fire.satellite || 'N/A'}</p>
                                        <p><strong style="display: inline-block; width: 100px;">Coordenadas:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                                    </div>
                                </div>
                            `;

                            marker.bindPopup(popupContent);
                            nasaFirmsLayer.addLayer(marker);
                        }
                    }

                    // Update counter
                    document.getElementById('nasa-firms-count').textContent = boliviaFireCount;

                    console.log(`Loaded ${boliviaFireCount} NASA FIRMS fire hotspots in Bolivia`);

                    // Show success notification
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Focos de Calor Detectados',
                        autohide: true,
                        delay: 4000,
                        body: `Se encontraron ${boliviaFireCount} focos de calor en Bolivia`
                    });

                    // Adjust map view if needed
                    if (boliviaFireCount > 0) {
                        adjustMapBounds();
                    }
                })
                .catch(error => {
                    console.error('Error loading NASA FIRMS data:', error);
                    document.getElementById('nasa-firms-count').textContent = 'Error';

                    // Show error notification
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        autohide: true,
                        delay: 5000,
                        body: 'Error al obtener datos de NASA FIRMS. Por favor, intente nuevamente.'
                    });
                });
        }

        // Ajustar vista del mapa para mostrar todas las capas
        function adjustMapBounds() {
            const allMarkers = [];

            // Collect markers from all layers (excluding focosLayer - database saved focos)
            // focosLayer.eachLayer(marker => allMarkers.push(marker)); // REMOVED
            nasaFirmsLayer.eachLayer(marker => allMarkers.push(marker));
            equiposLayer.eachLayer(marker => allMarkers.push(marker));
            reportesLayer.eachLayer(marker => allMarkers.push(marker));

            // Only adjust bounds if we have markers
            if (allMarkers.length > 0) {
                const group = new L.featureGroup(allMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // Agregar leyenda al mapa
        function addLegend() {
            const legend = L.control({
                position: 'bottomright'
            });

            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'legend');
                div.innerHTML = `
                    <h6 style="margin: 0 0 10px 0; font-weight: bold;">Información</h6>
                    <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #ddd;">
                        <strong style="font-size: 11px;">NASA FIRMS:</strong>
                    </div>
                    <div><i class="circle" style="background: #FF0000;"></i> Alta Confianza (≥80%)</div>
                    <div><i class="circle" style="background: #FFA500;"></i> Media Confianza (50-79%)</div>
                    <div><i class="circle" style="background: #00CED1;"></i> Baja Confianza (<50%)</div>
                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #ddd;">
                        <strong style="font-size: 11px;">Otros:</strong>
                    </div>
                    <div><i class="circle" style="background: #007bff;"></i> Equipo de Bomberos</div>
                    <div><i class="circle" style="background: #ff9800;"></i> Reporte de Incidente</div>
                `;
                return div;
            };

            legend.addTo(map);
        }

        // Centrar mapa en un foco específico
        function centerMapOnFoco(lat, lng) {
            map.setView([lat, lng], 14);

            // Encontrar y abrir el popup del marcador
            focosLayer.eachLayer(function(layer) {
                if (layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
                    layer.openPopup();
                }
            });

            // Scroll al mapa
            document.getElementById('focos-map').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>
@stop
