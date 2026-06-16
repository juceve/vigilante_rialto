<table>
    <tr>
        <td colspan="6" align="center"><strong>REGISTROS AIRBNB</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="5"> {{$parametros[0]}} al {{$parametros[1]}}</td>
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
        <th>TITULAR</th>
        <th>NRO. DOC.</th>
        <th>INFO DEPARTAMENTO</th>
        <th>FECHA DE INGRESO</th>
        <th>FECHA DE SALIDA</th>
        <th>ESTADO</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->name}}</td>
        <td>{{$item->document_number}}</td>
        <td>{{$item->department_info}}</td>
        <td>{{$item->arrival_date}}</td>
        <td>{{$item->departure_date}}</td>
        <td>{{$item->status}}</td>        
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