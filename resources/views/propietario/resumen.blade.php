{{-- filepath: resources/views/propietario/resumen.blade.php --}}
@extends('layouts.registros')

@section('content')
    <div class="container" id="resumen-registro" style="background-color: #f1f3f5">
        <div class="container py-5"  style="font-family: 'Nunito', sans-serif;">
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}" alt="" style="width: 70px;">
                </div>
                <div class="col-12 text-center">
                    <h3 class="mb-0 fw-bold text-secondary text-center" style="letter-spacing: 0.5px;">
                        {{ $propietario->cliente->nombre }}</h3>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4 mb-5">
                {{-- Header --}}
                <div class="card-header bg-gradient-to-end d-flex justify-content-between align-items-center rounded-top-4"
                    style="background: linear-gradient(90deg, #4b6cb7, #182848); color: white;">
                    <h5 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">Registro de Propietario</h5>
                    <small class="text-white-50">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</small>
                </div>

                <div class="card-body">
                    {{-- Datos del Propietario --}}
                    {{-- <h5 class="mb-3 text-primary fw-semibold" style="border-left: 4px solid #4b6cb7; padding-left: 8px;">Datos
                    del Propietario</h5> --}}
                    <ul class="list-group list-group-flush mb-4 shadow-sm rounded-3" style="font-size: 0.95rem;">
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Nombre:</strong>
                            <span>{{ $propietario->nombre }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Cédula:</strong>
                            <span>{{ $propietario->cedula }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Teléfono:</strong>
                            <span>{{ $propietario->telefono ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Email:</strong>
                            <span>{{ $propietario->email ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Dirección:</strong>
                            <span>{{ $propietario->direccion ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2"><strong>Ciudad:</strong>
                            <span>{{ $propietario->ciudad ?: '-' }}</span>
                        </li>
                    </ul>

                    {{-- Residencias
                <h5 class="mb-3 text-primary fw-semibold" style="border-left: 4px solid #4b6cb7; padding-left: 8px;">
                    Residencias</h5>
                <div class="table-responsive shadow-sm rounded-3 mb-4">
                    <table class="table table-bordered align-middle table-hover" style="font-size: 0.92rem;">
                        <thead class="table-success">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Número Puerta</th>
                                <th>Piso</th>
                                <th>Calle</th>
                                <th>Nro Lote</th>
                                <th>Manzano</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($residencias as $i => $res)
                                <tr class="text-center">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $res->numeropuerta ?: '-' }}</td>
                                    <td>{{ $res->piso ?: '-' }}</td>
                                    <td>{{ $res->calle ?: '-' }}</td>
                                    <td>{{ $res->nrolote ?: '-' }}</td>
                                    <td>{{ $res->manzano ?: '-' }}</td>
                                    <td>{{ $res->notas ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}


                </div>
            </div>
        </div>
    </div>
    {{-- Botones --}}
    <div class="container mt-4 d-grid">
        <button class="btn btn-primary px-4 py-2 fw-semibold" id="btnDescargarPDF"
            style="box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i> Descargar PDF
        </button>
        {{-- <button class="btn btn-secondary px-4 py-2 fw-semibold" id="btnDescargarImagen"
            style="box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="bi bi-image-fill me-2"></i> Descargar Imagen
        </button> --}}
    </div>
    {{-- Librerías --}}
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Descargar como PDF
            const btnPDF = document.getElementById('btnDescargarPDF');
            if (btnPDF) {
                btnPDF.addEventListener('click', function() {
                    const element = document.getElementById('resumen-registro');
                    const opt = {
                        margin: 0.5,
                        filename: 'resumen_propietario.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            logging: false,
                            scrollY: -window.scrollY
                        },
                        jsPDF: {
                            unit: 'in',
                            format: 'letter',
                            orientation: 'portrait'
                        }
                    };
                    html2pdf().set(opt).from(element).save();
                });
            }
        });
    </script>

    <style>
        /* Tipografía moderna */
        #resumen-registro {
            font-family: 'Nunito', 'Segoe UI', 'Helvetica Neue', sans-serif;
            font-size: 0.95rem;
            color: #212529;
        }

        /* Estilo cards y headers */
        .card-header h3 {
            font-size: 1.6rem;
        }

        /* Tablas con bordes suaves */
        table th,
        table td {
            vertical-align: middle;
            padding: 0.65rem;
        }

        /* Hover en filas */
        table tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Botones con sombra y transición */
        button.btn {
            transition: all 0.2s ease-in-out;
        }

        button.btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Encabezados secciones */
        h5.text-primary {
            font-size: 1.2rem;
            letter-spacing: 0.3px;
        }
    </style>
@endsection
