@extends('layouts.app')

@section('title', 'Comunarios de Apoyo')
@section('content_header_title', 'Comunarios de Apoyo')
@section('content_header_subtitle', 'Listado')

@section('content_body')
    <x-adminlte-card title="Comunarios de Apoyo" theme="orange" icon="fas fa-hands-helping">
        <a href="{{ route('comunarios_apoyo.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Comunario de Apoyo
        </a>

        <x-adminlte-datatable id="tableComunariosApoyo" :heads="['ID', 'Nombre', 'Entidad perteneciente', 'Acciones']">
            @foreach ($comunariosApoyo as $comunarioApoyo)
                <tr>
                    <td>{{ $comunarioApoyo->id }}</td>
                    <td>{{ $comunarioApoyo->nombre }}</td>
                    <td>{{ $comunarioApoyo->entidad_perteneciente }}</td>
                    <td>
                        <a href="{{ route('comunarios_apoyo.show', $comunarioApoyo) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('comunarios_apoyo.edit', $comunarioApoyo) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('comunarios_apoyo.destroy', $comunarioApoyo) }}" method="POST"
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
