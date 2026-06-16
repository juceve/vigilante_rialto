<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro_Novedades_{{ date('His') }}</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">


    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #23272f;
            background: #fff;
            margin: 0;
        }

        .contenido {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            min-height: 75%;
            background: rgba(255, 255, 255, 0.8);
            z-index: -1;
        }

        .document-footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #555;
            text-align: center;
            border-top: 1px solid #aaa;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="contenido">

        <div class="row" style="width: 100%;margin-right: 3rem; margin-left: 10px;">
            <div class="col-xs-5">
                <br>
                <small>
                    <strong>
                        {{ strtoupper(config('app.name')) }} <br>
                        Seguridad Privada y Vigilancia <br>

                        SANTA CRUZ - BOLIVIA
                    </strong>
                </small>
            </div>

            <div class="col-xs-3 text-right">

            </div>
            <div class="col-xs-4 text-center">
                <img class="img-responsive" src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                    style="width: 60px; margin-top: 1rem">
            </div>
        </div>

        <h4 class="text-center text-primary " style="margin-left: 22px;">
            <div class="alert alert-info" role="alert">
                REPORTE DE NOVEDADES - {{ $cliente->nombre }} <br>
                <small style="font-size: 10px">
                    <strong>
                        Del {{ \Carbon\Carbon::parse($parametros[1])->format('d/m/Y') }} al
                        {{ \Carbon\Carbon::parse($parametros[2])->format('d/m/Y') }}
                    </strong>
                </small>
            </div>

        </h4>
        <table class="table table-bordered table-striped"
            style="width: 97% ;font-size: 10px; margin-top: 10px; margin-left: 22px; margin-right: 40px; ">
            <thead>
                <tr class="success">
                    <th class="text-center">NRO</th>
                    <th>CLIENTE</th>
                    <th>GUARDIA</th>
                    <th class="text-center" style="width: 80px;">FECHA</th>
                    <th class="text-center" style="width: 80px;">HORA</th>
                    <th>TURNO</th>
                    <th>CONTENIDO</th>
                </tr>
            </thead>
            <tbody>
                @if (!is_null($resultados))

                    @forelse ($resultados as $item)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ $item->cliente_from_content ?? $item->cliente ?? 'Supervisor' }}</td>
                            <td>{{ $item->empleado }}</td>
                            <td class="text-center">{{ $item->fecha }}</td>
                            <td class="text-center">{{ $item->hora }}</td>
                            <td>{{ $item->turno ?? 'Turno de Supervisor' }}</td>
                            <td>{{ $item->contenido }}</td>

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
    </div>
    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
