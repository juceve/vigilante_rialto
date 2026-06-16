@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

<div>
    @section('title')
        Control de Pases
    @endsection

    <!-- Header Corporativo -->
    <div class="patrol-header">
        <div class="container">
            <div class="header-navigation">
                <a href="{{ route('home') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <h1 class="title-text">CONTROL DE PASES</h1>
                    <p class="subtitle-text">Verificación de Permisos de Propietarios</p>
                </div>
                <div class="header-status">
                    <div class="status-indicator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="patrol-content px-3">
        {{-- <h2 class="text-center">Escanear Código QR</h2> --}}
        <div class="row justify-content-center" wire:ignore>
            <div id="reader" style="width: 100%; display: none;"></div>
            <div id="status" class="d-none" style="margin-top: 10px; color: green;"></div>
            <div class="input-group mb-3 mt-3" style="display: none;" id="search">
                <input type="number" class="form-control" placeholder="Cod. Registro" aria-label="Cod. Registro"
                    aria-describedby="button-addon2" id="inputSearch" wire:model='search'>
                <button class="btn btn-outline-primary" type="button" id="button-addon2"
                    wire:click='buscarCod'>Buscar</button>
            </div>

            <div id="controls" class="d-grid" style="margin-top: 15px;">
                <button id="startScanner" class="btn btn-success" style="height: 70px">Verificar Codigo Qr <i
                        class="fas fa-camera"></i></button>
                <button id="startKeyboard" class="btn btn-info mt-3" style="height: 70px"
                    wire:click="$set('search', '')">Verificar Cod. Registro <i class="fas fa-keyboard"></i></button>
                <button id="reloadPage" class="btn btn-success" style="display: none;height: 50px">Verificar otro Codigo
                    Qr
                    <i class="fas fa-camera"></i></button>
                <button id="cancelScanner" class="btn btn-danger" style="display: none;">Cancelar <i
                        class="fas fa-ban"></i></button>
                <button id="cancelScanner2" class="btn btn-danger" style="display: none;" wire:click="resetAll">Cancelar
                    <i class="fas fa-ban"></i></button>
            </div>

            <!-- Mostrar información del registro -->

        </div>

        <div class="d-grid mt-3" wire:ignore>
            <a href="{{ route('vigilancia.flujopases',$designacione->id) }}" id="ctrlbutton" class="btn btn-warning"
                style="height: 70px; align-content:center;">Control de Flujos <i class="fas fa-exchange-alt"></i></a>
        </div>

    </div> <!-- Cierre patrol-content -->
</div>
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"
        integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            };

            // Elementos HTML
            const readerDiv = document.getElementById("reader");
            const statusDiv = document.getElementById("status");
            const search = document.getElementById("search");
            const startButton = document.getElementById("startScanner");
            const keyButton = document.getElementById("startKeyboard");
            const reloadButton = document.getElementById("reloadPage");
            const cancelButton = document.getElementById("cancelScanner");
            const cancelButton2 = document.getElementById("cancelScanner2");
            const ctrlButton = document.getElementById("ctrlbutton");

            // Variable de control para el estado del escaneo
            let isScanning = false;

            // Función para iniciar el lector
            startButton.addEventListener("click", () => {
                isScanning = true; // Permitir escaneo
                startButton.style.display = "none"; // Ocultar botón de inicio
                ctrlButton.style.display = "none"; // Ocultar botón de inicio
                keyButton.style.display = "none"; // Ocultar botón de inicio
                cancelButton.style.display = "inline-block"; // Mostrar botón de cancelar
                readerDiv.style.display = "block"; // Mostrar lector

                html5QrCode.start({
                        facingMode: "environment"
                    }, // Cámara trasera
                    config,
                    qrCodeMessage => {
                        if (isScanning) {
                            isScanning = false; // Detener escaneo
                            statusDiv.innerText = `Código QR leído: ${qrCodeMessage}`;

                            // Llamar a Livewire
                            @this.buscarRegistro(qrCodeMessage)
                                .then(() => {
                                    detenerScanner(); // Detener el lector
                                    mostrarBotonRecarga(); // Mostrar botón de recarga
                                })
                                .catch(err => {
                                    console.error("Error en Livewire:", err);
                                });
                        }
                    },
                    errorMessage => {
                        console.warn("Error al escanear:", errorMessage);
                    }
                ).catch(err => {
                    console.error("No se pudo iniciar el escáner:", err);
                    detenerScanner(); // Restaurar estado en caso de error
                });
            });

            keyButton.addEventListener("click", () => {
                startButton.style.display = "none"; // Ocultar botón de inicio
                keyButton.style.display = "none"; // Ocultar botón de keyboard
                ctrlButton.style.display = "none"; // Ocultar botón de control
                cancelButton2.style.display = "inline-block"; // Mostrar botón de cancelar
                search.style.display = ""; // Mostrar div search
                document.getElementById('inputSearch').focus();
            });

            // Función para detener el lector
            function detenerScanner() {
                html5QrCode.stop().then(() => {
                    console.log("Escáner detenido correctamente.");
                    readerDiv.style.display = "none"; // Ocultar lector
                    cancelButton.style.display = "none"; // Ocultar botón de cancelar
                }).catch(err => {
                    // console.error("Error al detener el escáner:", err);
                });
            }

            // Mostrar botón de recarga
            function mostrarBotonRecarga() {
                reloadButton.style.display = "inline-block"; // Mostrar botón de recarga
                startButton.style.display = "none"; // Ocultar botón de iniciar cámara
            }

            // Evento del botón "Cancelar"
            cancelButton.addEventListener("click", () => {
                location.reload(); // Recargar la página

            });
            cancelButton2.addEventListener("click", () => {
                location.reload(); // Recargar la página


            });

            // Evento del botón "Recargar Página"
            reloadButton.addEventListener("click", () => {
                location.reload(); // Recargar la página
            });
        });
    </script>

@endsection

@section('css')
    <style>
        /* Variables CSS - Paleta Empresarial de Seguridad */
        :root {
            --primary-color: #1e3a8a;
            /* Azul naval profundo */
            --primary-dark: #1e293b;
            /* Azul oscuro casi negro */
            --primary-light: #3b82f6;
            /* Azul corporativo */
            --secondary-color: #334155;
            /* Gris azulado */
            --secondary-dark: #1e293b;
            /* Gris oscuro */
            --accent-color: #d97706;
            /* Dorado corporativo */
            --accent-light: #f59e0b;
            /* Dorado claro */
            --success-color: #059669;
            /* Verde profesional */
            --warning-color: #d97706;
            /* Naranja dorado */
            --error-color: #dc2626;
            /* Rojo corporativo */
            --info-color: #0891b2;
            /* Azul información */
            --surface-color: #ffffff;
            --background-color: #f8fafc;
            /* Gris muy claro */
            --on-surface: #1e293b;
            /* Texto principal */
            --text-secondary: #64748b;
            /* Texto secundario */
            --shadow-light: 0 2px 8px rgba(30, 41, 59, 0.1);
            --shadow-medium: 0 4px 16px rgba(30, 41, 59, 0.15);
            --shadow-heavy: 0 8px 24px rgba(30, 41, 59, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Forzar modo claro permanente */
        * {
            color-scheme: light !important;
        }

        html,
        body {
            background-color: #f8fafc !important;
            color: #1e293b !important;
        }

        /* Estilos Generales */
        body {
            background-color: var(--background-color);
            font-family: 'Montserrat', sans-serif;
        }

        /* Header Corporativo */
        .patrol-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-medium);
        }

        .header-navigation {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-light);
        }

        .back-button {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-light);
        }

        .back-button:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .header-title {
            text-align: center;
            flex: 1;
            margin: 0 1rem;
        }

        .title-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--on-surface);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .subtitle-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0.2rem 0 0 0;
            font-weight: 500;
        }

        .header-status {
            width: 50px;
            display: flex;
            justify-content: center;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            background: var(--success-color);
            border-radius: 50%;
            animation: pulse-patrol 2s infinite;
        }

        /* Contenido Principal */
        .patrol-content {
            padding: 0 1rem;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Mejoras para los elementos existentes */

        /* Estilo para los botones existentes */
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #10b981) !important;
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            transition: var(--transition) !important;
            font-weight: 600 !important;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #047857, var(--success-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color), #06b6d4) !important;
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            transition: var(--transition) !important;
            font-weight: 600 !important;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #0e7490, var(--info-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--error-color), #ef4444) !important;
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            transition: var(--transition) !important;
            font-weight: 600 !important;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, var(--error-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            transition: var(--transition) !important;
            font-weight: 600 !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-medium) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light)) !important;
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            transition: var(--transition) !important;
            font-weight: 600 !important;
            color: white !important;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #b45309, var(--accent-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-medium) !important;
            color: white !important;
        }

        .btn-outline-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            border: none !important;
            color: white !important;
            border-radius: 0 var(--border-radius) var(--border-radius) 0 !important;
            font-weight: 600 !important;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
            color: white !important;
        }

        /* Estilo para las cards existentes */
        .card {
            border: none !important;
            border-radius: var(--border-radius) !important;
            box-shadow: var(--shadow-light) !important;
            overflow: hidden !important;
            margin-bottom: 1.5rem !important;
        }

        .card-header {
            border-radius: 0 !important;
            border: none !important;
            font-weight: 600 !important;
            padding: 1rem 1.5rem !important;
        }

        .card-header.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), var(--accent-light)) !important;
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
        }

        .card-header.bg-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280) !important;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        /* Estilo para las tablas */
        .table {
            margin-bottom: 0 !important;
        }

        .table td,
        .table th {
            border: none !important;
            padding: 0.75rem !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--background-color) !important;
        }

        .table-info {
            background: linear-gradient(135deg, var(--info-color), #06b6d4) !important;
            color: white !important;
            font-weight: 600 !important;
        }

        .table-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280) !important;
            color: white !important;
            font-weight: 600 !important;
        }

        /* Estilo para los inputs */
        .form-control {
            border: 2px solid #e2e8f0 !important;
            border-radius: var(--border-radius) 0 0 var(--border-radius) !important;
            padding: 1rem 1.5rem !important;
            font-size: 1rem !important;
            transition: var(--transition) !important;
        }

        .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25) !important;
        }

        /* Estilo para las alertas */
        .alert {
            border: none !important;
            border-radius: var(--border-radius) !important;
            font-weight: 600 !important;
            padding: 1rem 1.5rem !important;
        }

        .alert-dark {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280) !important;
            color: white !important;
        }

        /* Animaciones */
        @keyframes pulse-patrol {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-navigation {
                padding: 0.8rem 1rem;
            }

            .title-text {
                font-size: 1.1rem;
            }

            .subtitle-text {
                font-size: 0.75rem;
            }

            .back-button {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .patrol-content {
                padding: 0 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .header-navigation {
                padding: 0.7rem 0.8rem;
            }

            .title-text {
                font-size: 1rem;
            }

            .subtitle-text {
                font-size: 0.7rem;
            }

            .back-button {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .header-title {
                margin: 0 0.5rem;
            }
        }
    </style>
@endsection
