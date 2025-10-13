@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content_header')
    <h1>Tus notificaciones</h1>
@stop

@section('content')
    <ul class="list-group">
        @foreach ($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $notification->message }}
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @endforeach
    </ul>
@stop
