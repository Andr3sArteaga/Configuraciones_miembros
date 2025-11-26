@extends('layouts.app')

@section('title', 'Cursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cursos</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCursoModal">
                        <i class="fas fa-plus"></i> Nuevo Curso
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @forelse($cursos as $curso)
                    <div class="callout callout-warning">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5>{{ $curso->titulo }}</h5>
                            <span class="badge badge-warning text-white">
                                <i class="fas fa-user"></i> {{ $curso->inscritos ?? 0 }} Inscritos
                            </span>
                        </div>
                        
                        <p>{{ $curso->descripcion ?? 'Sin descripción disponible.' }}</p>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-warning text-white btn-sm">
                                <i class="fas fa-info-circle"></i> Mas información
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay cursos registrados actualmente.
                    </div>
                @endforelse
            </div>
        </div>

        @if ($cursos->hasPages())
            <div class="row mt-3">
                <div class="col-md-12">
                    {{ $cursos->links() }}
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .callout {
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            background-color: #fff;
            border-left: 5px solid #e9ecef;
            margin-bottom: 1rem;
            padding: 1rem;
        }
        .callout.callout-warning {
            border-left-color: #ffc107;
        }
    </style>
@stop

@include('cursos.partials.create-modal')
