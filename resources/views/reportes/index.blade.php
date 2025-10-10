@extends('layouts.app')

@section('title', 'Reportes')
@section('content_header_title', 'Reportes')
@section('content_header_subtitle', 'Listado')

@section('content_body')
    <x-adminlte-card title="Reportes" theme="orange" icon="fas fa-chart-bar">
        <a href="{{ route('reportes.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Reporte
        </a>

        <x-adminlte-datatable id="tableReportes" :heads="['ID', 'Reportante', 'Estado', 'Acciones']">
            @foreach ($reportes as $reporte)
                <tr>
                    <td>{{ $reporte->id }}</td>
                    <td>{{ $reporte->nombre_reportante }}</td>
                    <td>{{ $reporte->estado }}</td>
                    <td>
                        <a href="{{ route('reportes.show', $reporte) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('reportes.edit', $reporte) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('reportes.destroy', $reporte) }}" method="POST" style="display:inline;">
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
