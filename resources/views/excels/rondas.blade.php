<table>
    <tr>
        <td colspan="10" align="center"><strong>REGISTRO DE RONDAS</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="9"> {{$parametros[1]}} al {{$parametros[2]}}</td>
    </tr>
    <tr>
        <td><strong>CLIENTE:</strong></td>
        <td colspan="9"> {{$cliente->nombre}}</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <th>CLIENTE</th>
        <th>GUARDIA</th>
        <th>TURNO</th>
        <th>PUNTO CONTROL</th>
        <th>FECHA</th>
        <th>HORA</th>
        <th>ANOTACIONES</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->cliente}}</td>
        <td>{{$item->empleado}}</td>
        <td>{{$item->turno}}</td>
        <td>{{$item->ctrlpunto->nombre}}</td>
        <td>{{$item->fecha}}</td>
        <td>{{$item->hora}}</td>
        <td>{{$item->anotaciones}}</td>

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