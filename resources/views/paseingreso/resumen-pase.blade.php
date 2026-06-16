<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            /* align-items: center; */
            height: 100vh;
            margin: 0;
        }

        .credencial {
            width: 320px;
            height: 570px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            background: #0056b3;
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .datos {
            text-align: left;
            font-size: 16px;
            line-height: 1;
            padding: 0 10px;
        }

        .qr {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .qr img {
            width: 60%;
            height: auto;
        }

        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mt-5">
        <div id="credencial" class="credencial">
            <div class="header">
                <small>PASE DE ACCESO N°
                    {{ $paseingreso->residencia->cliente->id . '-' . str_pad($paseingreso->id, 4, '0', STR_PAD_LEFT) }}</small> <br>
                {{ $paseingreso->motivo->nombre }}
            </div>
            <table class="table table-striped table-sm text-left" style="font-size: 12px">
                <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>{{ $paseingreso->residencia->cliente->nombre }}</td>
                </tr>
                <tr>
                    <td style="width:70px;"><strong>Nombre:</strong></td>
                    <td>{{ $paseingreso->nombre }}</td>
                </tr>
                <tr>
                    <td><strong>Cedula:</strong></td>
                    <td>{{ $paseingreso->cedula }}</td>
                </tr>
                <tr>
                    <td><strong>Inicio:</strong></td>
                    <td>{{ date('d/m/Y', strtotime($paseingreso->fecha_inicio)) }}</td>
                </tr>
                <tr>
                    <td><strong>Expira:</strong></td>
                    <td>{{ date('d/m/Y', strtotime($paseingreso->fecha_fin)) }}</td>
                </tr>

            </table>
            <div class="qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ encrypt($paseingreso->id) }}&size=400x400"
                    alt="QR Code">
            </div>
            <div class="footer">Validez sujeta a control de acceso</div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary btn-block" onclick="descargar()">Descargar Resumen <i
                    class="fas fa-download"></i></button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>


    <script>
        function descargar() {
            const credencial = document.getElementById("credencial");
            html2canvas(credencial, {
                useCORS: true,
                scale: 2
            }).then(canvas => {
                const link = document.createElement("a");
                link.download = "credencial.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }
    </script>

</body>

</html>
