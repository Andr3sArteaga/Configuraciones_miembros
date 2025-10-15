<!-- === MODAL CREAR BRIGADA === -->
<div class="modal fade" id="modalBrigada" tabindex="-1" role="dialog" aria-labelledby="modalBrigadaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content shadow-lg border-0">

      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold text-warning">
          <i class="fas fa-plus-circle"></i> Crear Brigada
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- Tabs -->
        <ul class="nav nav-pills mb-3 justify-content-center">
          @php $steps = ['Ubicación', 'Configuración', 'Líder', 'Mochila', 'Comunitarios']; @endphp
          @foreach($steps as $index => $step)
            <li class="nav-item">
              <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-toggle="pill"
                href="#step{{ $index }}" role="tab">
                Paso {{ $index + 1 }}: {{ $step }}
              </a>
            </li>
          @endforeach
        </ul>

        <!-- Contenido -->
        <div class="tab-content">
          <!-- Paso 1 -->
          <div class="tab-pane fade show active" id="step0" role="tabpanel">
            <h5 class="fw-bold mb-3">Seleccione la ubicación del equipo</h5>

            <div class="card p-2 shadow-sm" style="border-radius: 10px; overflow: hidden;">
              <div id="map" style="height: 380px; border-radius: 10px;"></div>
            </div>

            <p class="mt-2 text-success" id="ubicacionTexto">
              <i class="fas fa-map-marker-alt"></i> Ubicación seleccionada: -17.6902, -62.9715
            </p>
          </div>

          <!-- Paso 2 -->
          <div class="tab-pane fade" id="step1" role="tabpanel">
            <h5 class="fw-bold mb-3">Configuración del equipo</h5>
            <div class="form-group">
              <label>Nombre del equipo</label>
              <input type="text" class="form-control" placeholder="Ej. Salva Tierras">
            </div>
            <div class="form-group">
              <label>Estado del equipo</label>
              <select class="form-control">
                <option>Activo</option>
                <option>Inactivo</option>
                <option>En misión</option>
              </select>
            </div>
          </div>

          <!-- Paso 3 -->
          <div class="tab-pane fade" id="step2" role="tabpanel">
            <h5 class="fw-bold mb-3">Seleccionar líder</h5>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user"></i> Franco Torrez</span>
                <input type="radio" name="lider" checked>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user"></i> Anuska Arruda</span>
                <input type="radio" name="lider">
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-between">
        <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-warning text-white fw-bold">Guardar</button>
      </div>
    </div>
  </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  let createMap = null, createMarker = null;

  // Crear mapa cuando el modal se abre
  $('#modalBrigada').on('shown.bs.modal', function () {
    if (createMap) {
      setTimeout(() => createMap.invalidateSize(), 0);
      return;
    }

    // Definir íconos locales (evita error 404)
    const LeafIcon = L.Icon.extend({
      options: {
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      }
    });

    const defaultIcon = new LeafIcon();

    createMap = L.map('map', { zoomControl: true })
                .setView([-17.6902, -62.9715], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(createMap);

    createMarker = L.marker([-17.6902, -62.9715], { draggable: true, icon: defaultIcon }).addTo(createMap);

    createMarker.on('dragend', function (e) {
      const { lat, lng } = e.target.getLatLng();
      document.getElementById('ubicacionTexto').innerHTML =
        `<i class='fas fa-map-marker-alt'></i> Nueva ubicación: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
    });

    setTimeout(() => createMap.invalidateSize(), 300);
  });

  // Eliminar mapa al cerrar
  $('#modalBrigada').on('hidden.bs.modal', function () {
    if (createMap) {
      createMap.remove();
      createMap = null;
      createMarker = null;
    }
  });
});
</script>

<style>
/* Solo afecta al modal de brigadas */
#modalBrigada .nav-pills .nav-link {
  border-radius: 30px;
  color: #555;
  margin: 0 5px;
}

#modalBrigada .nav-pills .nav-link.active {
  background-color: #ff7a00 !important;
  color: white !important;
}

#map {
  width: 100%;
  height: 380px;
  cursor: grab;
}

#map.leaflet-dragging {
  cursor: grabbing !important;
}

.modal-body {
  overflow-y: auto;
}
</style>

@endsection
