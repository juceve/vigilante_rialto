{{-- filepath: resources/views/pdf/sueldos-resumen.blade.php --}}
@php
    $appName = env('APP_NAME', 'Sistema');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resumen de Sueldos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .membrete {
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }

        .membrete-logo {
            font-size: 1.5em;
            color: #007bff;
            font-weight: bold;
            margin-right: 18px;
        }

        .titulo {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 5px 7px;
            text-align: center;
        }

        th {
            background: #e9ecef;
            color: #000000;
        }

        .firma {
            margin-top: 60px;
            text-align: right;
        }

        .firma .linea {
            border-top: 1px solid #333;
            width: 220px;
            margin-right: 0;
            margin-left: auto;
            margin-bottom: 5px;
        }

        .firma .nombre {
            font-size: 0.95em;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="membrete">
        <div class="membrete-logo">{{ $appName }}</div>
        <div>
            <div class="titulo">Resumen de Sueldos</div>
            <div>Gestión: {{ $rrhhsueldo->gestion }} &nbsp; | &nbsp; Mes: {{ $rrhhsueldo->mes }}</div>
            <div>Fecha de procesamiento: {{ $rrhhsueldo->fecha}}  {{$rrhhsueldo->hora}}</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th style="text-align: right">Basico</th>
                <th style="text-align: right">Inasistencias</th>
                <th style="text-align: right">No Marcó Salida</th>

                <th style="text-align: right">Descuentos</th>
                <th style="text-align: right">Adelantos</th>
                <th style="text-align: right">Bonos</th>
                <th>Líquido Pagable</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($sueldos as $i => $sueldo)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align:left">{{ $sueldo->nombreempleado }}</td>
                    <td style="text-align: right">{{ number_format($sueldo->salario_mes, 2) }}</td>
                    <td style="text-align: right">-{{ $sueldo->total_ctrlasistencias }}</td>
                    <td style="text-align: right">-{{ $sueldo->total_marcaciones_incompletas }}</td>
                    <td style="text-align: right">-{{ $sueldo->total_descuentos }}</td>
                    <td style="text-align: right">-{{ number_format($sueldo->total_adelantos, 2) }}</td>
                    <td style="text-align: right">+{{ number_format($sueldo->total_bonos, 2) }}</td>
                    <td style="text-align: right">{{ number_format($sueldo->liquido_pagable, 2) }}</td>
                </tr>
                @php
                    $total += $sueldo->liquido_pagable;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" style="text-align:right">Total:</th>
                <th style="font-weight:bold; text-align:right">{{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <div class="firma">
        <div class="linea"></div>
        <div class="nombre">Firma y sello encargado</div>
    </div>
</body>

</html>
