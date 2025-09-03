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

{{-- 🔹 Sidebar personalizado --}}
@section('adminlte_sidebar')
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            {{-- Usuarios --}}
            <li class="nav-item">
                <a href="{{ route('usuarios.index') }}"
                    class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Usuarios</p>
                </a>
            </li>

            {{-- Donaciones --}}
            <li class="nav-item">
                <a {{-- href="{{ route('donaciones.index') }}" --}} class="nav-link {{ request()->routeIs('donaciones.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hand-holding-heart"></i>
                    <p>Donaciones</p>
                </a>
            </li>

            {{-- Reportes --}}
            <li class="nav-item">
                <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>Reportes</p>
                </a>
            </li>

            {{-- Recursos --}}
            <li class="nav-item">
                <a href="{{ route('recursos.index') }}" class="nav-link {{ request()->routeIs('recursos.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Recursos</p>
                </a>
            </li>

            {{-- Comunarios de Apoyo --}}
            <li class="nav-item">
                <a href="{{ route('comunarios_apoyo.index') }}" class="nav-link {{ request()->routeIs('comunarios_apoyo.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hands-helping"></i>
                    <p>Comunarios de Apoyo</p>
                </a>
            </li>

            {{-- Reportes de Incendio --}}
            <li class="nav-item">
                <a href="{{ route('reportes_incendio.index') }}" class="nav-link {{ request()->routeIs('reportes_incendio.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-fire"></i>
                    <p>Reportes de Incendio</p>
                </a>
            </li>

        </ul>
    </nav>
@stop

{{-- 🔹 Footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
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
        .nav-sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endpush
