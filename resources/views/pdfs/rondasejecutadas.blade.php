<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Rondas</title>
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
                REPORTE DE RONDAS <br>
                <small style="font-size: 10px">
                    <strong>
                        Del {{ \Carbon\Carbon::parse($fechas[0])->format('d/m/Y') }} al
                        {{ \Carbon\Carbon::parse($fechas[1])->format('d/m/Y') }}
                    </strong>
                </small>
            </div>

        </h4>


        <table class="table table-bordered table-striped"
            style="width: 97% ;font-size: 10px; margin-top: 10px; margin-left: 22px; margin-right: 40px; ">
            <thead>
                <tr class="success">
                    <th style="vertical-align: middle;">ESTABLECIMIENTO</th>
                    <th style="vertical-align: middle;">GUARDIA</th>
                    <th style="vertical-align: middle;">RONDA</th>
                    <th style="vertical-align: middle;" class="text-center">INICIO</th>
                    <th style="vertical-align: middle;" class="text-center">FINAL</th>
                    <th style="vertical-align: middle;" class="text-center">PUNTOS <br> VALIDADOS</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td style="vertical-align: middle;">{{ $item->cliente->nombre }}</td>
                        <td style="vertical-align: middle;">{{ $item->user->name }}</td>
                        <td style="vertical-align: middle;">{{ $item->ronda?$item->ronda->nombre:'NA' }}</td>
                        <td style="vertical-align: middle;" class="text-center">
                            {{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y') }} <br>
                            {{ \Carbon\Carbon::parse($item->inicio)->format('H:i:s') }}
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            @if ($item->fin)
                                {{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y') }} <br>
                                {{ \Carbon\Carbon::parse($item->fin)->format('H:i:s') }}
                            @elseif ($item->status === 'EN_PROGRESO')
                                <span class="label label-success" style="font-size: 10px">En progreso</span>
                            @else
                                <span class="label label-default" style="font-size: 10px">Sin registro</span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;" class="text-center">
                            {{ $item->rondaejecutadaubicaciones->count() }} de
                            {{ $item->ronda?$item->ronda->rondapuntos->count():'NA' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class="document-footer">
        Documento emitido el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
    </div>
    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
