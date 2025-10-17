@extends('layouts.app')

@section('title', 'Dashboard')
@section('content_header_title', 'Dashboard')

@section('content_body')
    <div class="col-lg-6 col-6">
        <x-adminlte-profile-widget name="{{ $nameUser }}" desc="Administrator" theme="red"
            img="https://picsum.photos/id/1/100" layout-type="classic">
            <x-adminlte-profile-row-item icon="fas fa-fw fa-user-friends" title="Equipos" text="125" url="#"
                badge="teal" />
            <x-adminlte-profile-row-item icon="fas fa-fw fa-bell fa-flip-horizontal" title="Notificaciones" text="243"
                url="#" badge="lightblue" />
            <x-adminlte-profile-row-item icon="fas fa-fw fa-flag" title="Reportes" text="37" url="#"
                badge="navy" />
        </x-adminlte-profile-widget>
    </div>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalReportes }}</h3>
                    <p>Reportes</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="{{ route('reportes.index') }}" class="small-box-footer">Ver todos <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalIncendios }}</h3>
                    <p>Incendios</p>
                </div>
                <div class="icon"><i class="fas fa-fire"></i></div>
                <a href="{{ route('reportes_incendio.index') }}" class="small-box-footer">Ver todos <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalRecursos }}</h3>
                    <p>Recursos</p>
                </div>
                <div class="icon"><i class="fas fa-truck"></i></div>
                <a href="{{ route('recursos.index') }}" class="small-box-footer">Ver todos <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalUsuarios }}</h3>
                    <p>Usuarios</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('usuarios.index') }}" class="small-box-footer">Ver todos <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reportes de los últimos 15 días</h3>
                </div>
                <div class="card-body">
                    <canvas id="chart-reportes" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Incendios controlados</h3>
                </div>
                <div class="card-body">
                    <canvas id="chart-incendios" height="180"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recursos por estado</h3>
                </div>
                <div class="card-body">
                    <canvas id="chart-recursos" height="180"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimos reportes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Reportante</th>
                                <th>Lugar</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosReportes as $r)
                                <tr>
                                    <td>{{ optional($r->fecha_hora)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $r->nombre_reportante }}</td>
                                    <td>{{ $r->nombre_lugar }}</td>
                                    <td><span class="badge badge-secondary">{{ $r->estado ?? 'N/D' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Incendios recientes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Incidente</th>
                                <th>Controlado</th>
                                <th>Bomberos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosIncendios as $i)
                                <tr>
                                    <td>{{ optional($i->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $i->nombre_incidente }}</td>
                                    <td>
                                        @if ($i->controlado)
                                            <span class="badge badge-success">Sí</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $i->numero_bomberos ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimos recursos solicitados</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosRecursos as $rc)
                                <tr>
                                    <td>{{ optional($rc->creado)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $rc->codigo ?? $rc->id }}</td>
                                    <td>{{ $rc->descripcion }}</td>
                                    <td><span class="badge badge-info">{{ $rc->estado_del_pedido ?? 'N/D' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Line chart: Reportes por día (15 días)
            const ctxReportes = document.getElementById('chart-reportes');
            if (ctxReportes) {
                new Chart(ctxReportes, {
                    type: 'line',
                    data: {
                        labels: @json($labelsReportes),
                        datasets: [{
                            label: 'Reportes por día',
                            data: @json($dataReportes),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Doughnut: Incendios controlados vs no
            const ctxIncendios = document.getElementById('chart-incendios');
            if (ctxIncendios) {
                new Chart(ctxIncendios, {
                    type: 'doughnut',
                    data: {
                        labels: @json($incendiosControlados['labels']),
                        datasets: [{
                            data: @json($incendiosControlados['data']),
                            backgroundColor: ['#dc3545', '#28a745'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Bar: Recursos por estado
            const ctxRecursos = document.getElementById('chart-recursos');
            if (ctxRecursos) {
                new Chart(ctxRecursos, {
                    type: 'bar',
                    data: {
                        labels: @json($recursosPorEstado['labels']),
                        datasets: [{
                            label: 'Recursos',
                            data: @json($recursosPorEstado['data']),
                            backgroundColor: 'rgba(255, 193, 7, 0.6)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            }
        });
    </script>
@stop
