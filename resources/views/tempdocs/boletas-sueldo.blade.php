<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Boletas de Pago</title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .boleta {
            width: 100%;
            border: 1px solid #000;
            padding: 12px;
            margin-bottom: 15px;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .linea {
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        .campo {
            display: flex;
            justify-content: space-between;
        }

        .tabla-totales {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .tabla-totales td {
            padding: 2px 0;
        }

        .tabla-totales td:last-child {
            text-align: right;
            width: 80px;
        }

        .firma {
            text-align: center;
            margin-top: 20px;
        }

        .firma .linea-firma {
            border-top: 1px solid #000;
            width: 200px;
            margin: 0 auto;
        }

        @page {
            size: letter;
            margin-left: 20mm;
            margin-right: 20mm;
            margin-top: 10mm;
            margin-bottom: 10mm;
        }

        .contenedor {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        @foreach ($boletas as $b)
            <div class="boleta">
                <div class="titulo">{{ $appName }}</div>
                <div class="titulo">Boleta de Pago NRO° {{ str_pad($b->id, 6, '0', STR_PAD_LEFT) }}</div>


                <div class="campo">
                    <span>Gestión: {{ $rrhhsueldo->gestion }}</span>
                    <span>Mes: {{ $rrhhsueldo->mes }}</span>
                </div>
                <div>Emitida: {{ \Carbon\Carbon::parse($rrhhsueldo->fecha)->format('d/m/Y') }}</div>

                <div class="linea"></div>
                <table>
                    <tr>
                        <td>Empleado</td>
                        <td>: {{ $b->nombreempleado }}</td>
                    </tr>
                    <tr>
                        <td>Contrato ID</td>
                        <td>: {{ str_pad($b->rrhhcontrato_id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>


                <div class="linea"></div>

                <table class="tabla-totales">
                    <tr>
                        <td>Salario Base Bs.:</td>
                        <td>{{ number_format($b->salario_mes, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Permisos:</td>
                        <td>{{ number_format($b->total_permisos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Adelantos:</td>
                        <td>{{ number_format($b->total_adelantos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bonos:</td>
                        <td>{{ number_format($b->total_bonos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Descuentos:</td>
                        <td>{{ number_format($b->total_descuentos, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Inasistencias:</td>
                        <td>{{ number_format($b->total_ctrlasistencias, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Líquido Pagable Bs.:</strong></td>
                        <td><strong>{{ number_format($b->liquido_pagable, 2) }} </strong></td>
                    </tr>
                </table>

                <div class="firma">
                    <div class="linea-firma"></div>
                    <span>Firma del Empleado</span>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
