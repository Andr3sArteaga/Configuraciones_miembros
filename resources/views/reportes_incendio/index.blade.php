@extends('layouts.app')

@section('title', 'Reportes de Incendio')
@section('content_header_title', 'Reportes de Incendio')
@section('content_header_subtitle', 'Listado')

@section('content_body')
    <x-adminlte-card title="Reportes de Incendio" theme="primary" icon="fas fa-users">
        <a href="{{ route('reportes_incendio.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Reporte de Incendio
        </a>

        <x-adminlte-datatable id="tableReportesIncendio" :heads="['ID', 'Nombre del Incidente', 'Controlado', 'Acciones']">
            @foreach ($reportes_incendio as $reporte_incendio)
                <tr>
                    <td>{{ $reporte_incendio->id }}</td>
                    <td>{{ $reporte_incendio->nombre_incidente }}</td>
                    <td>
                        {{ $reporte_incendio->controlado == true ? 'Si' : 'No' ?? 'Desconocido' }}
                    </td>
                    <td>
                        <a href="{{ route('reportes_incendio.show', $reporte_incendio) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('reportes_incendio.edit', $reporte_incendio) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('reportes_incendio.destroy', $reporte_incendio) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
@stop
