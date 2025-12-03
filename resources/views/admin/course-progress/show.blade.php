@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-line"></i> Progreso del Curso: {{ $curso->nombre }}
                    </h3>
                    <a href="{{ route('admin.course-progress.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    @if(count($userProgress) === 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No hay usuarios inscritos en este curso.
                        </div>
                    @else
                        @foreach($userProgress as $userId => $data)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user"></i>
                                        {{ $data['usuario']->nombre }} {{ $data['usuario']->apellido }}
                                        <small class="text-muted">({{ $data['usuario']->email }})</small>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Etapa</th>
                                                    <th>Módulo</th>
                                                    <th>Estado</th>
                                                    <th>Completado</th>
                                                    <th>Revisado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['progress'] as $progress)
                                                    <tr>
                                                        <td>
                                                            <strong>Etapa {{ $progress['stage_number'] }}</strong>
                                                        </td>
                                                        <td>{{ $progress['module_name'] }}</td>
                                                        <td>
                                                            @if($progress['is_locked'])
                                                                <span class="badge badge-secondary">
                                                                    <i class="fas fa-lock"></i> Bloqueado
                                                                </span>
                                                            @elseif($progress['is_approved'])
                                                                <span class="badge badge-success">
                                                                    <i class="fas fa-check-circle"></i> Aprobado
                                                                </span>
                                                            @elseif($progress['is_pending'])
                                                                <span class="badge badge-warning">
                                                                    <i class="fas fa-clock"></i> Pendiente
                                                                </span>
                                                            @elseif($progress['is_available'])
                                                                <span class="badge badge-info">
                                                                    <i class="fas fa-unlock"></i> Disponible
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($progress['completed_at'])
                                                                <small>{{ \Carbon\Carbon::parse($progress['completed_at'])->format('d/m/Y H:i') }}</small>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($progress['reviewed_at'])
                                                                <small>{{ \Carbon\Carbon::parse($progress['reviewed_at'])->format('d/m/Y H:i') }}</small>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($progress['is_pending'])
                                                                <form action="{{ route('admin.course-progress.approve', $progress['progress_id']) }}" 
                                                                      method="POST" 
                                                                      style="display: inline;">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="btn btn-success btn-sm"
                                                                            onclick="return confirm('¿Aprobar esta etapa?')">
                                                                        <i class="fas fa-check"></i> Aprobar
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
