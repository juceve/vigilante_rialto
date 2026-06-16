<!DOCTYPE html>
<html lang="es">

<?php
$data = decodGet($data);
$myArray = explode('^', $data);

$datos1 = explode('|', $myArray[0]);
$citecobro=false;
$datos = [];
$mescobro;
$encryptedId = '';

if ($datos1[0] != 0) {
    $cite_id = $datos1[0];

    $encryptedId = Crypt::encrypt($cite_id);
    $link = url('/') . '/formulario-cobro' . '/' . $encryptedId;
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($link) . '&size=130x130';

    $citecobro = traeCitecobro($cite_id);
    if ($citecobro) {
        $datos = [$citecobro['cite'], $citecobro['fechaliteral'], $citecobro['cliente'], $citecobro['representante'], $citecobro['mescobro'], $citecobro['factura'], $citecobro['monto'], $citecobro['confactura']];
        $mescobro = explode('-', $datos[4]);
        $mescobro = $mescobro[1] . '-' . $mescobro[0] . '-01';
        $mescobro = ultDiaMes($mescobro);
    }
} else {
    $datos = explode('|', $myArray[1]);
    $mescobro = explode('-', $datos[4]);
    $mescobro = $mescobro[1] . '-' . $mescobro[0] . '-01';
    $mescobro = ultDiaMes($mescobro);
}
?>
{{-- @dump($myArray[1]) --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$citecobro?$citecobro['cite']:"Vista Previa"}}</title>
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
                    Atn.: {{ $datos[3] }} <br>
                </strong>
                Presente. -
            </p>

            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 3rem;text-align: justify;">

                De nuestra consideración: <br><br>
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                @if ($datos[7])
                    Adjunto a la presente remitimos a usted la Factura <b>Nro.:{{ $datos[5] }}</b> por Bs.
                    {{ $datos[6] }}
                    correspondiente a los servicios de
                    seguridad prestados del 01 al {{ $mescobro }}. <br><br>
                @else
                    Mediante la presente le solicitamos la cancelación de los Servicios de Seguridad del 01 al
                    {{ $mescobro }}
                    que corresponde a {{ $datos[6] }} Bs. <br>
                    Agradecemos sus gestiones. <br><br>
                @endif

                Atentamente,
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
