<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex - {{ $usuario->nombre }} {{ $usuario->apellido }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #F97727;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #F97727;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #e28e59ff;
            color: white;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 8px;
            width: 35%;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #F97727;
        }

        .stat-box .label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table thead {
            background-color: #343a40;
            color: white;
        }

        .data-table th,
        .data-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>KARDEX DE USUARIO</h1>
        <p><strong>Sistema de Gestión de Bomberos - Alas Chiquitanas</strong></p>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    {{-- Información Personal --}}
    <div class="section">
        <div class="section-title">INFORMACIÓN PERSONAL</div>
        <table class="info-table">
            <tr>
                <th>Nombre Completo:</th>
                <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                <th>CI:</th>
                <td>{{ $usuario->ci }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $usuario->email }}</td>
                <th>Teléfono:</th>
                <td>{{ $usuario->telefono ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Género:</th>
                <td>{{ $usuario->genero->descripcion ?? 'No especificado' }}</td>
                <th>Tipo de Sangre:</th>
                <td>{{ $usuario->tipos_sangre->descripcion ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Rol:</th>
                <td>{{ $usuario->role->nombre ?? 'Sin rol' }}</td>
                <th>Nivel de Entrenamiento:</th>
                <td>{{ $usuario->niveles_entrenamiento->nombre ?? 'Sin nivel' }}</td>
            </tr>
        </table>
    </div>

    {{-- Estadísticas --}}
    <div class="section">
        <div class="section-title">ESTADÍSTICAS</div>
        <div class="stats-container">
            <div class="stat-box">
                <div class="number">{{ $estadisticas['total_reportes'] }}</div>
                <div class="label">Reportes Creados</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $estadisticas['reportes_controlados'] }}</div>
                <div class="label">Reportes Controlados</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $estadisticas['total_equipos'] }}</div>
                <div class="label">Equipos Asignados</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $estadisticas['total_cursos'] }}</div>
                <div class="label">Cursos Asignados</div>
            </div>
        </div>
    </div>

    {{-- Equipos --}}
    <div class="section">
        <div class="section-title">MIS EQUIPOS</div>
        @if ($equipos->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre del Equipo</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Miembros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->nombre }}</td>
                            <td>{{ $equipo->especialidad ?? 'No especificada' }}</td>
                            <td>
                                @if ($equipo->estado_id && $equipo->estados_sistema)
                                    {{ $equipo->estados_sistema->nombre }}
                                @else
                                    Sin estado
                                @endif
                            </td>
                            <td>{{ $equipo->cantidad_integrantes ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No pertenece a ningún equipo actualmente</div>
        @endif
    </div>

    {{-- Reportes de Incendio --}}
    <div class="section">
        <div class="section-title">MIS REPORTES DE INCENDIO</div>
        @if ($reportes->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre del Incidente</th>
                        <th>Extensión</th>
                        <th>Bomberos</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportes as $reporte)
                        <tr>
                            <td>{{ $reporte->fecha_creacion ? $reporte->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>{{ $reporte->nombre_incidente }}</td>
                            <td>{{ $reporte->extension ? number_format($reporte->extension, 2) . ' ha' : 'N/A' }}</td>
                            <td>{{ $reporte->numero_bomberos ?? 'N/A' }}</td>
                            <td>
                                @if ($reporte->controlado)
                                    Controlado
                                @else
                                    Activo
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No ha creado ningún reporte de incendio</div>
        @endif
    </div>

    {{-- Cursos --}}
    <div class="section">
        <div class="section-title">MIS CURSOS</div>
        @if ($cursos->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre del Curso</th>
                        <th>Descripción</th>
                        <th>Fecha Asignación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $cursoAsignado)
                        <tr>
                            <td>{{ $cursoAsignado->curso->nombre ?? 'N/A' }}</td>
                            <td>{{ \Str::limit($cursoAsignado->curso->descripcion ?? 'Sin descripción', 80) }}</td>
                            <td>{{ $cursoAsignado->fecha_asignacion ? $cursoAsignado->fecha_asignacion->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No tiene cursos asignados actualmente</div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestión de Bomberos - Alas Chiquitanas | Documento generado automáticamente</p>
    </div>
</body>

</html>
