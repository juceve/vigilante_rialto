<table>
    <thead>
        <tr">
            <th>CLIENTE</th>
            <th>REG. RONDAS</th>
            <th>REG. VISITAS</th>
            <th>REG. PASES QR</th>
            <th>REG. PANICOS</th>
        </tr>
    </thead>
    <tbody>
        {{-- @if ($resultados) --}}
        @forelse ($resultados as $item)
        <tr>
            <td>{{$item['cliente']}}</td>
            <td>{{$item['rondas']}}</td>
            <td>{{$item['visitas']}}</td>
            <td>{{$item['flujopases']}}</td>
            <td>{{$item['panicos']}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No existen datos para mostrar</td>
        </tr>
        @endforelse
        {{-- @endif --}}

    </tbody>
</table>