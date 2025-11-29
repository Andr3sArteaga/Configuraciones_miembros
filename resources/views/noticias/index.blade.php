@extends('layouts.app')

@section('title', 'Noticias y Cursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Noticias</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Noticias</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Info Alert -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Noticias sobre Incendios:</strong> Estas noticias se actualizan automáticamente cada 24 horas desde opinion.com.bo
                </div>
            </div>
        </div>

        <!-- News Cards -->
        <div class="row">
            @forelse($noticias as $noticia)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow">
                        @if($noticia->image)
                            <img src="{{ $noticia->image }}" class="card-img-top" alt="{{ $noticia->title }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient-danger d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-fire fa-4x text-white opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ $noticia->url }}" target="_blank" class="text-dark text-decoration-none">
                                    {{ Str::limit($noticia->title, 80) }}
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="far fa-calendar-alt"></i> 
                                {{ $noticia->date->format('d/m/Y H:i') }}
                            </p>
                            
                            @if($noticia->description)
                                <p class="card-text flex-grow-1">
                                    {{ Str::limit($noticia->description, 120) }}
                                </p>
                            @endif
                            
                            <a href="{{ $noticia->url }}" target="_blank" class="btn btn-sm mt-auto" style="background-color: #FF470A; border-color: #FF470A; color: white;">
                                <i class="fas fa-external-link-alt"></i> Leer más
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay noticias disponibles</h5>
                            <p class="text-muted">Las noticias se actualizarán automáticamente.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($noticias->hasPages())
            <div class="row mt-3">
                <div class="col-md-12">
                    {{ $noticias->links() }}
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .card-title a:hover {
            color: #ff6200ff !important;
        }
    </style>
@stop
