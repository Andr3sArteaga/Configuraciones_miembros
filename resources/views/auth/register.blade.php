@extends('adminlte::auth.register')

@section('auth_header')
    <p class="login-box-msg">Registro de Usuario - Sistema de Bomberos</p>
@stop

@section('auth_body')
    <form action="{{ route('register') }}" method="post">
        @csrf

        {{-- Campo Nombre --}}
        <div class="input-group mb-3">
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}" placeholder="Nombre" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('nombre')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Apellido --}}
        <div class="input-group mb-3">
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                   value="{{ old('apellido') }}" placeholder="Apellido" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('apellido')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo CI --}}
        <div class="input-group mb-3">
            <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror"
                   value="{{ old('ci') }}" placeholder="Cédula de Identidad" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                </div>
            </div>
            @error('ci')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Fecha de Nacimiento --}}
        <div class="input-group mb-3">
            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                   value="{{ old('fecha_nacimiento') }}" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-calendar"></span>
                </div>
            </div>
            @error('fecha_nacimiento')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Teléfono (Opcional) --}}
        <div class="input-group mb-3">
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono') }}" placeholder="Teléfono (opcional)">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
            @error('telefono')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Email --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Correo electrónico" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Confirmar Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Confirmar contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Botón de Registro --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="mb-1">
        <a href="{{ route('login') }}">Ya tengo una cuenta</a>
    </p>
    <p class="mb-0">
        <small class="text-muted">
            Al registrarte, aceptas nuestros términos y condiciones
        </small>
    </p>
@stop