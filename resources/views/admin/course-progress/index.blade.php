@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-tasks"></i> Aprobaciones Pendientes de Etapas
                    </h3>
                </div>

                <div class="card-body">
                    @if(count($pendingCompletions) === 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No hay etapas pendientes de aprobación en este momento.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Curso</th>
                                        <th>Etapa</th>
                                        <th>Completado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingCompletions as $completion)
                                        <tr>
                                            <td>
                                                <strong>{{ $completion['usuario_nombre'] }}</strong>
                                            </td>
                                            <td>{{ $completion['usuario_email'] }}</td>
                                            <td>{{ $completion['curso_nombre'] }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    Etapa {{ $completion['stage_number'] }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $completion['stage_title'] }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($completion['completed_at'])->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.course-progress.approve', $completion['progress_id']) }}" 
                                                      method="POST" 
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('¿Aprobar esta etapa? Esto desbloqueará la siguiente etapa para el usuario.')">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
