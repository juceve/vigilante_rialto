<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de aviso de registro expirado para Airbnb">
    <title>Registro exitoso - Airbnb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background: linear-gradient(to bottom, #74ebd5, #9face6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            font-family: 'Arial', sans-serif;
            color: #343a40;
            padding-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="container" style="max-width: 650px">
        <div class="card mb-3">
            <div class="card-body" id="content">
                <div id='contenido'>
                    <h5 class=" text-center"><strong>Registro de Inquilino Temporal Airbnb</strong></h5>
                    <p class="text-center text-success"><strong>Datos Recepcionados</strong></p>
                    <div class="table-responsive d-flex justify-content-center">
                        <table class="table table-bordered table-sm" >
                            <tr>
                                <td> <label><strong>Datos del Departamento</strong></label></td>
                                <td>{{ $traveler->department_info }}</td>
                            </tr>
                            <tr>
                                <td> <label><strong>Fecha-Hora Ingreso</strong></label></td>
                                <td>{{ $traveler->arrival_date }}</td>
                            </tr>
                            <tr>
                                <td><label><strong>Fecha-Hora Salida</strong></label></td>
                                <td>{{ $traveler->departure_date }}</td>
                            </tr>
                        </table>
                    </div>



                    <hr>
                    <div class="table-responsive d-flex justify-content-center">
                        <table class="table table-sm table-bordered" style="font-size: 12px;max-width: 600px;">
                            <thead class="table-success">
                                <tr class="text-center">
                                    <th colspan="5">INQUILINO TITULAR</th>
                                </tr>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>NRO. DOC.</th>
                                    <th>PROCEDENCIA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $traveler->name }}</td>
                                    <td>{{ $traveler->document_number }}</td>
                                    <td>{{ $traveler->city_of_origin }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if ($companions)
                        <div class="table-responsive d-flex justify-content-center mt-3">
                            <table class="table table-sm table-bordered table-striped"
                                style="font-size: 12px;max-width: 600px;">
                                <thead class="table-info">
                                    <tr class="text-center">
                                        <th colspan="5">INQUILINO COMPAÑANTE</th>
                                    </tr>
                                    <tr>
                                        <th>NOMBRE</th>
                                        <th>NRO. DOC.</th>
                                        <th>NACIONALIDAD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companions as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->document_number }}</td>
                                            <td>{{ $item->nationality }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-center align-items-center mx-auto" style="max-width: 350px"
                        id="qr">
                        <div class="text-center ">
                            <p class="text-center">
                                <strong>CONTROL DE INGRESO</strong> <br> {{ $link->cliente->nombre }}
                            </p>
                            <div class="content d-flex justify-content-center mb-2">
                                <img src="{{ $qrUrl }}" alt="QR Code">
                            </div>
                            <p style="font-size: 14px;">
                                <strong>Cod. Registro: {{ str_pad($traveler->id,5, '0', STR_PAD_LEFT) }}</strong><br>
                                {{-- Válido: <br> --}}
                                <label><strong>Ingreso: </strong></label> {{ $traveler->arrival_date }}    <br> <label><strong>Salida: </strong></label> {{ $traveler->departure_date }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    {{-- <div class="col-12 col-md-2"></div> --}}
                    <div class="col-12 col-md-6 d-grid mb-2">
                        <button id="download-btn2" class="btn btn-primary">
                            Descargar Qr <i class="fas fa-qrcode"></i>
                        </button>
                    </div>
                    <div class="col-12 col-md-6 d-grid mb-2">
                        <button class="btn btn-success" id="download-btn">
                            Descargar Formulario <i class="fas fa-file-alt"></i>
                        </button>
                    </div>
                    <div class="col-12 d-grid">
                        <a href="{{ route('downloadpdf', $traveler->id) }}" class="btn btn-secondary">
                            Descargar Formulario Completo <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.getElementById('download-btn').addEventListener('click', async function() {
            html2canvas(document.getElementById('content'), {
                useCORS: true // Intenta cargar imágenes desde dominios con CORS habilitado
            }).then(function(canvas) {
                var img = canvas.toDataURL("image/png");
                var link = document.createElement('a');
                link.href = img;
                link.download = 'Formulario de Registro Nro ' +
                    {{ $traveler->id }} + '.png';
                link.click();
            }).catch(function(error) {
                console.error("Error al capturar la pantalla:", error);
            });
        });
    </script>

    <script>
        document.getElementById('download-btn2').addEventListener('click', async function() {
            html2canvas(document.getElementById('qr'), {
                useCORS: true // Intenta cargar imágenes desde dominios con CORS habilitado
            }).then(function(canvas) {
                var img = canvas.toDataURL("image/png");
                var link = document.createElement('a');
                link.href = img;
                link.download = 'QR de Control Nro ' + {{ $traveler->id }} + '.png';
                link.click();
            }).catch(function(error) {
                console.error("Error al capturar la pantalla:", error);
            });
        });
    </script>
</body>

</html>
