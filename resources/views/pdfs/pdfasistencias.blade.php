<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro_Asistencias_{{ date('His') }}</title>
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
                <h4><strong>REGISTRO DE ASISTENCIAS</strong></h4>
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
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>TURNO</th>
                <th>INGRESO</th>
                <th>INGRESO REGISTRADO</th>
                <th>SALIDA</th>
                <th>SALIDA REGISTRADO</th>
            </tr>
        </thead>
        <tbody>
            @if (!is_null($resultados))

            @forelse ($resultados as $item)
            <tr>
                <td>{{$item->empleado}}</td>
                <td>{{$item->fecha}}</td>
                <td>{{$item->turno}}</td>
                <td>{{$item->turno_horainicio}}</td>
                <td>{{$item->ingreso}}</td>
                <td>{{$item->turno_horafin}}</td>
                <td>
                    @if ($item->salida)
                    {{$item->salida}}
                    @else
                    Sin marcado
                    @endif
                </td>

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
