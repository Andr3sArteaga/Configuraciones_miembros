@extends('layouts.app')

@section('title', 'Editar Curso')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Curso</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de Edición</h3>
                    </div>
                    <form id="cursos-update-form" action="{{ route('cursos.update', $curso->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre del Curso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" placeholder="Ej: Primeros Auxilios Básicos"
                                    value="{{ old('nombre', $curso->nombre) }}" required maxlength="200">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Nombre descriptivo del curso (máx. 200
                                    caracteres)</small>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                    name="descripcion" rows="5"
                                    placeholder="Descripción detallada del curso, objetivos y contenido...">{{ old('descripcion', $curso->descripcion) }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Información adicional sobre el curso</small>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inicio_programado">Fecha de inicio <span class="text-danger">*</span></label>
                                    <input type="date"
                                           class="form-control @error('inicio_programado') is-invalid @enderror"
                                           id="inicio_programado"
                                           name="inicio_programado"
                                           value="{{ old('inicio_programado', optional($curso->inicio_programado)->format('Y-m-d')) }}">
                                    @error('inicio_programado')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fin_programado">Fecha de finalización <span class="text-danger">*</span></label>
                                    <input type="date"
                                           class="form-control @error('fin_programado') is-invalid @enderror"
                                           id="fin_programado"
                                           name="fin_programado"
                                           value="{{ old('fin_programado', optional($curso->fin_programado)->format('Y-m-d')) }}">
                                    @error('fin_programado')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="objetivos">Objetivos del curso</label>
                                <textarea class="form-control @error('objetivos') is-invalid @enderror"
                                          id="objetivos"
                                          name="objetivos"
                                          rows="3"
                                          placeholder="Objetivos y resultados esperados">{{ old('objetivos', $curso->objetivos) }}</textarea>
                                @error('objetivos')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        </div>

                        {{-- Etapas y recursos del curso --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-layer-group"></i> Etapas y Recursos del Curso
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        @if ($curso->stages->isEmpty())
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                Este curso aún no tiene etapas ni recursos registrados.
                                            </div>
                                        @else
                                            @foreach ($curso->stages->sortBy('orden') as $stage)
                                                <div class="card mb-3">
                                                    <div class="card-header bg-light">
                                        <strong>{{ $stage->titulo_autogenerado ?? 'Etapa ' . $stage->stage_number }}</strong>
                                                        <span class="text-muted">· {{ $stage->module_name }}</span>
                                                    </div>
                                                    <div class="card-body">
                                                        @php
                                                            $videos = $stage->resources->where('resource_type', 'video');
                                                            $docs = $stage->resources->where('resource_type', 'documento');
                                                            $lecturas = $stage->resources->where('resource_type', 'lectura');
                                                            $extras = $stage->resources->where('resource_type', 'material_extra');
                                                        @endphp

                                                        {{-- Mantener el orden/nombre del módulo para el controlador --}}
                                                        <input type="hidden" name="stages_order[]" value="{{ $stage->module_name }}">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-play-circle text-primary"></i> Video</h6>
                                                                @php $video = $videos->first(); @endphp
                                                                <input type="url"
                                                                       class="form-control form-control-sm mb-2"
                                                                       name="stages[{{ $stage->stage_number }}][video]"
                                                                       placeholder="https://..."
                                                                       value="{{ old('stages.'.$stage->stage_number.'.video', optional($video)->resource_url) }}">
                                                                @if($video && $video->resource_url)
                                                                    <small class="d-block mb-0">
                                                                        <a href="{{ $video->resource_url }}" target="_blank">
                                                                            Ver video actual
                                                                        </a>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-file-alt text-info"></i> Documento</h6>
                                                                @php $doc = $docs->first(); @endphp
                                                                <div class="input-group input-group-sm file-uploader-wrapper mb-2">
                                                                    <input type="text"
                                                                           class="form-control file-uploader-display"
                                                                           placeholder="Ningún archivo seleccionado"
                                                                           value="{{ $doc ? $doc->titulo : '' }}"
                                                                           readonly>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-primary file-uploader-trigger"
                                                                                type="button"
                                                                                data-target="stage_doc_{{ $stage->id }}">
                                                                            Buscar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <input type="file"
                                                                       class="d-none file-uploader-input"
                                                                       id="stage_doc_{{ $stage->id }}"
                                                                       name="stages[{{ $stage->stage_number }}][doc]"
                                                                       accept=".pdf,.doc,.docx,.ppt,.pptx">
                                                                @if($doc && $doc->file_path)
                                                                    <small class="d-block mb-0">
                                                                        <a href="{{ asset('storage/'.$doc->file_path) }}"
                                                                           target="_blank" download>
                                                                            Descargar documento actual ({{ $doc->titulo ?? 'Documento' }})
                                                                        </a>
                                                                    </small>
                                                                @else
                                                                    <small class="text-muted d-block mb-0">Sin documento cargado.</small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-book-reader text-success"></i> Lectura</h6>
                                                                @php $lec = $lecturas->first(); @endphp
                                                                <input type="url"
                                                                       class="form-control form-control-sm mb-2"
                                                                       name="stages[{{ $stage->stage_number }}][reading]"
                                                                       placeholder="https://..."
                                                                       value="{{ old('stages.'.$stage->stage_number.'.reading', optional($lec)->resource_url) }}">
                                                                @if($lec && $lec->resource_url)
                                                                    <small class="d-block mb-0">
                                                                        <a href="{{ $lec->resource_url }}" target="_blank">
                                                                            Ver lectura actual
                                                                        </a>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-sticky-note text-warning"></i> Material extra</h6>
                                                                @php $extra = $extras->first(); @endphp
                                                                <textarea class="form-control form-control-sm"
                                                                          name="stages[{{ $stage->stage_number }}][extra]"
                                                                          rows="2"
                                                                          placeholder="Notas, ayudas, ejercicios">{{ old('stages.'.$stage->stage_number.'.extra', optional($extra)->descripcion) }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de acción al final de toda la página --}}
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('cursos.index') }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('file-uploader-trigger')) {
            const targetId = event.target.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                input.click();
            }
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('file-uploader-input')) {
            const wrapper = event.target.closest('.file-uploader-wrapper');
            if (wrapper) {
                const display = wrapper.querySelector('.file-uploader-display');
                if (display) {
                    display.value = event.target.files.length ? event.target.files[0].name : '';
                }
            }
        }
    });
</script>
@stop
