@extends('layouts.app')

@section('title', 'Usuarios')
@section('content_header_title', 'Usuarios')
@section('content_header_subtitle', 'Editar')

@section('content_body')
    <x-adminlte-card title="Editar Usuario" theme="primary" icon="fas fa-user-edit">
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre" label="Nombre" value="{{ old('nombre', $usuario->nombre) }}" required />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="email" type="email" label="Email" value="{{ old('email', $usuario->email) }}" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="telefono" label="Teléfono" value="{{ old('telefono', $usuario->telefono) }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="password" type="password" label="Password (opcional)" />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop


