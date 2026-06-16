<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla de Asistencias</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 60px;
        }
        .empresa-info {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        td.asistencia {
            font-size: 11px;
        }
        .ingreso {
            color: green;
            font-weight: bold;
        }
        .salida {
            color: blue;
            font-weight: bold;
        }
        .sin-marcacion {
            color: red;
            font-weight: bold;
        }
        .libre {
            color: orange;
            font-weight: bold;
        }
        .sin-designacion {
            color: gray;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path(config('adminlte.auth_logo.img.path')) }}" alt="Logo Empresa">
        <div class="empresa-info">
            PLANILLA DE ASISTENCIAS
        </div>
    </div>

    @foreach($data as $empleado)
        <table>
            <thead>
                <tr>
                    <th colspan="3" style="text-align:left;">
                        Empleado: {{ $empleado['empleado'] }} <br>
                        Turno: {{ $empleado['turno'] }} <br>
                        Horario: {{ $empleado['horario'] }} <br>
                        Empresa: {{ $empleado['empresa'] }}
                    </th>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <th>Ingreso</th>
                    <th>Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleado['asistencias'] as $asis)
                    <tr>
                        <td>{{ $asis['fecha'] }}</td>
                        <td class="asistencia">
                            @if(isset($asis['libre']) && $asis['libre'])
                                <span class="libre" title="Día Libre">Libre</span>
                            @elseif(isset($asis['sin_designacion']) && $asis['sin_designacion'])
                                <span class="sin-designacion" title="Sin Designación">S/D</span>
                            @elseif($asis['ingreso'])
                                <span class="ingreso" title="Ingreso">{{ $asis['ingreso'] }}</span>
                            @elseif($asis['sin_marcacion'])
                                <span class="sin-marcacion" title="Sin Marcación">S/M</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="asistencia">
                            @if(isset($asis['libre']) && $asis['libre'])
                                <span class="libre" title="Día Libre">Libre</span>
                            @elseif(isset($asis['sin_designacion']) && $asis['sin_designacion'])
                                <span class="sin-designacion" title="Sin Designación">S/D</span>
                            @elseif($asis['salida'])
                                <span class="salida" title="Salida">{{ $asis['salida'] }}</span>
                            @elseif($asis['sin_marcacion'])
                                <span class="sin-marcacion" title="Sin Marcación">S/M</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="page-break-after: always;"></div>
    @endforeach

    <div class="footer">
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
