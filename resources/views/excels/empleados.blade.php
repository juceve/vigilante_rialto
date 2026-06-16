<table>
    <tr>
        <td colspan="10" align="center"><strong>REGISTRO DE EMPLEADOS</strong></td>
    </tr>
    <tr>
        <td></td>
        <td><strong>FECHA HORA EXPORTACIÓN:</strong></td>
        <td colspan="8"> {{date('Y-m-d H:i:s')}}</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <th>ID</th>
        <th>NOMBRES</th>
        <th>APELLIDOS</th>
        <th>TIPO DOC.</th>
        <th>NRO DOC.</th>
        <th>NACIONALIDAD</th>
        <th>DIRECCIÓN</th>
        <th>TELÉFONO</th>
        <th>EMAIL</th>
        <th>AREA</th>
    </tr>

    @if (!is_null($resultados))
    @forelse ($resultados as $item)
    <tr>
        <td>{{$item->id}}</td>
        <td>{{$item->nombres}}</td>
        <td>{{$item->apellidos}}</td>
        <td>{{$item->tipodocumento->name}}</td>
        <td>{{$item->cedula}}</td>
        <td>{{$item->nacionalidad}}</td>
        <td>{{$item->direccion}}</td>
        <td>{{$item->telefono}}</td>
        <td>{{$item->email}}</td>
        <td>{{$item->area->nombre}}</td>

    </tr>
    @empty
    <tr>
        <td colspan="10">No se econtraron resultados.</td>
    </tr>
    @endforelse
    @else
    <tr>
        <td colspan="10">No se econtraron resultados.</td>
    </tr>
    @endif
</table>