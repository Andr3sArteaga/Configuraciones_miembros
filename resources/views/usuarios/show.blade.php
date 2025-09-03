@extends('layouts.app')

@section('title', 'Usuarios')
@section('content_header_title', 'Usuarios')
@section('content_header_subtitle', 'Detalle')

@section('content_body')
    <x-adminlte-card title="Detalle de Usuario" theme="primary" icon="fas fa-user">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8">{{ $usuario->nombre }} {{ $usuario->apellido }}</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $usuario->email }}</dd>
                    <dt class="col-sm-4">Teléfono</dt>
                    <dd class="col-sm-8">{{ $usuario->telefono }}</dd>
                    <dt class="col-sm-4">Rol</dt>
                    <dd class="col-sm-8">{{ $usuario->rol }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">{{ $usuario->estado }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </x-adminlte-card>
@stop


