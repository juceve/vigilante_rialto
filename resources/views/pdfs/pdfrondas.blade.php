<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro_Rondas_{{ date('His') }}</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">
</head>

<body>
    <table class="table" style="width: 100%">
        <tr class="text-center">
            <td style="width: 20%">
                {{-- <img class="img-responsive" src="{{ asset('images/blackbird1.png') }}" style="width: 80px;"> --}}
                <p>
                    {{ strtoupper(config('app.name')) }} <br>
                    Seguridad Privada y Vigilancia <br>
                </p>
            </td>
            <td style="width: 60%; vertical-align: bottom">
                <h4><strong>REGISTRO DE RONDAS</strong></h4>
                <small>Del {{ $parametros[1] }} al {{ $parametros[2] }}</small>
                <br>
                <small><strong>CLIENTE: {{ $cliente->nombre }}</strong></small>
            </td>
            <td style="width: 20%"></td>
        </tr>
    </table>
    {{-- @dump($resultados) --}}

    <br>
    <h5 class="text-center">DETALLES</h5>
    <table class="table table-bordered table-striped" style="vertical-align: middle;font-size: 10px;">
        <thead>
            <tr class="table-info">
                <th class="text-center">NRO</th>
                <th>CLIENTE</th>
                <th>GUARDIA</th>
                <th class="text-center" style="width: 80px;">FECHA</th>
                <th class="text-center" style="width: 80px;">HORA</th>
                <th>TURNO</th>
                <th>PUNTO CONTROL</th>
            </tr>
        </thead>
        <tbody>
            @if (!is_null($resultados))

            @forelse ($resultados as $item)
            <tr>
                <td class="text-center">{{$i++}}</td>
                <td>{{$item->cliente}}</td>
                <td>{{$item->empleado}}</td>
                <td class="text-center">{{$item->fecha}}</td>
                <td class="text-center">{{$item->hora}}</td>
                <td>{{$item->turno}}</td>
                <td>{{$item->ctrlpunto->nombre}}</td>

            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="7">No se econtraron resultados.</td>
            </tr>
            @endforelse
            @else
            <tr>
                <td class="text-center" colspan="7">No se econtraron resultados.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
