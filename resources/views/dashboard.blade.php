@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Panel principal')

@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Estadísticas rápidas')

@section('content_body')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>150</h3>
                    <p>Usuarios Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@stop
