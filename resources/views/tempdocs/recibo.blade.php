<!DOCTYPE html>
<html lang="es">

<?php
$data = decodGet($data);
$myArray = explode('^', $data);

$datos1 = explode('|', $myArray[0]);
$citecobro;
$datos = [];
$mescobro;

$encryptedId = '';

$meses = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];

if ($datos1[0] != 0) {
    $cite_id = $datos1[0];

    $encryptedId = Crypt::encrypt($cite_id);
    $link = url('/') . '/formulario-recibo' . '/' . $encryptedId;
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($link) . '&size=130x130';

    $citecobro = traeCiteRecibo($cite_id);

    if ($citecobro) {
        $datos = [$citecobro['cite'], $citecobro['fechaliteral'], $citecobro['cliente'], $citecobro['mescobro'], $citecobro['monto']];
        $mescobro = explode('-', $datos[3]);
    }

    // $mescobro = $mescobro[1] . '-' . $mescobro[0] . '-01';
    // $mescobro = ultDiaMes($mescobro);
} else {
    $datos = explode('|', $myArray[1]);
    $mescobro = explode('-', $datos[3]);
    // $mescobro = $mescobro[1] . '-' . $mescobro[0] . '-01';
    // $mescobro = ultDiaMes($mescobro);
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBO</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">
    @if (count($datos))
        <style>
            body {
                background-size: 60%;
                background-image: url("{{ $datos1[0] ? ($citecobro['estado'] ? asset(config('adminlte.auth_logo.img.path')) : asset('images/anulado.png')) : asset('images/copia.jpg') }}");
                background-position: center center;
                background-repeat: no-repeat;
                height: 100%;
            }

            .contenido {
                min-height: 75%;
                background: rgba(255, 255, 255, 0.9);
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
            <div class="row" style="margin-top: 5rem">
                <div class="col-xs-6"></div>
                <div class="col-xs-6">
                    <strong>
                        CITE: {{ $datos1[0] ? $datos[0] : '0000/00' }} <br>
                        Santa Cruz, {{ $datos[1] }}
                    </strong>
                </div>
            </div>
            <br><br>
            <p style="margin-left: 3rem">
                Señores: <br>
                <strong>
                    {{ $datos[2] }} <br>
                    ADMINISTRACIÓN <br>
                </strong>
                Presente. -
            </p>
            <h3 style="margin-left: 3rem;margin-right: 3rem; margin-top: 3rem;text-align: center;">
                <u>
                    <strong>REF.: RECIBO</strong>
                </u>

            </h3>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 3rem;text-align: justify;">


                De nuestra consideración: <br><br>
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                Mediante la presente certifico el pago por los servicios prestados de la empresa de seguridad Black Bird
                correspondientes al mes de {{ $meses[$mescobro[0]] }}-{{ $mescobro[1] }} en instalaciones del
                mencionado
                establecimiento.
            </p>

            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 1rem;text-align: center;">
            <h4 class="text-center"> &#8226; {{ $datos[4] }} Bs. ({{ numLiteral($datos[4]) }})</h4>
            </p>

            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: left;">
                Sin otro particular agradecemos de antemano sus gestiones.
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
                @if ($encryptedId != '' && $datos1[1] > 0)
                    <img src="{{ $qrUrl }}" alt="QR Code"><br>
                @endif
                <br>
                <strong>
                    GERENTE ADMINISTRATIVO
                </strong>
            </p>
        </div>
    @endif

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
