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
                <img class="img-responsive" src="{{ asset(config('adminlte.auth_logo.img.path')) }}" style="width: 80px;">
                <p>
                    {{env('APP_NAME')}} <br>
                    <small>Seguridad y Vigilancia</small>
                </p>
            </td>
            <td style="width: 60%; vertical-align: bottom">
                <h4>REGISTRO DE RONDAS</h4>
                <small>Del {{ $designacione->fechaInicio }} al {{ $designacione->fechaFin }}</small>
            </td>
            <td style="width: 20%"></td>
        </tr>
    </table>

    {{-- <br> --}}
    <table class="table table-bordered table-condensed" style="width: 50%;font-size: 12px;">
        <tr>
            <td style="width: 30%"><b>Operador: </b></td>
            <td>{{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos }}</td>
        </tr>
        <tr>
            <td style="width: 30%"><b>Empresa: </b></td>
            <td>{{ $designacione->turno->cliente->nombre }}</td>
        </tr>
        <tr>
            <td style="width: 30%"><b>Turno: </b></td>
            <td>{{ $designacione->turno->nombre }}</td>
        </tr>
    </table>
    <br>
    <h5 class="text-center">DETALLES</h5>
    <table class="table table-bordered table-striped table-condensed" style="vertical-align: middle; font-size: 10px;">

        <thead class="">
            <tr class="active" align="center" style="vertical-align: middle">
                <td><strong>FECHAS</strong></td>
                @foreach ($designacione->turno->ctrlpuntos as $punto)
                    <td><strong>{{ $punto->nombre }} <br> {{ $punto->hora }}</strong></td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if (count($rondas) > 0)
                @foreach ($rondas as $ronda)
                    <tr align="center">
                        @foreach ($ronda as $item)
                                <td>{{ $item[0] }}</td>                           
                        @endforeach
                    </tr>
                @endforeach
            @endif

        </tbody>

    </table>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
