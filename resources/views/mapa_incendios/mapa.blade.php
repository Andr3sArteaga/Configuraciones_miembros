<div class="p-3">
    <!-- Encabezado -->
    <h4 class="text-center text-danger fw-bold mb-1">Mapa de Focos de Calor en Bolivia</h4>
    <p class="text-center text-muted mb-4">Visualiza los focos de calor detectados por satélites en territorio boliviano.</p>

    <!-- 🔘 Filtros de rango y switches -->
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 mb-3">

        <!-- Rango -->
        <div class="d-flex align-items-center">
            <label for="rango" class="me-2 fw-bold">Rango:</label>
            <select id="rango" class="form-select form-select-sm" style="width: 120px;">
                <option>Hoy</option>
                <option>Semana</option>
                <option>Mes</option>
            </select>
        </div>

        <!-- Switches estilo AdminLTE -->
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input bg-danger" id="switchFocos" checked>
            <label class="form-check-label fw-semibold" for="switchFocos">Focos de calor</label>
        </div>

        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input bg-primary" id="switchEquipos">
            <label class="form-check-label fw-semibold" for="switchEquipos">Equipos en camino</label>
        </div>

        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input bg-warning" id="switchReportes">
            <label class="form-check-label fw-semibold" for="switchReportes">Reportes de incendios</label>
        </div>

        <button class="btn btn-danger">
            <i class="fas fa-sync-alt"></i> Actualizar datos
        </button>
    </div>

    <!-- Mensaje informativo -->
    <div class="alert alert-success text-center py-2 mb-3 shadow-sm">
        No se detectaron focos de calor activos en el rango seleccionado.
    </div>

    <!-- Contenedor del mapa -->
    <div id="map-container" class="position-relative" style="height: 550px;">
        <div id="map"></div>

        <!-- 🔥 Widget flotante: Niveles de incendio -->
        <div id="legend-widget" 
            class="position-absolute bg-white shadow rounded-4 p-3"
            style="top: 20px; right: 20px; width: 200px; z-index: 999; border: 1px solid #ddd;">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-fire text-danger me-2"></i>
                <h6 class="fw-bold m-0">Nivel de incendio</h6>
            </div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-success me-2">&nbsp;</span> Bajo
            </div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-warning me-2">&nbsp;</span> Medio
            </div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-danger me-2">&nbsp;</span> Alto
            </div>
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-secondary me-2">&nbsp;</span> Incendios antiguos
            </div>
        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([-17.7833, -63.1821], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([-17.7833, -63.1821]).addTo(map)
            .bindPopup("<b>Santa Cruz de la Sierra</b><br>Centro de monitoreo de incendios.");
    });
</script>

<style>
    #map {
        height: 100%;
        width: 100%;
        border-radius: 10px;
        z-index: 1;
    }

    .form-switch {
        display: inline-flex;
        align-items: center;
        gap: 14px; /* más separación entre switches */
        margin: 0 8px;
    }

    /* Toggle estilo */
    .form-switch .form-check-input {
        width: 2.8em !important;
        height: 1.5em !important;
        border-radius: 1.5em !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        background-color: #d6d6d6 !important; /* gris por defecto */
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        outline: none;
        cursor: pointer;
        box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.2);
    }

    .form-switch .form-check-input::before {
        content: "";
        position: absolute;
        top: 2px;
        left: 2px;
        width: 1.1em;
        height: 1.1em;
        border-radius: 50%;
        background-color: #fff;
        transition: transform 0.3s ease;
    }

    .form-switch .form-check-input:checked {
        background-color: #ff7a00 !important;
        box-shadow: 0 0 3px rgba(255, 122, 0, 0.5);
    }

    .form-switch .form-check-input:checked::before {
        transform: translateX(1.3em);
    }
    .form-switch .form-check-label {
        margin-left: 6px;
        position: relative;
        top: 1px;
        font-weight: 500;
        cursor: pointer;
    }

/* Ajuste del botón */
    .btn.btn-danger {
        margin-left: 6px; 
    }

    #legend-widget h6 {
        font-size: 15px;
    }

    #legend-widget .badge {
        width: 20px;
        height: 12px;
        border-radius: 4px;
    }
</style>

