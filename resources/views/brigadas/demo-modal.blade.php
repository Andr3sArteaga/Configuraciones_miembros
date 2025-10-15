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

                        <!-- Paso 1 -->
                        <div class="tab-pane fade show active" id="edit-step0" role="tabpanel">
                            <h5 class="fw-bold mb-3">Seleccione la ubicación del equipo</h5>
                            <div id="edit-map" style="height:400px; border-radius:10px;"></div>
                            <p class="mt-2 text-success" id="ubicacionTexto">
                                <i class="fas fa-map-marker-alt"></i> Ubicación actual: -17.6902, -62.9715
                            </p>
                        </div>

                        <!-- Paso 2 -->
                        <div class="tab-pane fade" id="edit-step1" role="tabpanel">
                            <h5 class="fw-bold mb-3">Configuración</h5>
                            <input type="text" class="form-control mb-2" value="Salva Tierras">
                            <select class="form-control">
                                <option selected>Activo</option>
                                <option>Inactivo</option>
                                <option>En misión</option>
                            </select>
                        </div>

                        <!-- Paso 3 -->
                        <div class="tab-pane fade" id="edit-step2" role="tabpanel">
                            <h5 class="fw-bold mb-3">Seleccionar líder</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user"></i> Franco Torrez</span>
                                    <input type="radio" checked>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user"></i> Anuska Arruda</span>
                                    <input type="radio">
                                </li>
                            </ul>
                        </div>

                        <!-- Paso 4 -->
                        <div class="tab-pane fade" id="edit-step3" role="tabpanel">
                            <h5 class="fw-bold mb-3">Mochila</h5>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>Abrigo (Disponible: 161)</span>
                                <div>
                                    <button class="btn btn-sm btn-light">-</button>
                                    <span class="mx-2">0</span>
                                    <button class="btn btn-sm btn-warning text-white">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 5 -->
                        <div class="tab-pane fade" id="edit-step4" role="tabpanel">
                            <h5 class="fw-bold mb-3">Comunitarios</h5>
                            <button class="btn btn-outline-warning btn-sm mb-3"><i class="fas fa-plus"></i> Agregar comunario</button>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Mateo (25 años)
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning text-white fw-bold" id="btnGuardarFake">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('edit-map').setView([-17.69, -62.97], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([-17.69, -62.97], { draggable: true }).addTo(map);
    document.getElementById('btnGuardarFake').addEventListener('click', function() {
        Swal.fire({ icon: 'success', title: 'Cambios guardados (demo)', timer: 1200, showConfirmButton: false });
        setTimeout(() => $('#modalEditarBrigada').modal('hide'), 1200);
    });
});
</script>

<style>
.nav-pills .nav-link { border-radius: 30px; color: #555; margin: 0 5px; }
.nav-pills .nav-link.active { background-color: #ff7a00 !important; color: white !important; }
</style>
@stop
