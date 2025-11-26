@extends('layouts.app')

@section('title', 'Cursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cursos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Cursos</li>
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
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Listado de Cursos
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Fecha Inicio</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cursos as $curso)
                                        <tr>
                                            <td>
                                                <strong>{{ $curso->titulo }}</strong>
                                                @if($curso->descripcion)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($curso->descripcion, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $curso->fecha_inicio?->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">
                                                No hay cursos registrados
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
