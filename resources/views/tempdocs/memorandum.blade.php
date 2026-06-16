<!DOCTYPE html>
<html lang="es">
@php
    $data = decodGet($data);
    $myArray = explode('^', $data);

    $datos1 = explode('|', $myArray[0]);
    $citememo;
    $datos = [];
    if ($datos1[0] != 0) {
        $cite_id = $datos1[0];
        $citememo = traecitememo($cite_id);
        $datos = [$citememo['cite'], $citememo['empleado'], $citememo['fechaliteral'], $citememo['cuerpo']];
    } else {
        $datos = $datos1;
        $puntos = explode('|', $myArray[0]);
    }

@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informes</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">

    <style>
        body {
            background-size: initial;


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

</head>

<body>
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

        <h1 style="text-align: center; margin-top: 3rem;"><b><u>MEMORANDUM</u></b></h1>

        <div class="row" style="margin-right: 9rem; margin-left: 3rem;padding: 0;margin-top: 2rem">
            <div class="col-xs-6" style="border-right-style: groove;border-bottom-style: groove">
                <br>
                <div style="margin-left: 2rem;margin-bottom: 2rem;">

                    <br><br>
                    <br> <br>
                    Santa Cruz, {{ $datos[2] }} <br><br>


                </div>
            </div>
            <div class="col-xs-6" style="groove;border-bottom-style: groove">
                <br>
                <div style="margin-left: 2rem;margin-bottom: 2rem;">

                    CITE: {{ $datos[0] }} <br><br>
                    @php
                        $cadena = $datos[1];
                    @endphp

                    Al Señor(a): {{ $datos[1] }} <br> <br>

                    Presente.- <br>
                    @if (strlen($cadena) < 24)
                        <br>
                    @endif

                </div>
            </div>
        </div>

        <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
            Señor(a):
        </p>
        <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            {{ $datos[3] }}
        </p>
        <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
            Sin otro particular saludo a usted atentamente.
        </p>
        <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 9rem;text-align: center;">
             <br>
            <strong>
                GERENTE ADMINISTRATIVO
            </strong>
        </p>
        <br>
        <p style="margin-left: 3rem;margin-right: 3rem; text-align: justify;">
            <small>
                cc/ MIN. TRAB. <br>
                cc/arch. Pers. Min Trab. <br>
                cc/file personal
            </small>

        </p>
    </div>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
