@extends('adminlte::page')

@section('title', 'Perfil de usuario')

@section('content_header')
    <h1 class="mb-3 text-center text-danger fw-bold">Mi Perfil</h1>
@stop

@section('content')
<div class="row">

    <!-- COLUMNA IZQUIERDA -->
    <div class="col-md-4">
        <div class="card card-widget widget-user shadow">
            <div class="widget-user-header text-white" style="background-color: #ff7a00;">
                <h3 class="widget-user-username">{{ $user->name ?? 'Andrés Arteaga' }}</h3>
                <h5 class="widget-user-desc">{{ $user->is_admin ? 'Administrador' : 'Voluntario' }}</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'FT') }}&background=ff7a00&color=fff" alt="User Avatar">
            </div>
            <div class="card-footer pt-4">
                <div class="text-center mb-3" style="margin-top: 30px;">
                    <span class="badge bg-danger p-2 rounded-pill">Inactivo</span>
                </div>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>CI</b> <span class="float-right">8837823</span>
                    </li>
                    <li class="list-group-item">
                        <b>Fecha de nacimiento</b> <span class="float-right">10/04/2003</span>
                    </li>
                    <li class="list-group-item">
                        <b>Género</b> <span class="float-right">Masculino</span>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right">arteagaaguileraandres@gmail.com</span>
                    </li>
                    <li class="list-group-item">
                        <b>Teléfono</b> <span class="float-right">77384093</span>
                    </li>
                    <li class="list-group-item">
                        <b>Entidad</b> <span class="float-right">Bomberos</span>
                    </li>
                    <li class="list-group-item">
                        <b>Rol</b> <span class="float-right">Voluntario</span>
                    </li>
                    <li class="list-group-item">
                        <b>Miembro desde</b> <span class="float-right">29/09/2025</span>
                    </li>
                </ul>

                <div class="text-center">
                    <button type="button" class="btn btn-warning text-white fw-bold" data-toggle="modal" data-target="#modalEditarPerfil">
                        <i class="fas fa-edit"></i> Editar perfil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="col-md-8">

        <!-- Tarjeta: Información de emergencia -->
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title text-danger mb-0"><i class="fas fa-first-aid"></i> Información de emergencia</h5>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="p-3 rounded bg-warning bg-opacity-25">
                        <b>Tipo de sangre:</b>
                        <span class="text-danger fw-bold ms-2">O-</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded bg-primary bg-opacity-10">
                        <b>Nivel de entrenamiento:</b>
                        <span class="text-primary fw-bold ms-2">Bajo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Datos de contacto -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title text-danger mb-0"><i class="fas fa-address-book"></i> Datos de contacto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><b>Teléfono:</b> 77384093</p>
                        <p><b>Entidad:</b> Bomberos</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Correo electrónico:</b> arteagaaguileraandres@gmail.com</p>
                        <p><b>Rol:</b> Voluntario</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL EDITAR PERFIL -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" role="dialog" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white" id="modalEditarPerfilLabel">Editar perfil</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña (opcional)</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-white">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.widget-user-header {
    border-top-left-radius: 10px !important;
    border-top-right-radius: 10px !important;
}
.widget-user .widget-user-image img {
    width: 90px;
    height: 90px;
    object-fit: cover;
}
.widget-user .widget-user-header h3,
.widget-user .widget-user-header h5 {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}
.card-title i {
    margin-right: 5px;
}
</style>
@stop
