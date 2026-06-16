<table>
    <tr>
        <td colspan="10" align="center"><strong>REGISTRO DE VISITAS</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="9"> {{$parametros[2]}} al {{$parametros[3]}}</td>
    </tr>
    <tr>
        <td><strong>CLIENTE:</strong></td>
        <td colspan="9"> {{$cliente->nombre}}</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <th>VISITANTE</th>
        <th>DOC. IDENTIDAD</th>
        <th>RESIDENTE</th>
        <th>VIVIENDA</th>
        <th>INGRESO</th>
        <th>SALIDA</th>
        <th>MOTIVO</th>
        <th>OTROS</th>
        <th>OBSERVACIONES</th>
        <th>ESTADO</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->visitante}}</td>
        <td>{{$item->docidentidad}}</td>
        <td>{{$item->residente}}</td>
        <td>{{$item->nrovivienda}}</td>
        <td>{{$item->fechaingreso.' '.$item->horaingreso}}</td>
        <td>
            @if (!$item->estado)
            {{$item->fechasalida.' '.$item->horasalida}}
            @else
            --
            @endif
        </td>
        <td>{{$item->motivo}}</td>
        <td>{{$item->otros}}</td>
        <td>{{$item->observaciones}}</td>
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