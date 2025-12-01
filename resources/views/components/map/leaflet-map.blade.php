@props([
    'mapId' => 'map',
    'lat' => -17.8,
    'lng' => -63.1,
    'zoom' => 6,
    'minZoom' => 5,
    'maxZoom' => 18,
    'height' => '400px',
    'draggable' => false,
    'onMapClick' => null,
    'markers' => [],
])

<div id="{{ $mapId }}"
    style="height: {{ $height }}; width: 100%; border-radius: 0.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
</div>

<script>
    @php
        // Create a safe function name from mapId (replace hyphens with underscores)
        $safeFuncName = 'initLeafletMap_' . str_replace('-', '_', $mapId);
    @endphp

    document.addEventListener('DOMContentLoaded', function() {
        // Pequeño delay para asegurar que el DOM esté completamente renderizado
        setTimeout(function() {
            {{ $safeFuncName }}();
        }, 50);
    });

    function {{ $safeFuncName }}() {
        // Definir límites de Bolivia
        const boliviaBounds = [
            [-23.1, -57.5], // Sureste (latitud min, longitud max)
            [-9.8, -69.6] // Noroeste (latitud max, longitud min)
        ];

        // Crear el mapa con configuración de límites
        const map = L.map('{{ $mapId }}', {
            minZoom: {{ $minZoom }},
            maxZoom: {{ $maxZoom }}
        }).setView([{{ $lat }}, {{ $lng }}], {{ $zoom }});

        // Establecer límites máximos para prevenir pan fuera de Bolivia
        map.setMaxBounds(boliviaBounds);

        // Limitar el pan de forma suave (no abrupto)
        map.on('drag', function() {
            map.panInsideBounds(boliviaBounds, {
                animate: false
            });
        });

        // Agregar capa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        @if ($draggable)
            let marker = null;

            // Crear marcador inicial si hay coordenadas
            @if ($lat && $lng)
                marker = L.marker([{{ $lat }}, {{ $lng }}], {
                    draggable: true
                }).addTo(map);

                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    // Disparar evento personalizado con las nuevas coordenadas
                    window.dispatchEvent(new CustomEvent('markerDragEnd', {
                        detail: {
                            lat: position.lat,
                            lng: position.lng,
                            mapId: '{{ $mapId }}'
                        }
                    }));
                });
            @endif

            // Permitir click en el mapa para agregar/mover marcador
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);

                    marker.on('dragend', function(e) {
                        const position = marker.getLatLng();
                        window.dispatchEvent(new CustomEvent('markerDragEnd', {
                            detail: {
                                lat: position.lat,
                                lng: position.lng,
                                mapId: '{{ $mapId }}'
                            }
                        }));
                    });
                }

                // Disparar evento personalizado
                window.dispatchEvent(new CustomEvent('mapClick', {
                    detail: {
                        lat: lat,
                        lng: lng,
                        mapId: '{{ $mapId }}'
                    }
                }));
            });

            // Exponser el mapa y el marcador globalmente para acceso desde otras funciones
            window['map_' + '{{ $mapId }}'] = map;
            window['marker_' + '{{ $mapId }}'] = null;
            Object.defineProperty(window, 'marker_' + '{{ $mapId }}', {
                get() {
                    return marker;
                },
                set(value) {
                    marker = value;
                }
            });
        @else
            // Exponer mapa globalmente solo
            window['map_' + '{{ $mapId }}'] = map;
        @endif

        // Agregar marcadores predefinidos si existen
        @if (count($markers) > 0)
            @foreach ($markers as $marker)
                L.marker([{{ $marker['lat'] }}, {{ $marker['lng'] }}], {
                    @if (isset($marker['icon']))
                        icon: {{ $marker['icon'] }}
                    @endif
                }).addTo(map)
                @if (isset($marker['popup']))
                    .bindPopup(`{{ $marker['popup'] }}`)
                @endif ;
            @endforeach
        @endif

        // Exponer mapa para acceso desde componentes padre (usando underscore en lugar de guiones)
        @php
            $safeMapId = str_replace('-', '_', $mapId);
        @endphp
        // Exponer la instancia y la función de inicialización para que otras partes del sistema
        // (como modales) puedan acceder, invalidar tamaño o reinicializar si es necesario.
        window['mapInstance_' + '{{ $safeMapId }}'] = map;
        window['map_' + '{{ $safeMapId }}'] = map;
        window['initLeafletMap_' + '{{ $safeMapId }}'] = {{ $safeFuncName }};

        // Force invalidation after a small delay to ensure tile rendering and sizing
        setTimeout(function() {
            try {
                map.invalidateSize(true);
            } catch (e) {}
        }, 250);
    }
</script>
