@extends('layouts.app')

@section('title', 'Comunarios de Apoyo')
@section('content_header_title', 'Comunarios de Apoyo')
@section('content_header_subtitle', 'Editar')

@section('content_body')
    <x-adminlte-card title="Editar Comunario de Apoyo" theme="primary" icon="fas fa-user-edit">
        <form action="{{ route('comunarios_apoyo.update', $comunario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="nombre" label="Nombre" value="{{ old('nombre', $comunario->nombre) }}" required />
                </div>
                <div class="col-md-6 mb-3">
                    <x-adminlte-input name="entidad_perteneciente" label="Entidad perteneciente"
                        value="{{ old('entidad_perteneciente', $comunario->entidad_perteneciente) }}" required />
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('comunarios_apoyo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </x-adminlte-card>
@stop
