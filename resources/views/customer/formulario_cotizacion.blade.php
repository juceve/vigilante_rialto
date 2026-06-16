<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <!-- Vinculación de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Vinculación de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Fondo y estilo general */
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .form-container {

            margin: 30px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #5a5a5a;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Estilo personalizado para Select2 */
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            /* Ajusta la altura para que coincida con los campos Bootstrap */
            border-radius: 0.375rem;
            /* Coincide con los bordes redondeados de los formularios Bootstrap */
            border: 1px solid #ced4da;
            /* Borde similar al de los inputs de Bootstrap */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem + 2px);
            /* Ajusta el texto dentro del select */
        }

        .capitalize {
            text-transform: capitalize;
        }
    </style>
    @livewireStyles
</head>

<body>
    <div class="container">
        <div class="alert alert-success text-center mt-4" role="alert">
            <h3>COTIZACIÓN NRO.:{{ cerosIzq($citecotizacion->id) }} CORRECTAMENTE VALIDADO!</h3>
        </div>
        <div class="form-container">
            <h4 class="text-secondary text-center"><strong>COTIZACIÓN EMITIDA </strong></h4>
            <h5 class="text-secondary text-center">{{ $citecotizacion->destinatario }}</h5>
            <div class="table-responsive mt-4">
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr>
                            <td><strong>CITE:</strong></td>
                            <td>{{ $citecotizacion->cite }}</td>
                        </tr>
                        <tr>
                            <td><strong>REF.:</strong></td>
                            <td>SOLICITUD DE COTIZACIÓN</td>
                        </tr>
                        <tr>
                            <td><strong>FECHA:</strong></td>
                            <td>{{ $citecotizacion->fecha }}</td>
                        </tr>
                        <tr>
                            <td><strong>DIRIGIDO A:</strong></td>
                            <td>{{ $citecotizacion->destinatario }}</td>
                        </tr>
                        <tr>
                            <td><strong>DETALLES:</strong></td>
                            <td>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Detalle</th>
                                            <th>Cant.</th>
                                            <th>Precio</th>
                                            <th>SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($citecotizacion->detalles as $detalle)
                                            <tr>
                                                <td>{{ $detalle->detalle }}</td>
                                                <td>{{ $detalle->cantidad }}</td>
                                                <td>{{ $detalle->precio }}</td>
                                                <td>{{ $detalle->precio * $detalle->cantidad }}</td>
                                            </tr>
                                            @php
                                                $total += $detalle->precio * $detalle->cantidad;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">TOTAL</th>
                                            <th>{{ $total }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @livewireScripts
    <!-- Vinculación de Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        Livewire.on('success', msg => {
            console.log(msg);

        });

        Livewire.on('error', msg => {
            console.log(msg);

        });
    </script>
</body>

</html>
