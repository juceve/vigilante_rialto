<div style="margin-top: 95px">
    @section('title')
        CAJA CHICA
    @endsection

    @if (!$cajachica)
        <br>
        <div class="container">
            <div class="alert alert-warning mb-3" role="alert" style="font-size: 13px;">
                <div class="text-muted">
                    <h5 class="text-center">
                        <strong>NO SE CUENTA CON UNA CAJA CHICA ACTIVA</strong>
                    </h5>
                </div>
            </div>
            <div class="d-grid">
                <button onclick="history.back();" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> VOLVER
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-primary" role="alert" style="font-size: 13px;">
            <div class="text-secondary">
                <h4>
                    <strong><i class="fas fa-wallet"></i> CAJA CHICA</strong>
                </h4>
                <i class="fas fa-user-secret"></i>
                {{ $cajachica->empleado->nombres . ' ' . $cajachica->empleado->apellidos }}
            </div>
        </div>


        <div class="container mt-3">
            {{-- INFO DE LA CAJA --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body py-2" style="font-size: 13px;">
                    <div class="d-flex justify-content-between">
                        <span><strong>Gestión</strong></span>
                        <span>{{ $cajachica->gestion }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Estado</strong></span>
                        <span class="badge bg-success">
                            {{ $cajachica->estado }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Apertura</strong></span>
                        <span>
                            {{ \Carbon\Carbon::parse($cajachica->fecha_apertura)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- SALDO ACTUAL --}}
            <div class="card mb-3 shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <small class="opacity-75">Saldo disponible</small>
                    <h2 class="fw-bold mb-0">
                        Bs {{ number_format($cajachica->saldo_actual, 2) }}
                    </h2>
                </div>
            </div>

            {{-- EGRESOS DEL MES --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-body py-2 text-center">
                    <small class="text-muted">
                        Egresos del mes ({{ now()->format('m/Y') }})
                    </small>
                    <div class="fw-semibold text-danger">
                        Bs {{ number_format($cajachica->egresosDelMes(), 2) }}
                    </div>
                </div>
            </div>



        </div>

        @if ($cajachica->saldo_actual <= 100)
            <div class="alert alert-warning text-center mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                Saldo bajo, rinda gastos pendientes
            </div>
        @endif

        @if ($cajachica->estado === 'CERRADA')
            <div class="alert alert-secondary text-center mt-3">
                Esta caja chica se encuentra cerrada
            </div>
        @endif

        <div class="container d-grid py-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal"
                data-bs-target="#modalNuevoEgreso">
                REGISTRO DE EGRESOS <i class="fas fa-file-invoice-dollar"></i>
            </button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalMovimientos">
                MOVIMIENTOS DE CAJA <i class="fas fa-exchange-alt"></i>
            </button>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalNuevoEgreso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalNuevoEgresoLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <span class="modal-title" id="modalNuevoEgresoLabel"><strong>REGISTRO DE EGRESO -
                                GASTO</strong></span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mt-3 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <strong><i class="fas fa-file-invoice-dollar"></i> Saldo Actual: Bs.
                                    {{ number_format($cajachica->saldo_actual, 2) }}</strong>
                            </div>

                            <div class="card-body">

                                <div class="mb-2">
                                    <label class="form-label">Monto (Bs)</label>
                                    <input type="number" step="0.01" class="form-control" wire:model.defer="monto">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Concepto</label>
                                    <input type="text" class="form-control" wire:model.defer="concepto">
                                </div>

                                {{-- <div class="mb-2">
                                <label class="form-label">Categoría</label>
                                <input type="text" class="form-control" wire:model.defer="categoria">
                            </div> --}}
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="sinComprobante">
                                    <label class="form-check-label" for="sinComprobante">Registrar egreso sin
                                        comprobante</label>
                                </div>


                                <div class="mb-3" id="comprobanteInput">
                                    <label class="form-label">Comprobante (foto)</label>
                                    <input type="file" accept="image/*" class="form-control" wire:ignore>

                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-ban"></i>
                            Cancelar</button>

                        <button class="btn btn-danger" id="btnRegistrarEgreso" onclick="registrarEgreso();">
                            <i class="fas fa-save"></i> Registrar
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalMovimientos" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="modalMovimientosLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h1 class="modal-title fs-5" id="modalMovimientosLabel">Movimientos de Caja</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- filtro --}}

                        <div class="card shadow-sm mb-3">
                            <div class="card-body py-2">

                                <div class="row g-2 align-items-end">

                                    <div class="col-6 col-md-3">
                                        <label class="form-label small">Gestión</label>
                                        <select class="form-select form-select-sm" wire:model="filtroGestion">
                                            @foreach ($gestiones as $anio)
                                                <option value="{{ $anio }}">{{ $anio }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <label class="form-label small">Mes</label>
                                        <select class="form-select form-select-sm" wire:model="filtroMes">
                                            <option value="">Todos</option>
                                            @foreach (range(1, 12) as $m)
                                                <option value="{{ $m }}">
                                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 text-end">
                                        <small class="text-muted">
                                            Movimientos recientes
                                        </small>
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- reporte --}}

                        <div class="card shadow-sm mb-2">
                            <div class="card-body p-0">
                                {{--
                            <table class="table table-sm table-striped mb-0" style="font-size: 13px;">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th class="text-end">Monto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($movimientos as $mov)
                                        @php
                                            $esEgreso = $mov->tipo === 'EGRESO';
                                            $tieneComprobante =
                                                $mov->comprobante &&
                                                file_exists(public_path('storage/' . $mov->comprobante));
                                            $ext = $tieneComprobante
                                                ? pathinfo($mov->comprobante, PATHINFO_EXTENSION)
                                                : null;
                                        @endphp

                                        <tr>
                                            <td class="text-muted align-middle">
                                                {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                            </td>

                                            <td class="align-middle">
                                                {{ Str::limit($mov->concepto, 30) }}
                                            </td>

                                            <td
                                                class="text-end align-middle fw-semibold {{ $esEgreso ? 'text-danger' : 'text-success' }}">
                                                {{ $esEgreso ? '-' : '+' }}
                                                Bs {{ number_format($mov->monto, 2) }}
                                            </td>

                                            <td class="text-center ">
                                                @if ($tieneComprobante)
                                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <!-- Botón para imagen -->
                                                        <button class="btn btn-sm btn-primary"
                                                            onclick="abrirImagen('{{ asset('storage/' . $mov->comprobante) }}')"
                                                            title="Ver imagen">
                                                            <i class="fas fa-file-image"></i>
                                                        </button>
                                                    @elseif (strtolower($ext) === 'pdf')
                                                        <!-- Botón para PDF -->
                                                        <a href="{{ asset('storage/' . $mov->comprobante) }}"
                                                            target="_blank" class="btn btn-sm btn-danger"
                                                            title="Abrir PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-muted" title="Sin comprobante">
                                                        <i class="fas fa-file"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                Sin movimientos
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table> --}}
                                <div class="card shadow-sm mb-2" style="max-height: 400px; overflow-y: auto;">
                                    <div class="card-body p-0">

                                        <table class="table table-sm table-striped mb-0" style="font-size: 13px;">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th class="text-end">Monto</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($movimientos as $mov)
                                                    @php
                                                        $esEgreso = $mov->tipo === 'EGRESO';
                                                        $tieneComprobante =
                                                            $mov->comprobante &&
                                                            file_exists(public_path('storage/' . $mov->comprobante));
                                                        $ext = $tieneComprobante
                                                            ? pathinfo($mov->comprobante, PATHINFO_EXTENSION)
                                                            : null;
                                                    @endphp

                                                    <tr>
                                                        <td class="text-muted align-middle">
                                                            {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                                        </td>

                                                        <td class="align-middle">
                                                            {{ Str::limit($mov->concepto, 30) }}
                                                        </td>

                                                        <td
                                                            class="text-end align-middle fw-semibold {{ $esEgreso ? 'text-danger' : 'text-success' }}">
                                                            {{ $esEgreso ? '-' : '+' }}
                                                            Bs {{ number_format($mov->monto, 2) }}
                                                        </td>

                                                        <td class="text-center ">
                                                            @if ($tieneComprobante)
                                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                                    <button class="btn btn-sm btn-primary"
                                                                        onclick="abrirImagen('{{ asset('storage/' . $mov->comprobante) }}')"
                                                                        title="Ver imagen">
                                                                        <i class="fas fa-file-image"></i>
                                                                    </button>
                                                                @elseif (strtolower($ext) === 'pdf')
                                                                    <a href="{{ asset('storage/' . $mov->comprobante) }}"
                                                                        target="_blank" class="btn btn-sm btn-danger"
                                                                        title="Abrir PDF">
                                                                        <i class="fas fa-file-pdf"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <span class="text-muted" title="Sin comprobante">
                                                                    <i class="fas fa-file"></i>
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3">
                                                            Sin movimientos
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-ban"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mostrar imagen -->
        <div class="modal fade" id="modalImagen" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprobante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="imagenComprobante" src="" alt="Comprobante" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
{{-- @section('js')
    <script src="https://unpkg.com/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

    <script>
        function abrirImagen(url) {
            const img = document.getElementById('imagenComprobante');
            img.src = url;
            const modalEl = document.getElementById('modalImagen');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    </script>

    <script>
        let procesandoArchivo = false;

        function setEstadoBoton(estado) {
            const btn = document.getElementById('btnRegistrarEgreso');
            if (!btn) return;

            btn.disabled = estado;

            btn.innerHTML = estado ?
                '<span class="spinner-border spinner-border-sm"></span> Procesando...' :
                '<i class="fas fa-save"></i> Registrar';
        }



        document.addEventListener('DOMContentLoaded', () => {
            procesandoArchivo = true;
            setEstadoBoton(true);


            const input = document.getElementById('comprobanteInput');
            if (!input) return;

            input.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (!file) return;

                // PDF → solo validar tamaño
                if (file.type === 'application/pdf') {
                    if (file.size > 500 * 1024) {
                        alert('El PDF supera los 500 KB');
                        e.target.value = '';
                        procesandoArchivo = false;
                        setEstadoBoton(false);
                    } else {
                        // PDF válido, enviamos a Livewire
                        @this.upload(
                            'comprobante',
                            file,
                            () => {
                                console.log('PDF enviado.');
                                procesandoArchivo = false;
                                setEstadoBoton(false);
                            },
                            (error) => {
                                console.error(error);
                                alert('Error al subir el PDF');
                                procesandoArchivo = false;
                                setEstadoBoton(false);
                            }
                        );
                    }
                    return; // Salimos del handler
                }


                // 🔥 obtener función correcta
                const compress = window.imageCompression?.default ?? window.imageCompression;

                if (!compress) {
                    alert('No se pudo cargar el compresor de imágenes');
                    return;
                }

                const options = {
                    maxSizeMB: 0.5,
                    maxWidthOrHeight: 1600,
                    useWebWorker: true,
                    initialQuality: 0.7
                };

                try {
                    const compressedBlob = await compress(file, options);

                    // 🔥 Convertir Blob a File
                    const compressedFile = new File(
                        [compressedBlob],
                        file.name, {
                            type: compressedBlob.type,
                            lastModified: Date.now()
                        }
                    );

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    input.files = dataTransfer.files;

                    console.log(
                        `Comprimido: ${(compressedFile.size / 1024).toFixed(1)} KB`
                    );

                    // Enviar archivo a Livewire
                    @this.upload(
                        'comprobante',
                        compressedFile,
                        () => {
                            console.log('Archivo enviado.');
                            procesandoArchivo = false;
                            setEstadoBoton(false);
                        },
                        (error) => {
                            console.error(error);
                            alert('Error al subir comprobante');
                            procesandoArchivo = false;
                            setEstadoBoton(false);
                        }
                    );



                } catch (err) {
                    console.error(err);
                    alert('Error al comprimir la imagen');
                    input.value = '';

                    procesandoArchivo = false;
                    setEstadoBoton(false);
                }

            });

        });
    </script>

    <script>
        function registrarEgreso() {

            if (procesandoArchivo) {
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'El comprobante aún se está procesando'
                });
                return;
            }

            Swal.fire({
                title: "REGISTRAR EGRESO",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",
                cancelButtonColor: "#2c3e50",
                confirmButtonText: "Si, registrar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('guardarEgreso');
                }
            });
        }
    </script>

    <script>
        Livewire.on('openModal', () => {

            const modalEl = document.getElementById('modalNuevoEgreso');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('modalNuevoEgreso');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>
@endsection --}}
@section('js')
    <script src="https://unpkg.com/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

    <script>
        function abrirImagen(url) {
            const img = document.getElementById('imagenComprobante');
            img.src = url;
            const modalEl = document.getElementById('modalImagen');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    </script>

    <script>
        let procesandoArchivo = false;

        function setEstadoBoton(estado) {
            const btn = document.getElementById('btnRegistrarEgreso');
            if (!btn) return;

            btn.disabled = estado;

            btn.innerHTML = estado ?
                '<span class="spinner-border spinner-border-sm"></span> Procesando...' :
                '<i class="fas fa-save"></i> Registrar';
        }

        // Habilitar/deshabilitar botón según checkbox y archivo
        function actualizarEstadoBoton() {
            const btn = document.getElementById('btnRegistrarEgreso');
            const input = document.getElementById('comprobanteInput');
            const checkbox = document.getElementById('sinComprobante');

            if (!btn) return;

            // Si el checkbox está marcado → habilitamos el botón
            if (checkbox && checkbox.checked) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Registrar';
                return;
            }

            // Si hay un input file
            if (input && input.files && input.files.length > 0) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Registrar';
            } else {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-save"></i> Registrar'; // quitar spinner
            }
        }



        document.addEventListener('DOMContentLoaded', () => {
            // Inicializamos botón deshabilitado
            setEstadoBoton(true);

            const input = document.getElementById('comprobanteInput');
            const checkbox = document.getElementById('sinComprobante');

            if (checkbox) {
                checkbox.addEventListener('change', actualizarEstadoBoton);
            }

            if (!input) return;

            input.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (!file) {
                    actualizarEstadoBoton();
                    return;
                }

                // PDF → validar tamaño
                if (file.type === 'application/pdf') {
                    if (file.size > 500 * 1024) {
                        alert('El PDF supera los 500 KB');
                        e.target.value = '';
                        procesandoArchivo = false;
                        actualizarEstadoBoton();
                    } else {
                        procesandoArchivo = true;
                        setEstadoBoton(true);
                        // Subir a Livewire
                        @this.upload(
                            'comprobante',
                            file,
                            () => {
                                console.log('PDF enviado.');
                                procesandoArchivo = false;
                                actualizarEstadoBoton();
                            },
                            (error) => {
                                console.error(error);
                                alert('Error al subir el PDF');
                                procesandoArchivo = false;
                                actualizarEstadoBoton();
                            }
                        );
                    }
                    return;
                }

                // Imagen → comprimir
                const compress = window.imageCompression?.default ?? window.imageCompression;
                if (!compress) {
                    alert('No se pudo cargar el compresor de imágenes');
                    return;
                }

                procesandoArchivo = true;
                setEstadoBoton(true);

                const options = {
                    maxSizeMB: 0.5,
                    maxWidthOrHeight: 1600,
                    useWebWorker: true,
                    initialQuality: 0.7
                };

                try {
                    const compressedBlob = await compress(file, options);
                    const compressedFile = new File([compressedBlob], file.name, {
                        type: compressedBlob.type,
                        lastModified: Date.now()
                    });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    input.files = dataTransfer.files;

                    console.log(`Comprimido: ${(compressedFile.size / 1024).toFixed(1)} KB`);

                    @this.upload(
                        'comprobante',
                        compressedFile,
                        () => {
                            console.log('Archivo enviado.');
                            procesandoArchivo = false;
                            actualizarEstadoBoton();
                        },
                        (error) => {
                            console.error(error);
                            alert('Error al subir comprobante');
                            procesandoArchivo = false;
                            actualizarEstadoBoton();
                        }
                    );

                } catch (err) {
                    console.error(err);
                    alert('Error al comprimir la imagen');
                    input.value = '';
                    procesandoArchivo = false;
                    actualizarEstadoBoton();
                }
            });

            // Inicializamos estado al cargar modal
            actualizarEstadoBoton();
        });

        function registrarEgreso() {

            if (procesandoArchivo) {
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'El comprobante aún se está procesando'
                });
                return;
            }

            Swal.fire({
                title: "REGISTRAR EGRESO",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",
                cancelButtonColor: "#2c3e50",
                confirmButtonText: "Si, registrar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('guardarEgreso');

                }
            });
        }

        Livewire.on('openModal', () => {
            const modalEl = document.getElementById('modalNuevoEgreso');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('modalNuevoEgreso');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            // --- Resetear input y checkbox ---
            const checkbox = document.getElementById('sinComprobante');
            const inputFile = document.getElementById('comprobanteInput');

            if (checkbox) checkbox.checked = false;
            if (inputFile) {
                inputFile.value = '';
                inputFile.style.display = 'block';
            }

            // Resetear estado del botón
            actualizarEstadoBoton();
        });
    </script>
    <script>
        // Toggle input file según el checkbox
        function toggleInputComprobante() {
            const checkbox = document.getElementById('sinComprobante');
            const inputFile = document.getElementById('comprobanteInput');

            if (!checkbox || !inputFile) return;

            if (checkbox.checked) {
                inputFile.style.display = 'none';
                inputFile.value = ''; // Limpiar archivo si había seleccionado
            } else {
                inputFile.style.display = 'block';
            }

            // Actualizamos el estado del botón
            actualizarEstadoBoton();
        }

        // Inicializamos eventos
        document.addEventListener('DOMContentLoaded', () => {
            const checkbox = document.getElementById('sinComprobante');
            if (checkbox) {
                checkbox.addEventListener('change', toggleInputComprobante);
                toggleInputComprobante(); // Inicializamos
            }

            const inputFile = document.getElementById('comprobanteInput');
            if (inputFile) {
                inputFile.addEventListener('change', actualizarEstadoBoton);
            }
        });
    </script>
@endsection
