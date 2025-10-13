@forelse($notifications as $notification)
    <a href="#" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i> {{ $notification->message }}
        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
    </a>
@empty
    <a href="#" class="dropdown-item">
        <i class="fas fa-bell mr-2"></i> No new notifications
    </a>
@endforelse
