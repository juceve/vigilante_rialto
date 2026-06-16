<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma Mensual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f2f2f2;
            z-index: 1;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>Cronograma Mensual de Días Libres</h3>
        <p><strong>Mes:</strong> {{ \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="sticky-col">Empleado</th>
                @foreach($daysInMonth as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $emp)
                <tr>
                    <td class="sticky-col" style="text-align: left;">{{ $emp['name'] }}</td>
                    @foreach($daysInMonth as $day)
                        <td style="text-align: left;">
                            @if(in_array($day, $emp['days']))
                                <span title="Día Libre">{{ 'L' }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total de empleados: <strong>{{ count($empleados) }}</strong></p>
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>