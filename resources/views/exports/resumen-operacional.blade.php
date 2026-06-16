<table>
    <tr>
        <td>
            RESUMEN OPERACIONAL
        </td>
    </tr>
    <tr>
        <td>
            Cliente:&nbsp; {{ $cliente->nombre ?? 'Todos' }}
        </td>
    </tr>
    <tr>
        <td>
            Fecha Inicio:&nbsp; {{ formatearFecha($parametros[1]) }}
        </td>
    </tr>
    <tr>
        <td>
            Fecha Fin:&nbsp; {{ formatearFecha($parametros[2]) }}
        </td>
    </tr>
    <tr><td></td></tr>
    <thead>
        <tr>
            <td></td>
            <td colspan="7">
                CANTIDAD DE REGISTROS
            </td>
        </tr>
        <tr>
            <td>CLIENTE</td>
            <td>RONDAS</td>
            <td>VISITAS</td>
            <td>PASES QR</td>
            <td>PANICOS</td>
            <td>TAREAS</td>
            <td>NOVEDADES</td>
            <td>HOMBRE VIVO</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultados as $item)
        <tr>
            <td>{{$item['cliente']}}</td>
            <td>{{$item['rondas']}}</td>
            <td>{{$item['visitas']}}</td>
            <td>{{$item['flujopases']}}</td>
            <td>{{$item['panicos']}}</td>
            <td>{{$item['tareas']}}</td>
            <td>{{$item['novedades']}}</td>
            <td>{{$item['hombrevivos']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>