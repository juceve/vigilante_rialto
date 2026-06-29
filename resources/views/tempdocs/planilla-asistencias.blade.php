<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Planilla de Asistencias</title>
    <style>
        @page {
            size: Letter;
            margin-right: 10mm;
            margin-left: 15mm;
        }

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

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
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

        .con-permiso {
            color: rgb(0, 130, 153);
            font-weight: bold;
        }

        .con-atraso {
            color: rgb(175, 1, 175);
            font-weight: bold;
        }


        .footer {
            margin-top: 3px;
            text-align: right;
            font-size: 10px;
        }

        .row {
            /* width: 100%; */
        }

        .col {
            float: left;

            padding: 5px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="row" style="margin-right:25px; ">
        <div class="col header" style="width: 10%;">
            <img src="{{ public_path(config('adminlte.auth_logo.img.path')) }}" alt="Logo Empresa">
        </div>
        <div class="col" style="width: 40%; margin-top: 5px">
            <strong>{{ strtoupper(config('app.name')) }}</strong> <br>
            <small> Seguridad Privada y Vigilancia <br>

                SANTA CRUZ - BOLIVIA</small>
        </div>
        <div class="col" style="width: 50%; padding: 0">
            <h2 style="text-align: right">
                PLANILLA DE ASISTENCIAS <br>
                <small style="font-size: 10px;">Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</small>
            </h2>
        </div>
        <div class="clear"></div>
    </div>

    @foreach ($data as $empleado)
        <table>
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left;">
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
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleado['asistencias'] as $asis)
                    <tr>
                        <td>{{ $asis['fecha'] }}</td>
                        <td class="asistencia">
                            @if (isset($asis['permiso']) && $asis['permiso'])
                                <span class="con-permiso" title="Día Libre">PERMISO</span>
                            @elseif(isset($asis['libre']) && $asis['libre'])
                                <span class="libre" title="Día Libre">Libre</span>
                            @elseif(isset($asis['sin_designacion']) && $asis['sin_designacion'])
                                <span class="sin-designacion" title="Sin Designación">S/D</span>
                            @elseif($asis['ingreso'])
                                <span class="" title="Ingreso">{{ $asis['ingreso'] }}</span>
                            @elseif($asis['sin_marcacion'])
                                <span class="sin-marcacion" title="Sin Marcación">S/M</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="asistencia">
                            @if (isset($asis['permiso']) && $asis['permiso'])
                                <span class="con-permiso" title="Día Libre">PERMISO</span>
                            @elseif(isset($asis['libre']) && $asis['libre'])
                                <span class="libre" title="Día Libre">Libre</span>
                            @elseif(isset($asis['sin_designacion']) && $asis['sin_designacion'])
                                <span class="sin-designacion" title="Sin Designación">S/D</span>
                            @elseif($asis['salida'])
                                <span class="" title="Salida">{{ $asis['salida'] }}</span>
                            @elseif($asis['sin_marcacion'])
                                <span class="sin-marcacion" title="Sin Marcación">S/M</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{$asis['observaciones']}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
            <div class="row" style="margin-right:25px; ">
                <div class="col header" style="width: 10%;">
                    <img src="{{ public_path(config('adminlte.auth_logo.img.path')) }}" alt="Logo Empresa">
                </div>
                <div class="col" style="width: 40%; margin-top: 5px">
                    <strong>{{ strtoupper(config('app.name')) }}</strong> <br>
                    <small> Seguridad Privada y Vigilancia <br>

                        SANTA CRUZ - BOLIVIA</small>
                </div>
                <div class="col" style="width: 50%; padding: 0">
                    <h2 style="text-align: right">
                        PLANILLA DE ASISTENCIAS <br>
                        <small style="font-size: 10px;">Generado el
                            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</small>
                    </h2>
                </div>
                <div class="clear"></div>
            </div>
        @endif
    @endforeach

</body>

</html>
