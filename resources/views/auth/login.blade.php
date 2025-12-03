@extends('adminlte::master')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php($login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login'))
@php($register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register'))
@php($password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset'))

@if (config('adminlte.use_route_url', false))
    @php($login_url = $login_url ? route($login_url) : '')
    @php($register_url = $register_url ? route($register_url) : '')
    @php($password_reset_url = $password_reset_url ? route($password_reset_url) : '')
@else
    @php($login_url = $login_url ? url($login_url) : '')
    @php($register_url = $register_url ? url($register_url) : '')
    @php($password_reset_url = $password_reset_url ? url($password_reset_url) : '')
@endif

@section('classes_body', 'login-page')

@section('body')
    <!-- Cycling Background Images -->
    <div class="background-slider">
        <div class="bg-image active" style="background-image: url('{{ asset('assets/img/Incendios_bg_4.jpg') }}');"></div>
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/Incendios_bg_2.jpg') }}');"></div>
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/Incendios_bg_3.jpg') }}');"></div>
    </div>

    <div class="login-box">
        {{-- Logo --}}
        <div class="login-logo">
            <a href="{{ config('adminlte.dashboard_url', 'home') }}">
                <img src="{{ asset(config('adminlte.logo_img')) }}" alt="{{ config('adminlte.logo_img_alt') }}"
                    height="50">
                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
            </a>
        </div>

        {{-- Card Box --}}
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('adminlte::adminlte.login_message') }}</p>

                {{-- Login Form --}}
                <form action="{{ $login_url }}" method="post">
                    @csrf

                    {{-- Email field --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Password field --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('adminlte::adminlte.password') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Login field --}}
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                                <input type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    {{ __('adminlte::adminlte.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block">
                                <span class="fas fa-sign-in-alt"></span>
                                {{ __('adminlte::adminlte.sign_in') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Password reset link
                @if ($password_reset_url)
                    <p class="mb-1">
                        <a href="{{ $password_reset_url }}">
                            {{ __('adminlte::adminlte.i_forgot_my_password') }}
                        </a>
                    </p>
                @endif --}}

                {{-- Register link --}}
                @if ($register_url)
                    <p class="mb-1">
                        <a href="{{ $register_url }}">
                            {{ __('adminlte::adminlte.register_a_new_membership') }}
                        </a>
                    </p>
                @endif

                {{-- Guest access link --}}
                <p class="mb-0">
                    <a href="{{ route('guest.home') }}" class="text-info">
                        <i class="fas fa-user-shield"></i>
                        Acceder como Invitado
                    </a>
                </p>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        /* Override AdminLTE login page background */
        body.login-page {
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
            background-color: #e9ecef;
            /* Fallback color */
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

        /* Add overlay to make login form more readable */
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

        /* Ensure login box is above the overlay */
        .login-box {
            position: relative;
            z-index: 2;
        }

        /* Make login card slightly transparent for better visual effect */
        .login-box .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Style the logo area */
        .login-logo {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }

        .login-logo a {
            color: #495057;
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
