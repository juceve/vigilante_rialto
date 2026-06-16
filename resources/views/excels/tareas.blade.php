<table>
    <tr>
        <td colspan="6" align="center"><strong>REGISTRO DE TAREAS</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="5"> {{$parametros[2]}} al {{$parametros[3]}}</td>
    </tr>
    <tr>
        <td><strong>CLIENTE:</strong></td>
        <td colspan="5"> {{$cliente->nombre}}</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <th>ID</th>
        <th>CLIENTE</th>
        <th>GUARDIA</th>
        <th>FECHA</th>
        <th>CONTENIDO</th>
        <th>ESTADO</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->nombrecliente}}</td>
        <td>{{$item->nombreempleado}}</td>
        <td>{{$item->fecha}}</td>
        <td>{{$item->contenido}}</td>
        <td>
            @if ($item->estado)
            En proceso
            @else
            Finalizado
            @endif
        </td>
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