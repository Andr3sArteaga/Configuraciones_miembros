@extends('adminlte::master')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
@endif

@section('classes_body', 'register-page')

@section('body')
    <!-- Cycling Background Images -->
    <div class="background-slider">
        <div class="bg-image active" style="background-image: url('{{ asset('assets/img/Incendios_bg_1.jpg') }}');"></div>
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/Incendios_bg_2.jpg') }}');"></div>
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/Incendios_bg_3.jpg') }}');"></div>
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/Incendios_bg_4.jpg') }}');"></div>
    </div>

    <div class="register-box">
        {{-- Logo --}}
        <div class="register-logo">
            <a href="{{ config('adminlte.dashboard_url', 'home') }}">
                <img src="{{ asset(config('adminlte.logo_img')) }}" alt="{{ config('adminlte.logo_img_alt') }}" height="50">
                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
            </a>
        </div>

        {{-- Card Box --}}
        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Registro de Usuario - Sistema de Bomberos</p>

                {{-- Registration Form --}}
                <form action="{{ route('register') }}" method="post">
                    @csrf

                    {{-- Fila 1: Nombre y Apellido --}}
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>

                    {{-- Fila 2: CI y Fecha de Nacimiento --}}
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                       value="{{ old('fecha_nacimiento') }}" placeholder="Fecha de Nacimiento" required>
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
                        </div>
                    </div>

                    {{-- Fila 3: Género y Tipo de Sangre --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <select name="genero_id" class="form-control @error('genero_id') is-invalid @enderror" required>
                                    <option value="">Género</option>
                                    @foreach(\App\Models\Genero::where('activo', true)->get() as $genero)
                                        <option value="{{ $genero->id }}" {{ old('genero_id') == $genero->id ? 'selected' : '' }}>
                                            {{ $genero->descripcion ?? $genero->codigo }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-venus-mars"></span>
                                    </div>
                                </div>
                                @error('genero_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <select name="tipo_sangre_id" class="form-control @error('tipo_sangre_id') is-invalid @enderror" required>
                                    <option value="">Tipo de Sangre</option>
                                    @foreach(\App\Models\TiposSangre::where('activo', true)->get() as $tipoSangre)
                                        <option value="{{ $tipoSangre->id }}" {{ old('tipo_sangre_id') == $tipoSangre->id ? 'selected' : '' }}>
                                            {{ $tipoSangre->codigo }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-tint"></span>
                                    </div>
                                </div>
                                @error('tipo_sangre_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Fila 4: Rol y Teléfono --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <select name="rol_id" class="form-control @error('rol_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar Rol</option>
                                    @foreach(\App\Models\Role::where('activo', true)
                                        ->whereIn('codigo', ['BOMBERO', 'PARAMEDICO', 'VETERINARIO'])
                                        ->get() as $rol)
                                        <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user-tag"></span>
                                    </div>
                                </div>
                                @error('rol_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>

                    {{-- Email (ancho completo) --}}
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

                    {{-- Fila 5: Contraseñas --}}
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control"
                                       placeholder="Confirmar contraseña" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
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

                {{-- Login link --}}
                @if($login_url)
                    <p class="mb-1 mt-3">
                        <a href="{{ $login_url }}">Ya tengo una cuenta</a>
                    </p>
                @endif
                
                <p class="mb-0">
                    <small class="text-muted">
                        Al registrarte, aceptas nuestros términos y condiciones
                    </small>
                </p>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        /* Override AdminLTE register page background */
        body.register-page {
            background-color: transparent;
        }

        /* Background slider container */
        .background-slider {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background-color: #e9ecef; /* Fallback color */
        }

        /* Individual background images */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        /* Active image is visible */
        .bg-image.active {
            opacity: 1;
        }

        /* Add overlay to make register form more readable */
        .background-slider::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Ensure register box is above the overlay */
        .register-box {
            position: relative;
            z-index: 2;
            width: 600px !important;
            max-width: 95% !important;
        }

        /* Make register card slightly transparent for better visual effect */
        .register-box .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Style the logo area */
        .register-logo {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }

        .register-logo a {
            color: #495057;
        }

        /* Mejorar el espaciado entre filas */
        .register-box .row {
            margin-bottom: 0;
        }
        
        /* Background blanco limpio para inputs */
        .register-box .input-group .form-control {
            background-color: #ffffff !important;
        }
        
        /* Ajustar el fondo de los botones de input-group */
        .register-box .input-group-append .input-group-text {
            background-color: #e9ecef !important;
        }
        
        /* Mejorar contraste de los selects */
        .register-box select.form-control {
            background-color: #ffffff !important;
        }
        
        /* Espaciado entre campos en pantallas pequeñas */
        @media (max-width: 767px) {
            .register-box .col-md-6 .input-group {
                margin-bottom: 1rem;
            }
        }
    </style>
@stop

@section('adminlte_js')
    <script>
        // Cycling background images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.bg-image');
            let currentIndex = 0;

            function cycleImages() {
                // Remove active class from current image
                images[currentIndex].classList.remove('active');
                
                // Move to next image
                currentIndex = (currentIndex + 1) % images.length;
                
                // Add active class to next image
                images[currentIndex].classList.add('active');
            }

            // Change image every 5 seconds
            setInterval(cycleImages, 5000);
        });
    </script>
@stop