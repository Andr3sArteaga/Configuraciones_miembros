@extends('layouts.app')

@section('title', 'Mi Kardex')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-id-card"></i> Mi Kardex</h1>
        <a href="{{ route('kardex.pdf') }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
    </div>
@stop

@section('content')
    {{-- Información Personal --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user"></i> Información Personal</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Nombre Completo:</th>
                            <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                        </tr>
                        <tr>
                            <th>CI:</th>
                            <td>{{ $usuario->ci }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $usuario->email }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $usuario->telefono ?? 'No especificado' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Género:</th>
                            <td>{{ $usuario->genero->descripcion ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Sangre:</th>
                            <td>{{ $usuario->tipos_sangre->codigo ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td><span class="badge badge-info">{{ $usuario->role->nombre ?? 'Sin rol' }}</span></td>
                        </tr>
                        <tr>
                            <th>Nivel de Entrenamiento:</th>
                            <td><span
                                    class="badge badge-success">{{ $usuario->niveles_entrenamiento->nivel ?? 'Sin nivel' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas Rápidas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total_reportes'] }}</h3>
                    <p>Reportes Creados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fire"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['reportes_controlados'] }}</h3>
                    <p>Reportes Controlados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['total_equipos'] }}</h3>
                    <p>Equipos Asignados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $estadisticas['total_cursos'] }}</h3>
                    <p>Cursos Asignados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Equipos --}}
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Mis Equipos</h3>
        </div>
        <div class="card-body">
            @if ($equipos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nombre del Equipo</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th>Cantidad de Miembros</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipos as $equipo)
                                <tr>
                                    <td><strong>{{ $equipo->nombre }}</strong></td>
                                    <td>{{ $equipo->especialidad ?? 'No especificada' }}</td>
                                    <td>
                                        @if ($equipo->estado_id)
                                            <span
                                                class="badge badge-{{ $equipo->estados_sistema->codigo == 'activo' ? 'success' : 'secondary' }}">
                                                {{ $equipo->estados_sistema->nombre ?? 'Sin estado' }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sin estado</span>
                                        @endif
                                    </td>
                                    <td>{{ $equipo->cantidad_integrantes ?? 0 }} miembros</td>
                                    <td>
                                        <a href="{{ route('equipos.show', $equipo->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No perteneces a ningún equipo actualmente.
                </div>
            @endif
        </div>
    </div>

    {{-- Reportes de Incendio --}}
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-fire"></i> Mis Reportes de Incendio</h3>
        </div>
        <div class="card-body">
            @if ($reportes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre del Incidente</th>
                                <th>Extensión (ha)</th>
                                <th>Bomberos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportes as $reporte)
                                <tr>
                                    <td>{{ $reporte->fecha_creacion ? $reporte->fecha_creacion->format('d/m/Y H:i') : 'No especificada' }}
                                    </td>
                                    <td><strong>{{ $reporte->nombre_incidente }}</strong></td>
                                    <td>{{ $reporte->extension ? number_format($reporte->extension, 2) . ' ha' : 'No especificada' }}
                                    </td>
                                    <td>{{ $reporte->numero_bomberos ?? 'N/A' }}</td>
                                    <td>
                                        @if ($reporte->controlado)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Controlado
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('reportes-incendio.show', $reporte->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No has creado ningún reporte de incendio.
                </div>
            @endif
        </div>
    </div>

    {{-- Cursos --}}
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Mis Cursos</h3>
        </div>
        <div class="card-body">
            @if ($cursos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nombre del Curso</th>
                                <th>Descripción</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursos as $cursoAsignado)
                                <tr>
                                    <td><strong>{{ $cursoAsignado->curso->nombre ?? 'Curso no disponible' }}</strong></td>
                                    <td>{{ Str::limit($cursoAsignado->curso->descripcion ?? 'Sin descripción', 60) }}</td>
                                    <td>
                                        @if ($cursoAsignado->fecha_asignacion)
                                            <span class="badge badge-info">
                                                <i class="fas fa-calendar"></i>
                                                {{ $cursoAsignado->fecha_asignacion->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">No especificada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('cursos.show', $cursoAsignado->curso_id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver Curso
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tienes cursos asignados actualmente.
                </div>
            @endif
        </div>
    </div>

@stop

@section('css')
    <style>
        .small-box {
            border-radius: 10px;
        }

        .small-box .icon {
            top: -10px;
            font-size: 70px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        }

        .table th {
            font-weight: 600;
            color: #495057;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .card-purple .card-header {
            background-color: #6f42c1;
            color: white;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Kardex página cargada');
    </script>
@stop
