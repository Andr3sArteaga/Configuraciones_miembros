@extends('layouts.app')

@section('title', 'Usuarios')
@section('content_header_title', 'Usuarios')
@section('content_header_subtitle', 'Listado')

@section('content_body')
    <x-adminlte-card title="Usuarios" theme="orange" icon="fas fa-users">
        <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>

        <x-adminlte-datatable id="tableUsuarios" :heads="['ID', 'Nombre', 'Email', 'Acciones']">
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
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
