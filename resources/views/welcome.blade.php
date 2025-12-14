@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Bienvenido')
@section('content_header_title', 'Alas Chiquitanas')
@section('content_header_subtitle', 'Centro de Control Ambiental')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        {{-- Mensaje de Bienvenida Principal --}}
        <div class="col-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-leaf"></i> Bienvenido al Sistema de Gestión - Alas Chiquitanas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-success mb-3">🌿 Organización Voluntaria de Conservación Ambiental</h4>
                            <p class="lead mb-3">
                                Este dashboard es el centro de operaciones para la gestión de actividades de conservación,
                                monitoreo ambiental y respuesta ante emergencias en la Chiquitania.
                            </p>
                            <p>
                                Desde aquí podrás acceder a todas las herramientas necesarias para documentar, reportar
                                y coordinar nuestras acciones de protección de la biodiversidad y los ecosistemas locales.
                            </p>
                        </div>
                        <div class="col-md-4 text-center"
                            style="display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tree" style="font-size: 120px; color: #28a745; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Guía de Navegación --}}
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-signs"></i> Guía de Navegación del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <p class="mb-4">
                        El sistema está organizado en módulos especializados. A continuación te explicamos
                        cómo navegar y utilizar cada sección:
                    </p>

                    <div class="row">
                        {{-- Módulo de Usuarios --}}
                        <div class="col-md-6 mb-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary elevation-1">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Gestión de Usuarios</strong></span>
                                    <span class="info-box-number text-sm">
                                        Administra los voluntarios y personal de la organización. Desde el menú lateral
                                        accede a "Usuarios" para crear nuevos perfiles, editar información existente,
                                        visualizar listados completos y consultar detalles específicos de cada miembro.
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Módulo de Comunarios de Apoyo --}}
                        <div class="col-md-6 mb-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-hands-helping"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Comunarios de Apoyo</strong></span>
                                    <span class="info-box-number text-sm">
                                        Registra y gestiona la información de los miembros de las comunidades locales que
                                        colaboran con nuestras actividades de conservación. Mantén actualizado el directorio
                                        de aliados comunitarios, sus contactos y áreas de participación.
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Módulo de Recursos --}}
                        <div class="col-md-6 mb-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-box-open"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Recursos y Materiales</strong></span>
                                    <span class="info-box-number text-sm">
                                        Controla el inventario de equipos, herramientas y materiales disponibles para las
                                        operaciones de campo. Registra nuevos recursos, actualiza su estado, consulta
                                        disponibilidad y realiza seguimiento de su uso y mantenimiento.
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Módulo de Reportes Generales --}}
                        <div class="col-md-6 mb-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Reportes de Actividades</strong></span>
                                    <span class="info-box-number text-sm">
                                        Documenta todas las actividades de conservación, monitoreo de fauna, reforestación,
                                        talleres educativos y proyectos comunitarios. Crea reportes detallados, adjunta
                                        evidencias fotográficas y genera históricos de intervenciones realizadas.
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Módulo de Reportes de Incendios --}}
                        <div class="col-md-12 mb-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-fire"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Reportes de Incendios Forestales</strong></span>
                                    <span class="info-box-number text-sm">
                                        <strong>Módulo de Emergencia:</strong> Sistema especializado para reportar,
                                        documentar y hacer seguimiento a incendios forestales. Permite registrar la
                                        ubicación exacta, extensión del área afectada, recursos comprometidos, acciones
                                        de respuesta y estado de los focos de incendio. Fundamental para la coordinación
                                        con autoridades y respuesta rápida ante emergencias ambientales.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Instrucciones de Uso --}}
        <div class="col-md-6">
            <div class="card card-outline card-teal">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i> ¿Cómo empezar?
                    </h3>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">
                            <strong>Explora el menú lateral:</strong> Todas las secciones están organizadas
                            en el panel izquierdo de tu pantalla.
                        </li>
                        <li class="mb-2">
                            <strong>Acciones disponibles:</strong> En cada módulo encontrarás opciones para:
                            <ul class="mt-2">
                                <li><i class="fas fa-list text-primary"></i> Ver listado completo (Index)</li>
                                <li><i class="fas fa-plus text-success"></i> Crear nuevo registro (Create)</li>
                                <li><i class="fas fa-eye text-info"></i> Ver detalles (Show)</li>
                                <li><i class="fas fa-edit text-warning"></i> Editar información (Edit)</li>
                            </ul>
                        </li>
                        <li class="mb-2">
                            <strong>Perfil de usuario:</strong> Accede a tu información personal desde
                            el menú de perfil en la esquina superior derecha.
                        </li>
                        <li class="mb-0">
                            <strong>Búsqueda y filtros:</strong> Utiliza las herramientas de búsqueda en cada
                            módulo para encontrar información específica rápidamente.
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Consejos Importantes --}}
        <div class="col-md-6">
            <div class="card card-outline card-orange">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> Consejos Importantes
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Reportes de Incendios</h5>
                        <p class="mb-0">
                            Los reportes de incendios forestales son de <strong>máxima prioridad</strong>.
                            Asegúrate de incluir coordenadas GPS, hora exacta de detección y fotos si es posible.
                        </p>
                    </div>

                    <div class="callout callout-info">
                        <h5><i class="icon fas fa-save"></i> Guarda tu trabajo</h5>
                        <p class="mb-0">
                            Recuerda guardar regularmente la información que ingresas. Los formularios largos
                            pueden perder datos si la sesión expira.
                        </p>
                    </div>

                    <div class="callout callout-success">
                        <h5><i class="icon fas fa-camera"></i> Documentación visual</h5>
                        <p class="mb-0">
                            Siempre que sea posible, adjunta fotografías a tus reportes. La evidencia visual
                            es fundamental para el seguimiento y análisis de impacto.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas Rápidas --}}
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Vista Rápida del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success">
                                    <i class="fas fa-users"></i>
                                </span>
                                <h5 class="description-header">--</h5>
                                <span class="description-text">Voluntarios Activos</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-info">
                                    <i class="fas fa-hands-helping"></i>
                                </span>
                                <h5 class="description-header">--</h5>
                                <span class="description-text">Comunarios de Apoyo</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-warning">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <h5 class="description-header">--</h5>
                                <span class="description-text">Reportes este Mes</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <span class="description-percentage text-danger">
                                    <i class="fas fa-fire"></i>
                                </span>
                                <h5 class="description-header">--</h5>
                                <span class="description-text">Incendios Reportados</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted mt-3 mb-0">
                        <small>Los contadores se actualizarán automáticamente conforme uses el sistema</small>
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer de Bienvenida --}}
        <div class="col-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-seedling"></i> ¡Gracias por tu compromiso con la conservación!</h5>
                <p class="mb-0">
                    Cada registro, cada reporte y cada acción documentada contribuye a la protección de la
                    biodiversidad de la Chiquitania. Tu trabajo voluntario es fundamental para nuestra misión.
                </p>
            </div>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    <style>
        .info-box {
            min-height: 140px;
        }

        .info-box-content {
            padding: 10px;
        }

        .callout {
            border-left-width: 5px;
        }

        .card {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        }

        .description-block {
            padding: 20px 0;
        }

        .description-header {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script>
        console.log("Dashboard Alas Chiquitanas - Sistema de Gestión Ambiental");

        $(document).ready(function() {
            // Animación suave de entrada
            $('.card, .alert').hide().fadeIn(1000);

            // Aquí podrás agregar lógica para cargar estadísticas reales
            // Ejemplo:
            // $.ajax({...}) para obtener datos de la base de datos
        });
    </script>
@endpush
