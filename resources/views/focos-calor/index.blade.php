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
                        <div id="focos-map" style="height: 600px;"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-fire text-danger mr-2"></i>
                                    <span class="mr-3">Focos de Calor</span>
                                    <i class="fas fa-users text-primary mr-2"></i>
                                    <span class="mr-3">Equipos</span>
                                    <i class="fas fa-bullhorn text-warning mr-2"></i>
                                    <span>Reportes</span>
                                </div>
                            </div>
                            <div class="col-sm-6 text-right">
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
                    <span class="info-box-icon bg-danger"><i class="fas fa-fire"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Focos Activos</span>
                        <span class="info-box-number">{{ $focos->total() }}</span>
                    </div>
                </div>
            </div>
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
                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Alta Confianza</span>
                        <span class="info-box-number" id="high-confidence-count">0</span>
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

        <!-- Tabla de Datos -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire mr-1"></i>
                            Listado de Focos de Calor
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Latitud</th>
                                        <th>Longitud</th>
                                        <th>Confianza</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>FRP</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($focos as $foco)
                                        <tr>
                                            <td>{{ number_format($foco->latitude, 6) }}</td>
                                            <td>{{ number_format($foco->longitude, 6) }}</td>
                                            <td>
                                                @if ($foco->confidence >= 80)
                                                    <span class="badge badge-danger">{{ $foco->confidence }}%</span>
                                                @elseif($foco->confidence >= 50)
                                                    <span class="badge badge-warning">{{ $foco->confidence }}%</span>
                                                @else
                                                    <span class="badge badge-info">{{ $foco->confidence }}%</span>
                                                @endif
                                            </td>
                                            <td>{{ $foco->acq_date->format('d/m/Y') }}</td>
                                            <td>{{ $foco->acq_time }}</td>
                                            <td>{{ $foco->frp ? number_format($foco->frp, 2) . ' MW' : 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-xs btn-info"
                                                    onclick="centerMapOnFoco({{ $foco->latitude }}, {{ $foco->longitude }})">
                                                    <i class="fas fa-map-marker-alt"></i> Ver en Mapa
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                No hay focos de calor registrados
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

        @if ($focos->hasPages())
            <div class="row mt-3">
                <div class="col-md-12">
                    {{ $focos->links() }}
                </div>
            </div>
        @endif
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

            // Agregar capas al mapa
            map.addLayer(focosLayer);
            map.addLayer(equiposLayer);
            map.addLayer(reportesLayer);

            // Agregar leyenda
            addLegend();

            // Cargar datos
            loadFocosData();
            loadEquiposData();
            loadReportesData();
        }

        // Cargar focos de calor
        function loadFocosData() {
            const focos = @json($focos->items());
            let highConfidenceCount = 0;

            focos.forEach(foco => {
                if (foco.confidence >= 80) {
                    highConfidenceCount++;
                }

                // Crear icono según nivel de confianza
                let iconColor = '#dc3545'; // Rojo por defecto
                let iconSize = 10;

                if (foco.confidence >= 80) {
                    iconColor = '#dc3545'; // Rojo
                    iconSize = 12;
                } else if (foco.confidence >= 50) {
                    iconColor = '#ffc107'; // Amarillo
                    iconSize = 10;
                } else {
                    iconColor = '#17a2b8'; // Azul claro
                    iconSize = 8;
                }

                const focoIcon = L.divIcon({
                    html: `<div style="background-color: ${iconColor}; width: ${iconSize}px; height: ${iconSize}px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>`,
                    className: 'foco-marker',
                    iconSize: [iconSize, iconSize]
                });

                const marker = L.marker([foco.latitude, foco.longitude], {
                    icon: focoIcon
                });

                // Crear popup
                const popupContent = `
                    <div class="foco-popup">
                        <h6><i class="fas fa-fire"></i> Foco de Calor</h6>
                        <div class="popup-info">
                            <p><strong>Coordenadas:</strong> ${foco.latitude.toFixed(6)}, ${foco.longitude.toFixed(6)}</p>
                            <p><strong>Confianza:</strong> <span class="badge badge-${foco.confidence >= 80 ? 'danger' : (foco.confidence >= 50 ? 'warning' : 'info')}">${foco.confidence}%</span></p>
                            <p><strong>Fecha:</strong> ${new Date(foco.acq_date).toLocaleDateString('es-BO')}</p>
                            <p><strong>Hora:</strong> ${foco.acq_time || 'N/A'}</p>
                            ${foco.frp ? `<p><strong>FRP:</strong> ${parseFloat(foco.frp).toFixed(2)} MW</p>` : ''}
                            ${foco.bright_ti4 ? `<p><strong>Brillo TI4:</strong> ${parseFloat(foco.bright_ti4).toFixed(2)}K</p>` : ''}
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                focosLayer.addLayer(marker);
            });

            // Actualizar contador de alta confianza
            document.getElementById('high-confidence-count').textContent = highConfidenceCount;
        }

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
                    const fechaHora = reporte.fecha_hora ? new Date(reporte.fecha_hora).toLocaleString('es-BO') : 'N/A';
                    const gravedadBadge = reporte.niveles_gravedad ? `<span class="badge badge-danger">${reporte.niveles_gravedad.nombre}</span>` : '';
                    const estadoBadge = reporte.estados_sistema ? `<span class="badge" style="background-color: ${reporte.estados_sistema.color || '#6c757d'}">${reporte.estados_sistema.nombre}</span>` : '';

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

        // Ajustar vista del mapa para mostrar todas las capas
        function adjustMapBounds() {
            if (focosLayer.getLayers().length > 0 || equiposLayer.getLayers().length > 0 || reportesLayer.getLayers().length > 0) {
                const group = new L.featureGroup([focosLayer, equiposLayer, reportesLayer]);
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
                    <h6 style="margin: 0 0 10px 0; font-weight: bold;">Leyenda</h6>
                    <div><i class="circle" style="background: #dc3545;"></i> Foco Alta Confianza (≥80%)</div>
                    <div><i class="circle" style="background: #ffc107;"></i> Foco Media Confianza (50-79%)</div>
                    <div><i class="circle" style="background: #17a2b8;"></i> Foco Baja Confianza (<50%)</div>
                    <div style="margin-top: 5px;"><i class="circle" style="background: #007bff;"></i> Equipo de Bomberos</div>
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
