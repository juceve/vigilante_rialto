<table>
    <tr>
        <td colspan="6" align="center"><strong>REGISTRO DE NOVEDADES</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="5"> {{$parametros[1]}} al {{$parametros[2]}}</td>
    </tr>
    <tr>
        <td><strong>CLIENTE:</strong></td>
        <td colspan="5"> {{$cliente->nombre}}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
    </tr>
    <tr>
        <th>CLIENTE</th>
        <th>GUARDIA</th>
        <th>TURNO</th>
        <th>FECHA</th>
        <th>HORA</th>
        <th>CONTENIDO</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->cliente}}</td>
        <td>{{$item->empleado}}</td>
        <td>{{$item->turno}}</td>
        <td>{{$item->fecha}}</td>
        <td>{{$item->hora}}</td>
        <td>{{$item->contenido}}</td>

    </tr>
    @empty
    <tr>
        <td colspan="7">No se econtraron resultados.</td>
    </tr>
    @endforelse
    @else
    <tr>
        <td colspan="7">No se econtraron resultados.</td>
    </tr>
    @endif
</table>