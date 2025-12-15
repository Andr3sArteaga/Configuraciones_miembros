<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">

        {{-- Sidebar user panel --}}
        @php
            // Diagnostic logging for session debugging
            \Log::info('Sidebar rendering', [
                'auth_check' => Auth::check(),
                'session_id' => session()->getId(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'url' => request()->url(),
            ]);
        @endphp
        @if (Auth::check())
            <div class="user-panel mt-2 pb-1 mb-1 d-flex">
                <div class="d-flex align-items-center justify-content-center"
                    style="
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                        background: #6c757d;
                        color: #fff;
                        font-weight: bold;
                        font-size: 18px;
                        margin-top: 8px;
                    ">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                </div>

                <div class="info">
                    <a href="{{ Auth::user()->adminlte_profile_url() }}" class="d-block">{{ Auth::user()->nombre }}
                        {{ Auth::user()->apellido }}</a>
                    <span class="d-block text-muted small"
                        style="color: #c2c7d0;">{{ Auth::user()->adminlte_desc() }}</span>
                </div>
            </div>
        @endif

        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>
