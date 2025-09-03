@extends('layouts.app')

@section('title', 'Comunarios de Apoyo')
@section('content_header_title', 'Comunarios de Apoyo')
@section('content_header_subtitle', 'Detalle')

@section('content_body')
    <x-adminlte-card title="Detalle del Comunario de Apoyo" theme="primary" icon="fas fa-user">
        <div class="row">
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8">{{ $comunario->nombre }}</dd>
                    <dt class="col-sm-4">Entidad perteneciente</dt>
                    <dd class="col-sm-8">{{ $comunario->entidad_perteneciente }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('comunarios_apoyo.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('comunarios_apoyo.edit', $comunario) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </x-adminlte-card>
@stop


