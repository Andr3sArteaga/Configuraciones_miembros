@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content_header')
    <h1>Tus notificaciones</h1>
@stop

@section('content')
    @foreach ($notifications as $notification)
        @php
            $title = $notification->data['title'] ?? 'Notificación sin título';
            $message = $notification->data['message'] ?? 'Sin descripción disponible.';
            $time = $notification->created_at->diffForHumans();
            $antiguedad = $notification->created_at > now()->subDay() ? 'Nueva notificación' : 'Notificación Pasada';
            $icono =
                $notification->created_at > now()->subDay() ? 'fas fa-bell text-warning' : 'fas fa-bell text-muted';
        @endphp

        <x-adminlte-card title="{{ $title }}" theme="blue" icon="{{ $icono }}">
            <!-- contenido de la tarjeta -->
            <b style=" {{ $antiguedad == 'Nueva notificación' ? 'color: #e9ca03' : '' }}"> <i>{{ $antiguedad }}</i></b>
            <p>{{ $message }}</p>
            <small class="text-muted">{{ $time }}</small>
        </x-adminlte-card>
    @endforeach
@endsection
