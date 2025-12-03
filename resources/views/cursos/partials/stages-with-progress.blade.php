{{-- Congratulations message if course is completed --}}
@if($authUsuarioAsignado && $courseCompleted)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4><i class="icon fas fa-trophy"></i> ¡Felicidades!</h4>
                Felicidades, has terminado el curso <strong>{{ $curso->nombre }}</strong> de manera eficaz.
            </div>
        </div>
    </div>
@endif

{{-- Etapas del Curso --}}
@if($curso->stages && $curso->stages->count() > 0)
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group"></i> Etapas del Curso
                    </h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="stagesAccordion">
                        @foreach($curso->stages as $stage)
                            @php
                                // Find progress for this stage
                                $progress = collect($userProgress)->firstWhere('stage_id', $stage->id);
                                $isLocked = $progress && $progress['is_locked'];
                                $isAvailable = $progress && $progress['is_available'];
                                $isCompleted = $progress && $progress['is_completed'];
                                $isApproved = $progress && $progress['is_approved'];
                                $isPending = $progress && $progress['is_pending'];
                            @endphp

                            <div class="card">
                                <div class="card-header" id="heading{{ $stage->id }}">
                                    <h2 class="mb-0 d-flex justify-content-between align-items-center">
                                        <button class="btn btn-link btn-block text-left" type="button" 
                                                data-toggle="collapse" 
                                                data-target="#collapse{{ $stage->id }}" 
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $stage->id }}"
                                                @if($isLocked) disabled @endif>
                                            <i class="fas fa-book-open"></i> <strong>{{ $stage->titulo_autogenerado }}</strong>
                                            @if($stage->module_name)
                                                <span class="badge badge-primary ml-2">{{ $stage->module_name }}</span>
                                            @endif
                                        </button>
                                        
                                        {{-- Stage status badge --}}
                                        @if($authUsuarioAsignado && $progress)
                                            @if($isLocked)
                                                <span class="badge badge-secondary badge-sm ml-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-lock"></i> Bloqueado
                                                </span>
                                            @elseif($isApproved)
                                                <span class="badge badge-success badge-sm ml-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-check-circle"></i> Aprobado
                                                </span>
                                            @elseif($isPending)
                                                <span class="badge badge-warning badge-sm ml-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-clock"></i> Pendiente Aprobación
                                                </span>
                                            @elseif($isAvailable)
                                                <span class="badge badge-info badge-sm ml-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-unlock"></i> Disponible
                                                </span>
                                            @endif
                                        @endif
                                    </h2>
                                </div>

                                <div id="collapse{{ $stage->id }}" 
                                     class="collapse {{ $loop->first && !$isLocked ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $stage->id }}" 
                                     data-parent="#stagesAccordion">
                                    <div class="card-body">
                                        @if($stage->descripcion)
                                            <p><strong>Descripción:</strong> {{ $stage->descripcion }}</p>
                                        @endif
                                        
                                        @if($stage->duracion_minutos)
                                            <p><strong>Duración:</strong> {{ $stage->duracion_minutos }} minutos</p>
                                        @endif

                                        @if($stage->delivery_mode)
                                            <p><strong>Modalidad:</strong> {{ $stage->delivery_mode }}</p>
                                        @endif

                                        {{-- Recursos de esta etapa --}}
                                        @if($stage->resources && $stage->resources->count() > 0)
                                            <hr>
                                            <h5><i class="fas fa-file-alt"></i> Recursos:</h5>
                                            <div class="list-group">
                                                @foreach($stage->resources as $resource)
                                                    <div class="list-group-item">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">
                                                                @if($resource->resource_type === 'video')
                                                                    <i class="fas fa-video text-danger"></i>
                                                                @elseif($resource->resource_type === 'documento')
                                                                    <i class="fas fa-file-pdf text-primary"></i>
                                                                @elseif($resource->resource_type === 'lectura')
                                                                    <i class="fas fa-book text-success"></i>
                                                                @else
                                                                    <i class="fas fa-file text-secondary"></i>
                                                                @endif
                                                                {{ $resource->titulo ?? 'Recurso sin título' }}
                                                            </h6>
                                                            <small>
                                                                <span class="badge badge-info">{{ ucfirst($resource->resource_type) }}</span>
                                                            </small>
                                                        </div>
                                                        @if($resource->descripcion)
                                                            <p class="mb-1 text-muted">{{ $resource->descripcion }}</p>
                                                        @endif
                                                        @if($resource->resource_url)
                                                            <a href="{{ $resource->resource_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                                <i class="fas fa-external-link-alt"></i> Ver recurso
                                                            </a>
                                                        @endif
                                                        @if($resource->file_path)
                                                            <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success mt-2">
                                                                <i class="fas fa-download"></i> Descargar
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted"><i>No hay recursos disponibles para esta etapa.</i></p>
                                        @endif

                                        {{-- Completion button for enrolled users --}}
                                        @if($authUsuarioAsignado && $isAvailable && !$isCompleted)
                                            <hr>
                                            <form action="{{ route('cursos.stages.complete', [$curso->id, $stage->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('¿Marcar esta etapa como completada?')">
                                                    <i class="fas fa-check"></i> Marcar como Completado
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
