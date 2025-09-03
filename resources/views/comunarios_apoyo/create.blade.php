@extends('layouts.app')

@section('title', 'Comunarios de Apoyo')
@section('content_header_title', 'Comunarios de Apoyo')
@section('content_header_subtitle', 'Crear')

@section('content_body')
    <x-adminlte-card title="Nuevo Comunario de Apoyo" theme="primary" icon="fas fa-user-friends">
        <form action="{{ route('comunarios_apoyo.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre" label="Nombre" value="{{ old('nombre') }}" required />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="entidad_perteneciente" label="Entidad perteneciente" value="{{ old('entidad_perteneciente') }}" required />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('comunarios_apoyo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop


