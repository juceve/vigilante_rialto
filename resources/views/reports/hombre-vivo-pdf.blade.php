{{-- filepath: c:\laragon\www\vigilantev2\resources\views\reports\hombre-vivo-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Hombre Vivo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #17a2b8;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            color: #17a2b8;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #17a2b8;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #ddd;
        }
        td {
            padding: 5px 3px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        td:first-child {
            text-align: left;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tfoot tr {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge-success { color: #28a745; font-weight: bold; }
        .badge-warning { color: #ffc107; font-weight: bold; }
        .badge-danger { color: #dc3545; font-weight: bold; }
        .badge-secondary { color: #6c757d; }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPORTE DE MARCACIONES HOMBRE VIVO</h2>
        <p><strong>Cliente:</strong> {{ $cliente }}</p>
        <p><strong>Período:</strong> {{ formatearFecha($fecha_inicio) }} - {{ formatearFecha($fecha_fin) }}</p>
        <p><strong>Generado:</strong> {{ $fecha_reporte }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Empleado</th>
                <th style="width: 8%;">Total<br>Marcaciones</th>
                <th style="width: 8%;">Total<br>Esperadas</th>
                <th style="width: 8%;">Cumplimiento</th>
                @foreach($dias as $dia)
                    <th style="width: 5%;">
                        {{ ucfirst($dia['diaNombre']) }}<br>
                        <strong>{{ $dia['dia'] }}</strong><br>
                        {{ ucfirst($dia['mes']) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($resultados as $resultado)
                <tr>
                    <td>{{ $resultado['empleado_nombre'] }}</td>
                    <td>{{ $resultado['total_marcaciones'] }}</td>
                    <td>
                        @php $totalEsp = array_sum(array_column($resultado['dias'], 'esperadas')); @endphp
                        {{ $totalEsp }}
                    </td>
                    <td>
                        @php
                            $cump = $totalEsp > 0 ? round(($resultado['total_marcaciones'] / $totalEsp) * 100, 1) : 0;
                            $class = $cump >= 90 ? 'success' : ($cump >= 70 ? 'warning' : ($cump > 0 ? 'danger' : 'secondary'));
                        @endphp
                        <span class="badge-{{ $class }}">{{ $cump }}%</span>
                    </td>
                    @foreach($resultado['dias'] as $dia)
                        <td>
                            @if($dia['esperadas'] == 0)
                                -
                            @else
                                @php
                                    $pct = $dia['cumplimiento'];
                                    $cls = $pct >= 90 ? 'success' : ($pct >= 70 ? 'warning' : ($pct > 0 ? 'danger' : 'secondary'));
                                    $cant = isset($dia['cant_registros']) ? $dia['cant_registros'] : (isset($dia['cantidad']) ? $dia['cantidad'] : 0);
                                @endphp
                                <span class="badge-{{ $cls }}">{{ $cant }}/{{ $dia['esperadas'] }}</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>TOTALES</td>
                <td>{{ array_sum(array_column($resultados, 'total_marcaciones')) }}</td>
                <td>
                    @php
                        $totEspGen = 0;
                        foreach($resultados as $r) { $totEspGen += array_sum(array_column($r['dias'], 'esperadas')); }
                    @endphp
                    {{ $totEspGen }}
                </td>
                <td>
                    @php
                        $totGen = array_sum(array_column($resultados, 'total_marcaciones'));
                        $cumpTot = $totEspGen > 0 ? round(($totGen / $totEspGen) * 100, 1) : 0;
                    @endphp
                    {{ $cumpTot }}%
                </td>
                <td colspan="{{ count($dias) }}"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Sistema de Vigilancia - Reporte generado automáticamente</p>
    </div>
</body>
</html>