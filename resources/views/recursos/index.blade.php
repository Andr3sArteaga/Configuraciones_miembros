@extends('layouts.app')

@section('title', 'Recursos')
@section('content_header_title', 'Recursos')
@section('content_header_subtitle', 'Listado')

@section('content_body')
    <x-adminlte-card title="Recursos" theme="primary" icon="fas fa-users">
        <a href="{{ route('recursos.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Recurso
        </a>

        <x-adminlte-datatable id="tableRecursos" :heads="['ID', 'Descripción', 'Creado', 'Acciones']">
            @foreach ($recursos as $recurso)
                <tr>
                    <td>{{ $recurso->id }}</td>
                    <td>{{ $recurso->descripcion }}</td>
                    <td>{{ $recurso->creado }}</td>
                    <td>
                        <a href="{{ route('recursos.show', $recurso) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('recursos.edit', $recurso) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('recursos.destroy', $recurso) }}" method="POST" style="display:inline;">
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
