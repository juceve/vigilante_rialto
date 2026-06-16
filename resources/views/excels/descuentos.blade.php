<table>
    <tr>
        <td colspan="6" align="center"><strong>REGISTRO DE DESCUENTOS POR SANCIONES</strong></td>
    </tr>
    <tr>
        <td><strong>FECHA:</strong></td>
        <td colspan="5"> {{ $parametros[1] }} al {{ $parametros[2] }}</td>
    </tr>
    <tr>
        <td><strong>ESTADO:</strong></td>
        <td colspan="5">
            @switch($parametros[0])
                @case('')
                    Todos
                    @break
                @case('1')
                    Activos
                    @break
                @case('0')
                    Inactivos
                    @break

                @default

            @endswitch
        </td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <th>ID</th>
        <th>FECHA</th>
        <th>EMPLEADO</th>
        <th>TIPO</th>
        <th>MONTO</th>
        <th>ESTADO</th>
    </tr>

    @if (!is_null($resultados))
        @forelse ($resultados as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->fecha }}</td>

                <td>{{ $item->empleado?->nombres . ' ' . $item->empleado?->apellidos }}</td>
                <td>{{ $item->rrhhtipodescuento ? $item->rrhhtipodescuento->nombre : 'NULL' }} </td>
                <td>{{ number_format($item->cantidad * $item->monto, 2) }}</td>
                <td>
                    @if ($item->estado)
                        <span class="badge badge-pill badge-success">ACTIVO</span>
                    @else
                        <span class="badge badge-pill badge-secondary">INACTIVO</span>
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
