@extends('layouts.app')

@section('title', 'Usuarios')
@section('content_header_title', 'Usuarios')
@section('content_header_subtitle', 'Crear')

@section('content_body')
    <x-adminlte-card title="Nuevo Usuario" theme="primary" icon="fas fa-user-plus">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre" label="Nombre" value="{{ old('nombre') }}" required />
                </div>
                <div class="col-md-6 mb-3">
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


