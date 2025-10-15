<div class="modal fade" id="modalEditarBrigada" tabindex="-1" role="dialog" aria-labelledby="modalEditarBrigadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-warning" id="modalEditarBrigadaLabel">
                    <i class="fas fa-edit"></i> Editar Brigada
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formEditarBrigada" method="POST" action="#">
                <div class="modal-body">

                    <!-- Navegación de pasos -->
                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab-edit" role="tablist">
                        @php 
                            $steps = ['Ubicación', 'Configuración', 'Líder', 'Mochila', 'Comunitarios']; 
                        @endphp
                        @foreach($steps as $index => $step)
                            <li class="nav-item">
                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                                   id="edit-step{{ $index }}-tab" 
                                   data-toggle="pill" 
                                   href="#edit-step{{ $index }}" 
                                   role="tab">
                                    Paso {{ $index + 1 }}: {{ $step }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="pills-tabContent-edit">

                        <!-- Paso 1: Ubicación -->
                        <div class="tab-pane fade show active" id="edit-step0" role="tabpanel">
                            <h5 class="fw-bold mb-3">Seleccione la ubicación del equipo</h5>
                            <div id="edit-map" style="height:400px; border-radius:10px;"></div>
                            <p class="mt-2 text-success" id="ubicacionTexto">
                                <i class="fas fa-map-marker-alt"></i> Ubicación actual: -17.6902, -62.9715
                            </p>
                        </div>

                        <!-- Paso 2: Configuración -->
                        <div class="tab-pane fade" id="edit-step1" role="tabpanel">
                            <h5 class="fw-bold mb-3">Configuración del equipo</h5>
                            <div class="form-group">
                                <label>Nombre del equipo</label>
                                <input type="text" name="nombre" class="form-control" value="Salva Tierras">
                            </div>

                            <div class="form-group">
                                <label>Estado del equipo</label>
                                <select name="estado" class="form-control">
                                    <option selected>Activo</option>
                                    <option>Inactivo</option>
                                    <option>En misión</option>
                                </select>
                            </div>
                        </div>

                        <!-- Paso 3: Líder -->
                        <div class="tab-pane fade" id="edit-step2" role="tabpanel">
                            <h5 class="fw-bold mb-3">Seleccionar líder del equipo</h5>
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

                        <!-- Paso 4: Mochila -->
                        <div class="tab-pane fade" id="edit-step3" role="tabpanel">
                            <h5 class="fw-bold mb-3">Mochila del equipo</h5>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>Abrigo (Disponible: 161)</span>
                                <div>
                                    <button class="btn btn-sm btn-light">-</button>
                                    <span class="mx-2">0</span>
                                    <button class="btn btn-sm btn-warning text-white">+</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>Agua (Disponible: 33)</span>
                                <div>
                                    <button class="btn btn-sm btn-light">-</button>
                                    <span class="mx-2">0</span>
                                    <button class="btn btn-sm btn-warning text-white">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 5: Comunitarios -->
                        <div class="tab-pane fade" id="edit-step4" role="tabpanel">
                            <h5 class="fw-bold mb-3">Comunitarios locales</h5>
                            <button class="btn btn-outline-warning btn-sm mb-3"><i class="fas fa-plus"></i> Agregar comunario</button>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Mateo (25 años)
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Juan (20 años)
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning text-white fw-bold" id="btnGuardarFake">Actualizar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // === EDITAR BRIGADA ===
  let editMap = null;
  let editMarker = null;

  $('#modalEditarBrigada').on('shown.bs.modal', function () {
    if (editMap) {
      setTimeout(() => editMap.invalidateSize(), 0);
      return;
    }

    editMap = L.map('edit-map').setView([-17.6902, -62.9715], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(editMap);

    editMarker = L.marker([-17.6902, -62.9715], { draggable: true }).addTo(editMap);

    editMarker.on('dragend', e => {
      const { lat, lng } = e.target.getLatLng();
      document.getElementById('ubicacionTexto').innerHTML =
        `<i class='fas fa-map-marker-alt'></i> Ubicación seleccionada: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
    });

    // Corregir tamaño tras animación
    setTimeout(() => editMap.invalidateSize(), 300);
  });

  $('#modalEditarBrigada').on('hidden.bs.modal', function () {
    if (editMap) {
      editMap.remove();
      editMap = null;
      editMarker = null;
    }
  });

  // === CREAR BRIGADA ===
  let createMap = null;
  let createMarker = null;

  $('#modalBrigada').on('shown.bs.modal', function () {
    if (createMap) {
      setTimeout(() => createMap.invalidateSize(), 0);
      return;
    }

    createMap = L.map('map').setView([-17.6902, -62.9715], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(createMap);

    createMarker = L.marker([-17.6902, -62.9715], { draggable: true }).addTo(createMap);
    createMarker.on('dragend', e => {
      const { lat, lng } = e.target.getLatLng();
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: `Nueva ubicación: ${lat.toFixed(5)}, ${lng.toFixed(5)}`,
        showConfirmButton: false,
        timer: 1200
      });
    });

    setTimeout(() => createMap.invalidateSize(), 300);
  });

  $('#modalBrigada').on('hidden.bs.modal', function () {
    if (createMap) {
      createMap.remove();
      createMap = null;
      createMarker = null;
    }
  });
});
</script>
@endsection



<style>

#edit-map, #map {
  width: 100%;
  height: 420px;
  border-radius: 10px;
  overflow: hidden;
}

.leaflet-container {
  width: 100% !important;
  height: 100% !important;
}


.nav-pills .nav-link {
    border-radius: 30px;
    color: #555;
    margin: 0 5px;
}
.nav-pills .nav-link.active {
    background-color: #ff7a00 !important;
    color: white !important;
}
</style>
@stop
