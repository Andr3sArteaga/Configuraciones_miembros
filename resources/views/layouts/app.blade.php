@extends('adminlte::page')

{{-- 🔹 Título dinámico --}}
@section('title')
    {{ config('adminlte.title', 'Mi Panel') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@stop

{{-- 🔹 Header de contenido --}}
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- 🔹 Contenido principal --}}
@section('content')
    @yield('content_body')
@stop

{{-- 🔹 Footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'Alas Chiquitanas - Grupo 12  ') }}
        </a>
    </strong>
@stop

{{-- 🔹 Scripts globales --}}
@push('js')
    <script>
        $(function() {
            console.log("JS global cargado desde layout con sidebar 🚀");
        });
    </script>
@endpush

{{-- 🔹 CSS global --}}
@push('css')
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --text-color: #212529;
        }

        .nav-sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: #fff;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
        }

        .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Estilos para modo oscuro */
        .dark-mode .main-header {
            background-color: #343a40 !important;
            border-bottom: 1px solid #495057 !important;
        }

        .dark-mode .main-header .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .dark-mode .main-header .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }

        .dark-mode .main-header .navbar-brand {
            color: #ffffff !important;
        }

        .dark-mode .main-header .navbar-toggler {
            color: #ffffff !important;
        }

        .dark-mode .main-header .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        .dark-mode .main-sidebar {
            background-color: #212529 !important;
        }

        .dark-mode .main-sidebar .nav-sidebar .nav-link {
            color: #adb5bd !important;
        }

        .dark-mode .main-sidebar .nav-sidebar .nav-link:hover {
            background-color: #495057 !important;
            color: #ffffff !important;
        }

        .dark-mode .main-sidebar .nav-sidebar .nav-link.active {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
        }

        .dark-mode .content-wrapper {
            background-color: #343a40 !important;
        }

        /* Asegurar que el body también se oscurezca */
        .dark-mode body {
            background-color: #343a40 !important;
            color: #ffffff !important;
        }

        /* Solucionar problema del título que desaparece en hover */
        .content-header h1.text-muted {
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }

        .content-header h1.text-muted:hover {
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            color: #6c757d !important;
        }
    </style>
@endpush
