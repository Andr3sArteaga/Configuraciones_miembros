@extends('adminlte::page')

@section('title', 'Brigadas')
@section('content_header')
    <h1>Coordinación de Brigadas</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="text-warning fw-bold">Gestión de Brigadas</h4>
                <p class="text-muted mb-0">Organización y coordinación de brigadas de emergencia</p>
            </div>
            <button class="btn btn-warning text-white fw-bold" data-toggle="modal" data-target="#modalEditarBrigada">
                <i class="fas fa-plus"></i> Nueva Brigada
            </button>
        </div>

        <!-- Listado simulado -->
        <div class="row">
            @for($i = 1; $i <= 3; $i++)
                <div class="col-md-4">
                    <div class="card border shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold text-uppercase text-warning">Brigada {{ $i }}</h5>
                            <p><strong>Estado:</strong> Activo</p>
                            <p><strong>Miembros:</strong> {{ rand(3,8) }}</p>
                            <p><strong>Ubicación:</strong> Santa Cruz</p>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modalEditarBrigada">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Si no hay brigadas -->
        @if(false)
            <div class="text-center p-5 text-muted">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>No hay brigadas registradas</h5>
                <p>Puedes crear una nueva brigada usando el botón superior.</p>
            </div>
        @endif
    </div>
</div>

@include('brigadas.demo-modal')
@stop
