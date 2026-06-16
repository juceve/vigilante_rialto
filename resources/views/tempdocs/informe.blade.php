<!DOCTYPE html>
<html lang="en">
@php
    $data = decodGet($data);
    $myArray = explode('^', $data);

    $datos1 = explode('|', $myArray[0]);
    $citeinforme;
    $datos = [];
    $encryptedId = '';

    if ($datos1[0] != 0) {
        $cite_id = $datos1[0];

        $encryptedId = Crypt::encrypt($cite_id);
        $link = url('/') . '/formulario-informe' . '/' . $encryptedId;
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($link) . '&size=130x130';

        $citeinforme = traeCiteInforme($cite_id);
        if ($citeinforme) {
            $datos = [
                $citeinforme['cite'],
                $citeinforme['objeto'],
                $citeinforme['fechaliteral'],
                $citeinforme['cliente'],
                $citeinforme['representante'],
                $citeinforme['referencia'],
            ];
            $puntos = explode('|', $citeinforme['puntos']);
        }
    } else {
        $datos = $datos1;
        $puntos = explode('|', $myArray[1]);
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informes</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">

    @if (count($datos))
        <style>
            body {
                background-size: 60%;

                /* background-image: url("{{ $datos1[0] ? asset('images/blackbird1.png') : asset('images/copia.jpg') }}"); */
                background-image: url("{{ $datos1[0] ? ($citeinforme['estado'] ? asset(config('adminlte.auth_logo.img.path')) : asset('images/anulado.png')) : asset('images/copia.jpg') }}");
                background-position: center center;
                background-repeat: no-repeat;
                height: 100%;
            }

            .contenido {
                min-height: 75%;
                background: rgba(255, 255, 255, 0.8);
                z-index: -1;
            }
        </style>
    @endif

</head>

<body>
    @if (count($datos))
        <div class="contenido">

            <div class="row" style="width: 100%;margin-right: 3rem">
                <div class="col-xs-5 text-center">
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
                    <img class="img-responsive" src="{{ asset(config('adminlte.auth_logo.img.path')) }}" style="width: 90px;">
                </div>
            </div>


            <div class="row" style="margin-top: 3rem">
                <div class="col-xs-6"></div>
                <div class="col-xs-6">
                    <strong>
                        CITE: {{ $datos1[0] ? $datos[0] : '0000/00' }} <br>
                        Objeto: {{ $datos[1] }} <br>
                        Santa Cruz, {{ $datos[2] }}
                    </strong>
                </div>
            </div>

            <p style="margin-left: 3rem">
                <strong>
                    {{ $datos[3] }} <br>
                    {{ $datos[4] }} <br>
                </strong>
                Presente. -
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
                <u>
                    <strong>REF.: {{ $datos[5] }}</strong>
                </u>
                <br><br>
                Mediante la presente me dirijo a su persona a modo de saludarla y poder ponerla en conocimiento los
                siguientes
                puntos:

            </p>

            @foreach ($puntos as $punto)
                <p style="margin-left: 6rem;margin-right: 3rem;text-align: justify; margin-bottom: 2rem">
                    &#8226; {{ $punto }}
                </p>
            @endforeach



            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
                Es todo lo que tengo a bien informar para los fines consiguientes.
            </p>



            @if ($encryptedId != '' && $datos1[1] > 0)
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: center;">
                    <img src="{{ $qrUrl }}" alt="QR Code"><br>
                @else
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
            @endif
             <br>
            <strong>
                GERENTE
            </strong>
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; text-align: justify;">
                LCM/ir.
            </p>
        </div>
    @endif

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
