@extends('layouts.app')

@section('title', 'Usuarios')
@section('content_header_title', 'Usuarios')
@section('content_header_subtitle', 'Crear')

@section('content_body')
    <x-adminlte-card title="Nuevo Usuario" theme="primary" icon="fas fa-user-plus">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="nombre" label="Nombre" value="{{ old('nombre') }}" required />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="apellido" label="Apellido" value="{{ old('apellido') }}" required />
                </div>
                <div class="col-md-4 mb-3">
                    <x-adminlte-input name="email" type="email" label="Email" value="{{ old('email') }}" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="telefono" label="Teléfono" value="{{ old('telefono') }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="password" type="password" label="Password" />
                </div>
            </div>

            {{-- Campo agregado: Tipo de Usuario --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-select name="tipo_usuario" label="Tipo de Usuario" required>
                        <option value="">-- Seleccione una opción --</option>
                        <option value="Admin">Admin</option>
                        <option value="Voluntario - Bombero">Voluntario (Bombero)</option>
                        <option value="Voluntario - Policía">Voluntario (Policía)</option>
                        <option value="Voluntario - Rescatista">Voluntario (Rescatista)</option>
                        <option value="Voluntario - Servicios Médicos">Voluntario (Servicios Médicos)</option>
                    </x-adminlte-select>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop
