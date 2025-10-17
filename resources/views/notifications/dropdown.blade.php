@forelse($notifications as $notification)
    @php
        $esNueva = $notification->created_at > now()->subDay();
        $icono = $esNueva ? 'fas fa-bell text-warning' : 'fas fa-bell text-muted';
        $textoClase = $esNueva ? 'text-primary font-weight-bold' : 'text-muted';
        $antiguedad = $esNueva ? 'Nueva notificación' : 'Notificación Pasada';
        $titulo = $notification->data['title'] ?? 'Notificación sin título';
    @endphp

    <a href="#" class="dropdown-item d-flex flex-column">
        <div class="d-flex align-items-center">
            <i class="{{ $icono }} mr-2"></i>
            <strong class="{{ $textoClase }}">{{ $antiguedad }}</strong>
        </div>
        <div class="font-weight-bold">{{ $titulo }}</div>
        <div class="text-truncate text-dark">
            {{ $notification->data['message'] ?? 'Sin descripción disponible.' }}
        </div>
        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
    </a>
@empty
    <a href="#" class="dropdown-item text-center text-muted">
        <i class="fas fa-bell-slash mr-2"></i> No hay nuevas notificaciones
    </a>
@endforelse
