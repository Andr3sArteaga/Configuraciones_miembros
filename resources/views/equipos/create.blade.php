@extends('adminlte::page')

@section('title', 'Crear Equipo')

@section('content_header')
    <h1>Crear Nuevo Equipo</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Asistente de Creación de Equipos</h3>
                    </div>
                    <div class="card-body text-center" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <i class="fas fa-users-cog fa-4x text-muted mb-4"></i>
                        <h5>Iniciar proceso de conformación de equipo</h5>
                        <p class="text-muted">Haga clic en el botón para abrir el asistente de creación.</p>
                        
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#createTeamModal">
                            <i class="fas fa-plus"></i> Nuevo Equipo
                        </button>

                        <!-- Incluir el modal parcial -->
                        @include('equipos.partials.create-modal', [
                            'modalId' => 'createTeamModal',
                            'isEditMode' => false,
                            'formAction' => route('equipos.store'),
                            'reportes' => $reportes ?? [],
                            'liderAsignadoId' => $liderAsignadoId ?? null
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@stop

@section('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            // Auto open modal if desired, or let user click
            // $('#createTeamModal').modal('show');
        });
    </script>
@stop
