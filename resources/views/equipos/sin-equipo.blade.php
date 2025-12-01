@extends('layouts.app')

@section('title', 'Sin Equipo Asignado')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Equipos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Equipos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-5x text-muted mb-4"></i>
                        <h3>No tienes un equipo asignado</h3>
                        <p class="text-muted">
                            Actualmente no perteneces a ningún equipo. Contacta con un administrador para ser asignado a un equipo.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
