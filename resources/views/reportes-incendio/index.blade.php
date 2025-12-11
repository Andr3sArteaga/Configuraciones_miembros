@extends('layouts.app')

@section('title', 'Reportes de Incendios')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte de usuario</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Mensajes de éxito/error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire mr-1"></i>
                            Listado de Reportes
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('reportes-incendio.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Nuevo Reporte
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nombre Incidente</th>
                                        <th>Extensión (ha)</th>
                                        <th>Condición Climática</th>
                                        <th class="text-center">N° Bomberos</th>
                                        <th class="text-center">Necesita Apoyo</th>
                                        <th class="text-center">Controlado</th>
                                        <th>Reportado por</th>
                                        <th>Fecha Creación</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportes as $reporte)
                                        <tr>
                                            <td>
                                                <strong>{{ $reporte->nombre_incidente }}</strong>
                                            </td>
                                            <td>
                                                @if ($reporte->extension)
                                                    <span class="badge badge-info">
                                                        {{ number_format($reporte->extension, 2) }} ha
                                                    </span>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($reporte->condiciones_climatica)
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-cloud-sun mr-1"></i>
                                                        {{ $reporte->condiciones_climatica->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->numero_bomberos)
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-users mr-1"></i>
                                                        {{ $reporte->numero_bomberos }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->necesita_mas_bomberos)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Sí
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($reporte->controlado)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Sí
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-fire mr-1"></i>
                                                        No
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($reporte->usuario)
                                                    <i class="fas fa-user-circle mr-1"></i>
                                                    {{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}
                                                @else
                                                    <span class="text-muted">Sistema</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ $reporte->fecha_creacion?->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $reporte->fecha_creacion?->format('H:i') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('reportes-incendio.show', $reporte->id) }}"
                                                        class="btn btn-info" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('reportes-incendio.edit', $reporte->id) }}"
                                                        class="btn btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" title="Eliminar"
                                                        onclick="if(confirm('¿Está seguro de que desea eliminar este reporte?')) { document.getElementById('delete-form-{{ $reporte->id }}').submit(); }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <form id="delete-form-{{ $reporte->id }}"
                                                    action="{{ route('reportes-incendio.destroy', $reporte->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No hay reportes de incendios registrados
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

        @if ($reportes->hasPages())
            <div class="row mt-3 mb-3" style="background-color: transparent;">
                <div class="col-md-12">
                    <div class="pagination-wrapper-reportes">
                        {{ $reportes->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        /* Fix oversized pagination buttons - INCLUDE NAV ELEMENT */
        .pagination-wrapper-reportes nav,
        .pagination-wrapper-reportes nav *,
        .pagination-wrapper-reportes .pagination,
        .pagination-wrapper-reportes .pagination * {
            box-sizing: border-box !important;
        }

        .pagination-wrapper-reportes nav {
            display: flex !important;
            justify-content: center !important;
        }

        .pagination-wrapper-reportes .pagination {
            margin-bottom: 0 !important;
            font-size: 0.7rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            list-style: none !important;
            padding-left: 0 !important;
        }

        .pagination-wrapper-reportes .pagination .page-item {
            margin: 0 1px !important;
            display: inline-block !important;
            list-style: none !important;
        }

        /* CRITICAL: Target ALL page-link elements including those inside nav */
        .pagination-wrapper-reportes nav .pagination .page-link,
        .pagination-wrapper-reportes .pagination .page-link,
        .pagination-wrapper-reportes .page-link {
            padding: 0.15rem 0.35rem !important;
            font-size: 0.7rem !important;
            line-height: 1 !important;
            min-width: 30px !important;
            max-width: 35px !important;
            width: auto !important;
            height: 30px !important;
            min-height: 30px !important;
            max-height: 30px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            border-radius: 0.25rem !important;
            text-decoration: none !important;
        }

        /* Specific fix for prev/next buttons (first and last) - arrows */
        .pagination-wrapper-reportes nav .pagination .page-item:first-child .page-link,
        .pagination-wrapper-reportes nav .pagination .page-item:last-child .page-link,
        .pagination-wrapper-reportes .pagination .page-item:first-child .page-link,
        .pagination-wrapper-reportes .pagination .page-item:last-child .page-link {
            padding: 0.15rem 0.35rem !important;
            min-width: 30px !important;
            max-width: 35px !important;
            width: 30px !important;
            height: 30px !important;
            min-height: 30px !important;
            max-height: 30px !important;
            font-size: 0.7rem !important;
            line-height: 1 !important;
        }

        /* Fix for disabled prev/next buttons */
        .pagination-wrapper-reportes nav .pagination .page-item.disabled .page-link,
        .pagination-wrapper-reportes .pagination .page-item.disabled .page-link {
            padding: 0.15rem 0.35rem !important;
            min-width: 30px !important;
            max-width: 35px !important;
            width: 30px !important;
            height: 30px !important;
            min-height: 30px !important;
            max-height: 30px !important;
        }

        /* Override ALL AdminLTE and Bootstrap pagination styles */
        .pagination-wrapper-reportes nav .pagination-sm .page-link,
        .pagination-wrapper-reportes nav .pagination .page-link,
        .pagination-wrapper-reportes .pagination-sm .page-link,
        .pagination-wrapper-reportes .pagination .page-link {
            padding: 0.15rem 0.35rem !important;
            font-size: 0.7rem !important;
            min-width: 30px !important;
            max-width: 35px !important;
            height: 30px !important;
            min-height: 30px !important;
            max-height: 30px !important;
            line-height: 1 !important;
        }

        /* Ensure text content inside pagination is small */
        .pagination-wrapper-reportes nav .pagination .page-link span,
        .pagination-wrapper-reportes .pagination .page-link span,
        .pagination-wrapper-reportes nav .pagination .page-link,
        .pagination-wrapper-reportes .pagination .page-link {
            font-size: 0.7rem !important;
            line-height: 1 !important;
        }

        /* Active page button */
        .pagination-wrapper-reportes nav .pagination .page-item.active .page-link,
        .pagination-wrapper-reportes .pagination .page-item.active .page-link {
            min-width: 30px !important;
            max-width: 35px !important;
            height: 30px !important;
            min-height: 30px !important;
            max-height: 30px !important;
            padding: 0.15rem 0.35rem !important;
        }

        /* Fix button group styling */
        .btn-group.btn-group-sm {
            display: inline-flex;
            vertical-align: middle;
        }

        .btn-group.btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            height: auto;
            min-height: 31px;
        }

        /* Ensure buttons are properly aligned */
        table .btn-group {
            white-space: nowrap;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force pagination button sizes - including those inside nav
            const paginationLinks = document.querySelectorAll(
                '.pagination-wrapper-reportes .page-link, .pagination-wrapper-reportes nav .page-link');
            paginationLinks.forEach(function(link) {
                // For arrow buttons (prev/next), use fixed width
                const isArrow = link.textContent.trim() === '‹' || link.textContent.trim() === '›' ||
                    link.textContent.trim() === '«' || link.textContent.trim() === '»' ||
                    link.getAttribute('aria-label')?.includes('previous') ||
                    link.getAttribute('aria-label')?.includes('next');

                if (isArrow) {
                    link.style.width = '30px';
                    link.style.minWidth = '30px';
                    link.style.maxWidth = '30px';
                } else {
                    link.style.minWidth = '30px';
                    link.style.maxWidth = '35px';
                    link.style.width = 'auto';
                }

                link.style.height = '30px';
                link.style.minHeight = '30px';
                link.style.maxHeight = '30px';
                link.style.padding = '0.15rem 0.35rem';
                link.style.fontSize = '0.7rem';
                link.style.lineHeight = '1';
                link.style.display = 'inline-flex';
                link.style.alignItems = 'center';
                link.style.justifyContent = 'center';
            });

            // Force pagination item sizes
            const paginationItems = document.querySelectorAll(
                '.pagination-wrapper-reportes .page-item, .pagination-wrapper-reportes nav .page-item');
            paginationItems.forEach(function(item) {
                item.style.margin = '0 1px';
            });

            // Also force styles on the pagination ul itself
            const paginationUl = document.querySelectorAll(
                '.pagination-wrapper-reportes .pagination, .pagination-wrapper-reportes nav .pagination');
            paginationUl.forEach(function(ul) {
                ul.style.display = 'flex';
                ul.style.alignItems = 'center';
                ul.style.justifyContent = 'center';
            });
        });
    </script>
@stop
