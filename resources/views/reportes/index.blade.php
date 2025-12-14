@extends('layouts.app')

@section('title', 'Reportes Rapidos')

@section('content_header')
    <div class="container-fluid">
        @php
            $pendientesCount = 0;
            foreach ($reportes as $reporteItem) {
                if (
                    isset($reporteItem->estados_sistema) &&
                    strcasecmp($reporteItem->estados_sistema->nombre ?? '', 'pendiente') === 0
                ) {
                    $pendientesCount++;
                }
            }
            $sinEstadoCount = $reportes->whereNull('estado_id')->count();
        @endphp

        @if ($pendientesCount > 0)
            <x-adminlte-alert theme="warning" title="Pendientes">
                {{ $pendientesCount == 1
                    ? 'Existe 1 reporte pendiente'
                    : 'Existen ' . $pendientesCount . ' reportes pendientes' }}
                {{ $sinEstadoCount > 0 ? ' y ' . $sinEstadoCount . ' sin estado asignado' : '' }}.
            </x-adminlte-alert>
        @endif
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reportes Rapidos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes Ciudadanos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Reportes</h3>
                <div class="card-tools">
                    <a href="{{ route('reportes.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank" title="Exportar PDF">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('reportes.export.csv') }}" class="btn btn-success btn-sm" title="Exportar CSV">
                        <i class="fas fa-file-excel"></i> CSV
                    </a>
                    <a href="{{ route('reportes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Reporte
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="reportes-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Reportante</th>
                            <th>Lugar</th>
                            <th>Tipo</th>
                            <th>Gravedad</th>
                            <th>Estado</th>
                            <th>Recursos</th>
                            <th width="150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportes as $reporte)
                            @php
                                $estado = strtolower(trim($reporte->estados_sistema->nombre ?? $reporte->estado ?? ''));
                                $falsoPositivo = in_array($estado, [
                                    'falso positivo', 'falsopositivo', 'falso_positivo', 'false positive', 'falsepositive', 'false_positive'
                                ]);
                            @endphp
                            <tr @if($falsoPositivo) style="background-color: rgba(255, 0, 0, 0.40);" @endif>
                                <td>{{ $reporte->fecha_hora->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong>{{ $reporte->nombre_reportante }}</strong><br>
                                    <small class="text-muted">{{ $reporte->telefono_contacto }}</small>
                                </td>
                                <td>{{ $reporte->nombre_lugar ?? 'Sin especificar' }}</td>
                                <td>
                                    @if ($reporte->tipos_incidente)
                                        <span class="badge"
                                            style="background-color: {{ $reporte->tipos_incidente->color ?? '#6c757d' }}; color: white;">
                                            @if ($reporte->tipos_incidente->icono)
                                                <i class="fas fa-{{ $reporte->tipos_incidente->icono }}"></i>
                                            @endif
                                            {{ $reporte->tipos_incidente->nombre }}
                                        </span>
                                    @else
                                        <span class="text-muted">Sin especificar</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($reporte->niveles_gravedad)
                                        <span class="badge"
                                            style="background-color: {{ $reporte->niveles_gravedad->color ?? '#ffc107' }}; color: white;">
                                            {{ $reporte->niveles_gravedad->nombre }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($reporte->estados_sistema)
                                        <span class="badge"
                                            style="background-color: {{ $reporte->estados_sistema->color ?? '#6c757d' }}; color: white;">
                                            {{ $reporte->estados_sistema->nombre }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin estado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($reporte->cant_bomberos > 0)
                                        <span class="badge badge-danger">{{ $reporte->cant_bomberos }} <i
                                                class="fas fa-fire-extinguisher"></i></span>
                                    @endif
                                    @if ($reporte->cant_paramedicos > 0)
                                        <span class="badge badge-info">{{ $reporte->cant_paramedicos }} <i
                                                class="fas fa-ambulance"></i></span>
                                    @endif
                                    @if ($reporte->cant_veterinarios > 0)
                                        <span class="badge badge-success">{{ $reporte->cant_veterinarios }} <i
                                                class="fas fa-paw"></i></span>
                                    @endif
                                    @if ($reporte->cant_autoridades > 0)
                                        <span class="badge badge-warning">{{ $reporte->cant_autoridades }} <i
                                                class="fas fa-shield-alt"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('reportes.show', $reporte->id) }}" class="btn btn-sm btn-primary"
                                        title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reportes.edit', $reporte->id) }}" class="btn btn-sm btn-info"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reportes.destroy', $reporte->id) }}" method="POST"
                                        style="display: inline-block;" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $reportes->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#reportes-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                order: [
                    [0, 'desc']
                ],
                pageLength: 25
            });

            // Confirmación antes de eliminar
            $('.form-delete').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Esta acción no se puede revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop
