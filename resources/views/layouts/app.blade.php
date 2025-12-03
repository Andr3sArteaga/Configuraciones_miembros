@extends('adminlte::page')

{{-- 🔹 Cargar componente de Leaflet Assets --}}
<x-map.leaflet-assets />

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
            /* color de fondo por defecto del sidebar */
            --sidebar-hover-color: rgba(0, 0, 0, 0.08);
            /* color al pasar el cursor */
            --sidebar-hover-text-color: #ffffff;
            /* color del texto en hover */
            --sidebar-active-color: rgba(0, 0, 0, 0.14);
            /* color del item activo */
            --sidebar-active-text-color: #ffffff;
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


        /* Override: hover y activo en el sidebar (más específico que AdminLTE por si se usa clases globales) */
        .main-sidebar .nav-sidebar .nav-link:hover {
            background-color: var(--sidebar-hover-color) !important;
            color: var(--sidebar-hover-text-color) !important;
        }

        .main-sidebar .nav-sidebar .nav-link:focus {
            background-color: var(--sidebar-hover-color) !important;
            color: var(--sidebar-hover-text-color) !important;
        }

        .main-sidebar .nav-sidebar .nav-link.active {
            background-color: var(--sidebar-active-color) !important;
            color: var(--sidebar-active-text-color) !important;
        }

        /* Variante específica para sidebar-light/dark si se quiere invertir contraste */
        .sidebar-light .main-sidebar .nav-sidebar .nav-link:hover,
        .sidebar-light .main-sidebar .nav-sidebar .nav-link.active {
            color: var(--sidebar-hover-text-color) !important;
        }

        /* Estilos para ajustar el tamaño del logo sin romper el layout */
        .brand-link img.brand-image {
            width: auto !important;
            height: 40px !important;
            max-height: 40px !important;
            object-fit: contain !important;
            /* evita recortar y mantiene la proporción */
            padding: 0 !important;
            /* eliminar padding para que no se vea el borde */
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            /* anular sombra si existe */
            box-sizing: border-box !important;
        }

        /* Si el layout usa la clase text-sm, reducir ligeramente el tamaño */
        .brand-link.text-sm img.brand-image,
        .text-sm .brand-link img.brand-image {
            height: 36px !important;
            max-height: 36px !important;
        }

        .btn-primary:not(:hover) {
            color: rgb(0, 0, 0);
            /* color cuando NO está el mouse encima */
        }

        /* ===== SELECT2 ESTILOS GLOBALES ===== */
        .select2-container--bootstrap4 .select2-selection--single {
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem;
            height: auto;
            min-height: 38px;
            padding: 6px 12px !important;
            background-color: #fff !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            line-height: 1.5;
            color: #495057;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            display: block !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }

        .select2-container--bootstrap4 .select2-selection--single:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }
    </style>
@endpush
