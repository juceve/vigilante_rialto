<table>
    <tr>
        <td colspan="7" align="center"><strong>REGISTRO DE ASISTENCIAS</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="6"> {{$parametros[1]}} al {{$parametros[2]}}</td>
    </tr>
    <tr>
        <td><strong>CLIENTE:</strong></td>
        <td colspan="6"> {{$cliente->nombre}}</td>
    </tr>
    <tr>
        <td><strong>PERSONAL:</strong></td>
        <td colspan="6"> {{$empleado}}</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <th>NOMBRE</th>
        <th>FECHA</th>
        <th>TURNO</th>
        <th>INGRESO</th>
        <th>INGRESO REGISTRADO</th>
        <th>SALIDA</th>
        <th>SALIDA REGISTRADO</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->empleado}}</td>
        <td>{{$item->fecha}}</td>
        <td>{{$item->turno}}</td>
        <td>{{$item->turno_horainicio}}</td>
        <td>{{$item->ingreso}}</td>
        <td>{{$item->turno_horafin}}</td>
        <td>{{$item->salida}}</td>

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