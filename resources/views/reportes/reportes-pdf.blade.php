<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Reportes Rápidos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .main-title {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            margin: 0 0 0 0;
            border-radius: 0;
        }

        .main-title h1 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .main-title h2 {
            font-size: 14px;
            font-weight: normal;
            opacity: 0.95;
            margin: 0;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 10px 15px;
            margin: 0 0 15px 0;
        }

        .header-info .info-item {
            display: inline-block;
        }

        .header-info strong {
            color: #667eea;
            font-size: 11px;
        }

        .header-info span {
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        thead th {
            padding: 10px 6px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr.falso-positivo {
            background-color: #ffebee !important;
        }

        tbody td {
            padding: 8px 6px;
            font-size: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        tbody td.text-left {
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            color: white;
            text-align: center;
            white-space: nowrap;
        }

        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; }
        .badge-success { background-color: #28a745; }
        .badge-secondary { background-color: #6c757d; }
        .badge-primary { background-color: #007bff; }

        .text-muted {
            color: #6c757d;
            font-size: 7px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #667eea;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
        }

        .comment-row {
            background-color: #fff3cd !important;
        }

        .comment-row td {
            text-align: left;
            font-style: italic;
            padding-left: 15px;
        }

        .resource-badges {
            white-space: nowrap;
        }

        .resource-badges .badge {
            margin: 2px;
            font-size: 6px;
        }
    </style>
</head>
<body>
    <div class="main-title">
        <h1>Alas Chiquitanas</h1>
        <h2>Gestión de Reportes de Incendios y Brigadas</h2>
    </div>

    <div class="header-info">
        <div class="info-item">
            <strong>Total de Reportes:</strong> <span>{{ $reportes->count() }}</span>
        </div>
        <div class="info-item">
            <strong>Pendientes:</strong> <span>{{ $reportes->filter(function($r) { return $r->estados_sistema && strcasecmp($r->estados_sistema->nombre, 'pendiente') === 0; })->count() }}</span>
        </div>
        <div class="info-item">
            <strong>Generado:</strong> <span>{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Fecha<br>Hora</th>
                <th style="width: 12%;">Reportante</th>
                <th style="width: 12%;">Lugar</th>
                <th style="width: 10%;">Tipo</th>
                <th style="width: 8%;">Gravedad</th>
                <th style="width: 8%;">Estado</th>
                <th style="width: 12%;">Recursos Solicitados</th>
                <th style="width: 8%;">Contacto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $reporte)
                @php
                    $estado = strtolower(trim($reporte->estados_sistema->nombre ?? ''));
                    $falsoPositivo = in_array($estado, ['falso positivo', 'falsopositivo', 'falso_positivo']);
                @endphp
                <tr @if($falsoPositivo) class="falso-positivo" @endif>
                    <td>{{ $reporte->fecha_hora->format('d/m/Y') }}<br><small>{{ $reporte->fecha_hora->format('H:i') }}</small></td>
                    <td class="text-left">
                        <strong>{{ $reporte->nombre_reportante }}</strong>
                    </td>
                    <td class="text-left">{{ Str::limit($reporte->nombre_lugar ?? 'Sin especificar', 40) }}</td>
                    <td>
                        @if($reporte->tipos_incidente)
                            <span class="badge" style="background-color: {{ $reporte->tipos_incidente->color ?? '#6c757d' }};">
                                {{ $reporte->tipos_incidente->nombre }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($reporte->niveles_gravedad)
                            <span class="badge" style="background-color: {{ $reporte->niveles_gravedad->color ?? '#ffc107' }};">
                                {{ $reporte->niveles_gravedad->nombre }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($reporte->estados_sistema)
                            <span class="badge" style="background-color: {{ $reporte->estados_sistema->color ?? '#6c757d' }};">
                                {{ $reporte->estados_sistema->nombre }}
                            </span>
                        @else
                            <span class="badge badge-secondary">Sin estado</span>
                        @endif
                    </td>
                    <td class="resource-badges">
                        @php
                            $hasResources = false;
                        @endphp
                        @if($reporte->cant_bomberos > 0)
                            @php $hasResources = true; @endphp
                            <span class="badge badge-danger">{{ $reporte->cant_bomberos }} Bomb.</span>
                        @endif
                        @if($reporte->cant_paramedicos > 0)
                            @php $hasResources = true; @endphp
                            <span class="badge badge-info">{{ $reporte->cant_paramedicos }} Param.</span>
                        @endif
                        @if($reporte->cant_veterinarios > 0)
                            @php $hasResources = true; @endphp
                            <span class="badge badge-success">{{ $reporte->cant_veterinarios }} Vet.</span>
                        @endif
                        @if($reporte->cant_autoridades > 0)
                            @php $hasResources = true; @endphp
                            <span class="badge badge-warning">{{ $reporte->cant_autoridades }} Aut.</span>
                        @endif
                        @if(!$hasResources)
                            <span class="text-muted">Sin recursos</span>
                        @endif
                    </td>
                    <td>
                        {{ $reporte->telefono_contacto ?? '-' }}
                    </td>
                </tr>
                @if($reporte->comentario_adicional)
                <tr class="comment-row">
                    <td colspan="8">
                        <strong>Comentario:</strong> {{ $reporte->comentario_adicional }}
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Sistema de Gestión de Incendios - Alas Chiquitanas</strong></p>
        <p>Este documento contiene información confidencial</p>
    </div>
</body>
</html>
