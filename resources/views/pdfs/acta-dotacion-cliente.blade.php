<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF - Dotación a Clientes</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">

    <style>
        /* Forzar márgenes de página en dompdf para controlar exactamente la distancia */
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #23272f;
            background: #fff;
            margin: 0;
        }

        /* Aumentado padding en la primera página (.contenido) */
        .contenido {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            min-height: 75%;
            background: rgba(255, 255, 255, 0.8);
            z-index: -1;
            padding: 1cm;
            padding-bottom: 4cm;
            /* aumentado para dejar más espacio al pie */
            /* aumentado para dejar espacio al pie fijo */
            box-sizing: border-box;
        }

        /* Alinear el header/row de la primera página con los paneles (mismo left offset),
           y evitar que el contenido principal haga overflow en el eje X. */
        .contenido .row.header-align {
            width: 100%;
            margin-left: 22px;
            /* coincide con los paneles que usan margin-left: 22px */
            margin-right: 22px;
            /* evita que el contenido llegue hasta el borde derecho */
            box-sizing: border-box;
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

        /* .manual-page-border: borde visible colocado a 1cm del borde de la hoja */
        .manual-page-border {
            margin: 1cm;
            /* distancia del borde de la página hasta el borde visible */
            border: 1px solid #333;
            /* borde visible */
            box-sizing: border-box;
            width: auto;
            height: auto;
        }

        .manual-page {
            margin: 0;
            padding: 0.3cm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #222;
            word-wrap: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box;
        }

        /* Evitar que la .row de Bootstrap sobresalga por los márgenes negativos dentro de la manual-page
           y reducir el espacio (gutter) entre columnas. */
        .manual-page .row.no-gutter {
            margin-left: 0;
            margin-right: 0;
        }

        .manual-page .row.no-gutter>[class*="col-"] {
            padding-left: 4px;
            padding-right: 4px;
        }

        .manual-page-border {
            overflow: hidden;
        }

        .manual-title {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .manual-section {
            margin-bottom: 8px;
            text-align: justify;
        }

        .manual-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 35px;
            /* un poco más arriba que antes para separarlo del contenido */
            font-size: 8px;
            color: #666;
            text-align: center;
            background: transparent;
        }

        /* asegurar salto de página antes (compatible con dompdf) */
        .page-break {
            page-break-before: always;
        }
    </style>


</head>

<body>

    <div class="contenido">

        <div class="row header-align">
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
            <div class="alert alert-info" role="alert">ACTA DE ENTREGA DE DOTACIÓN A CLIENTES</div>
        </h4>

       

        <div class="panel panel-primary" style="font-size: 12px; margin-top: 10px; margin-left: 22px;">
            <div class="panel-heading text-center">
                <h3 class="panel-title">DETALLES</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped" style="width: 100%; font-size: 12px;">                   
                    <tbody>
                        <tr>
                            <td><strong>CLIENTE:</strong> {{ $dotacion->cliente->nombre }}</td>
                            <td><strong>FECHA:</strong> {{ formatearFecha($dotacion->fecha) }}</td>
                        </tr>
                        <tr>
                            <td><strong>RESP. ENTREGA:</strong> {{ $dotacion->responsable_entrega }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <h5 class="text-center text-info">ARTICULOS ENTREGADOS</h5>
                <table class="table table-condensed table-bordered table-striped" style="width: 100%; font-size: 9px;">
                    <thead>
                        <tr class="info">
                            <th class="text-center">ITEM</th>
                            <th class="text-center">DESCRIPCIÓN</th>
                            <th class="text-center">CANTIDAD</th>
                            <th class="text-center">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dotacion->cldotaciondetalles as $detalle)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $detalle->detalle }}</td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-center">{{ strtoupper($detalle->rrhhestadodotacion->nombre) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
<br><br><br>
<div class="row">
    <div class="col-xs-6 text-center">
        ___________________________________<br>
        <small>ENTREGADO POR</small><br>
    </div>
    <div class="col-xs-6 text-center">
        ___________________________________<br>
        <small>RECIBIDO POR</small><br>
    </div>
</div>
        

    </div>


    <div class="manual-footer">
        {{ strtoupper(config('app.name')) }} • Impreso el
        {{ now()->format('d/m/Y \a \l\a\s H:i') }}
    </div>
    </div>
    </div>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>