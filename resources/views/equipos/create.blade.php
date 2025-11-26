@extends('layouts.app')

@section('title', 'Crear Equipo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Crear Equipo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipos.index') }}">Equipos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Equipos
                        </h3>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#createTeamModal">
                            <i class="fas fa-plus-circle"></i> Crear Nuevo Equipo
                        </button>
                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-lg ml-2">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('equipos.partials.create-modal')
@stop
