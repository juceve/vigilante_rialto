<style>
    .btn-xs {
        padding: 0.15rem 0.4rem;
        font-size: 0.75rem;
        line-height: 1.2;
        border-radius: 0.15rem;
    }
</style>
<div class="row mb-2">
    <div class="col-12 col-md-9 mb-2">
        <strong>DOTACIONES - Contrato ID:
            {{ $contratoActivo ? cerosIzq($contratoActivo->id) : 'Sin definir' }}</strong>
    </div>
    <div class="col-12 col-md-3 mb-2">
        @if ($contratoActivo)
            <button class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target="#modalDotaciones">
                Nuevo <i class="fas fa-plus"></i>
            </button>
        @endif
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm" id="tabla-dotaciones" style="width: 100%">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Responsable Entrega</th>
                <th>Acciones</th> <!-- modified -->
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDotaciones" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalDotacionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDotacionesLabel">Formulario de Dotaciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar3()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Campos del modelo Rrhhdotacion -->
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Fecha Solicitud</span>
                            </div>
                            <input type="date" class="form-control" id="fecha3" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Responsable Entrega</span>
                            </div>
                            <input type="text" class="form-control" id="responsable_entrega"
                                placeholder="Nombre de quien entrega">
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Campos del modelo Rrhhdetalledotacion -->
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Detalle</span>
                            </div>
                            <input type="text" step="any" class="form-control" id="detalle3"
                                placeholder="Detalle de la dotación">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Cant.</span>
                            </div>
                            <input type="number" id="cantidad3" class="form-control" value="1" placeholder="1">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Estado</span>
                            </div>
                            <select class="form-control" id="rrhhestadodotacion_id">
                                <option value="">Seleccione un tipo</option>
                                @foreach ($estadoDots as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imagenDot" accept="image/*">
                                    <label class="custom-file-label" for="imagenDot">Seleccionar imagen</label>
                                </div>
                            </div>
                            <div class="mt-2" id="previewContainer" style="display:none;">
                                <img id="previewImage" src="#" alt="Vista previa" class="img-fluid img-thumbnail"
                                    style="max-height:150px;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        id="removePreviewBtn">Quitar
                                        imagen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-12 col-md-3">

                    </div> --}}
                    <div class="col-12 col-md-2">
                        <div wire:loading.remove>
                            <button class="btn btn-success btn-block" onclick="agregarFila()">Agregar <i
                                    class="fas fa-arrow-down"></i></button>

                        </div>
                        <div wire:loading>
                            <div class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <span>Detalles</span>
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="tablaDetalles" style="font-size: 12px;">
                        <thead class="table-success">
                            <tr>
                                <th>Detalle</th>
                                <th style="width:60px;" class="text-center">Imagen</th>
                                <th class="text-center">Cantidad</th>
                                <th>Estado</th>
                                <th style="width: 25px"></th>
                            </tr>
                        </thead>
                        <tbody id="bodyDetalles" style="vertical-align: middle;">
                        </tbody>
                    </table>
                </div>

                <hr>
                <div class="text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiar3()"><i
                            class="fas fa-ban"></i>
                        Cancelar</button>
                    <button type="button" id="btnEdit3" class="btn btn-warning d-none"
                        onclick="updateDotacion()">Actualizar
                        Dotación
                        <i class="fas fa-save"></i></button>
                    <button type="button" id="btnRegist3" class="btn btn-info"
                        onclick="registrarDotacion()">Registrar
                        Dotación <i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalEditDotacion" tabindex="-1"
    aria-labelledby="modalEditDotacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditDotacionLabel">Editar Dotación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rrhhdotacion_id">
                <div class="spinner-border text-primary d-none" id="spinner3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div id="data3">
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" class="form-control" id="fecha4">
                    </div>
                    <div class="form-group">
                        <label>Detalle:</label>
                        <input type="text" class="form-control" id="detalle4">
                    </div>
                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad4">
                    </div>
                    <div class="form-group">
                        <label>Estado:</label>
                        <select class="form-control" id="rrhhestadodotacion_id4">
                            <option value="">Seleccione un tipo</option>
                            @foreach ($estadoDots as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                    Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="updateDotacion()">Actualizar Dotación <i
                        class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ver Detalle -->
<div class="modal fade" id="modalVerDotacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle Dotación <span id="ver_dotacion_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong>Fecha:</strong> <span id="ver_fecha"></span></p>
                <p><strong>Responsable Entrega:</strong> <span id="ver_responsable"></span></p>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tablaVerDetalles">
                        <thead class="thead-light">
                            <tr>
                                <th>Detalle</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="ver_bodyDetalles"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Previsualización modal -->
<div class="modal fade" id="modalPreviewImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-2">
                <img id="modalPreviewImg" src="#" alt="Preview" class="img-fluid" style="max-height:70vh;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('js7')
    <script>
        $('.nav-dotaciones').click(function() {
            cargarTablaDotaciones();
        });
    </script>

    <script>
        function renderizarPDF(id, contrato_id) {
            var win = window.open("../../../pdf/acta-dotacion-empleado/" + id + "/" + contrato_id, '_blank');
            win.focus();
        }
    </script>

    <script>
        // Reemplazo: usar siempre "responsable_entrega"
        // Array para guardar los detalles
        let datos = [];
        // imágenes comprimidas temporales: map index => { blob, name }
        let imagesBlobs = {};
        let imageCounter = 0;

        // detect input id (soporta ambas variantes)
        function getImageInput() {
            return document.getElementById('imagenDot') || document.getElementById('imagen3') || null;
        }

        // compressImage: recibe File -> Promise<Blob> (JPEG) intentando <= maxSizeKB
        function compressImage(file, maxSizeKB = 200) {
            return new Promise((resolve, reject) => {
                if (!file || !file.type.startsWith('image/')) {
                    return reject(new Error('Archivo no es imagen'));
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let [w, h] = [img.width, img.height];
                        // limitar resolución inicial si muy grande
                        const maxDim = 1600; // to avoid huge canvas
                        if (Math.max(w, h) > maxDim) {
                            const ratio = maxDim / Math.max(w, h);
                            w = Math.round(w * ratio);
                            h = Math.round(h * ratio);
                        }
                        canvas.width = w;
                        canvas.height = h;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, w, h);

                        // try progressive quality reduction and resizing if needed
                        (function tryCompress(quality, scale) {
                            canvas.width = Math.round(w * scale);
                            canvas.height = Math.round(h * scale);
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                            canvas.toBlob((blob) => {
                                if (!blob) return reject(new Error('No se pudo generar blob'));
                                const sizeKB = blob.size / 1024;
                                if (sizeKB <= maxSizeKB || (quality <= 0.4 && scale <= 0.5)) {
                                    // done
                                    // normalize to jpeg
                                    if (blob.type !== 'image/jpeg') {
                                        // convert blob to jpeg by drawing into canvas again and toBlob
                                        const img2 = new Image();
                                        const url = URL.createObjectURL(blob);
                                        img2.onload = () => {
                                            const c2 = document.createElement('canvas');
                                            c2.width = img2.width;
                                            c2.height = img2.height;
                                            c2.getContext('2d').drawImage(img2, 0, 0);
                                            c2.toBlob((jb) => {
                                                    URL.revokeObjectURL(url);
                                                    resolve(jb);
                                                }, 'image/jpeg', Math.max(0.35,
                                                quality));
                                        };
                                        img2.onerror = () => {
                                            URL.revokeObjectURL(url);
                                            resolve(blob); // fallback
                                        };
                                        img2.src = url;
                                    } else {
                                        resolve(blob);
                                    }
                                } else {
                                    // reduce quality first, then scale down if needed
                                    if (quality > 0.5) {
                                        tryCompress(quality - 0.15, scale);
                                    } else {
                                        tryCompress(quality, Math.max(0.5, scale - 0.1));
                                    }
                                }
                            }, 'image/jpeg', quality);
                        })(0.9, 1.0);
                    };
                    img.onerror = () => reject(new Error('Error cargando imagen'));
                    img.src = e.target.result;
                };
                reader.onerror = () => reject(new Error('Error leyendo archivo'));
                reader.readAsDataURL(file);
            });
        }

        // Preview handling (supports both ids)
        function resetPreview() {
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const input = getImageInput();
            if (input) input.value = '';
            if (previewContainer) previewContainer.style.display = 'none';
            if (previewImage) previewImage.src = '#';
            const label = document.querySelector('label[for="imagenDot"].custom-file-label') || document.querySelector(
                'label[for="imagen3"].custom-file-label');
            if (label) label.textContent = 'Seleccionar imagen';
        }

        // attach listeners for preview
        (function initImageInput() {
            document.addEventListener('DOMContentLoaded', function() {
                const input = getImageInput();
                const previewContainer = document.getElementById('previewContainer');
                const previewImage = document.getElementById('previewImage');
                const removePreviewBtn = document.getElementById('removePreviewBtn');

                if (input) {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files && e.target.files[0];
                        const label = document.querySelector(
                            `label[for="${input.id}"].custom-file-label`);
                        if (label) label.textContent = file ? file.name : 'Seleccionar imagen';

                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                if (previewImage) {
                                    previewImage.src = ev.target.result;
                                    if (previewContainer) previewContainer.style.display = 'block';
                                }
                            };
                            reader.readAsDataURL(file);
                        } else {
                            if (previewContainer) previewContainer.style.display = 'none';
                            if (previewImage) previewImage.src = '#';
                        }
                    });
                }
                if (removePreviewBtn) {
                    removePreviewBtn.addEventListener('click', function() {
                        resetPreview();
                    });
                }

                // integrate with limpiar3
                const originalLimpiar3 = window.limpiar3;
                if (typeof originalLimpiar3 === 'function') {
                    window.limpiar3 = function() {
                        try {
                            originalLimpiar3();
                        } catch (e) {
                            console.error(e);
                        }
                        // reset images state
                        imagesBlobs = {};
                        imageCounter = 0;
                        resetPreview();
                    };
                }
            });
        })();

        // agregarFila ahora async para comprimir imagen antes de añadir
        async function agregarFila() {
            const detalle = document.getElementById("detalle3").value.trim();
            const cantidadVal = document.getElementById("cantidad3").value.trim();
            const cantidad = parseInt(cantidadVal, 10);
            const selectEstado = document.getElementById("rrhhestadodotacion_id");
            const estado = selectEstado.value;
            const estadoTexto = selectEstado.options[selectEstado.selectedIndex]?.text || '';

            // Validación básica
            if (!detalle || isNaN(cantidad) || cantidad <= 0 || !estado) {
                Swal.fire('Error', 'Por favor complete todos los campos del detalle correctamente.', 'error');
                return;
            }

            // check image input
            const input = getImageInput();
            let image_index = null;
            let image_name = null;
            if (input && input.files && input.files[0]) {
                const file = input.files[0];
                try {
                    const compressedBlob = await compressImage(file, 200); // target 200KB
                    image_index = imageCounter++;
                    // set extension .jpg
                    const filename = (file.name || 'image').replace(/\.[^/.]+$/, '') + '.jpg';
                    imagesBlobs[image_index] = {
                        blob: compressedBlob,
                        name: filename
                    };
                } catch (err) {
                    console.error('Error comprimiendo imagen:', err);
                    Swal.fire('Error', 'No se pudo procesar la imagen. Intente otra.', 'error');
                    return;
                }
            }

            // Guardar en el array
            datos.push({
                detalle: detalle,
                cantidad: cantidad,
                estado: estado,
                estadoTexto: estadoTexto,
                image_index: image_index, // null si no hay imagen
            });

            // Limpiar campos del formulario y preview
            document.getElementById("detalle3").value = "";
            document.getElementById("cantidad3").value = "1";
            document.getElementById("rrhhestadodotacion_id").value = "";
            resetPreview();

            // Actualizar tabla
            renderizarTabla3();
        }

        // renderizar tabla ahora muestra columna de imagen separada y permite previsualizar
        function renderizarTabla3() {
            const tabla = document.getElementById("tablaDetalles");
            const tbody = document.getElementById("bodyDetalles");

            // Mostrar u ocultar la tabla
            if (!datos || datos.length === 0) {
                tabla.style.display = "none";
                tbody.innerHTML = "";
                return;
            }

            tabla.style.display = "table";
            tbody.innerHTML = ""; // Limpiar tabla antes de volver a llenarla

            datos.forEach((item, index) => {
                // hay imagen si existe imagen nueva (image_index) o URL existente (item.url)
                const hasImage = (item.image_index !== null && item.image_index !== undefined) || !!item.url;
                // construir celda imagen
                let imageCell = '<td class="text-center align-middle">';
                if (item.image_index !== null && item.image_index !== undefined) {
                    // imagen temporal (blob)
                    imageCell +=
                        `<a href="javascript:void(0)" onclick="previewBlob(${item.image_index})" title="Ver imagen"><i class="fas fa-image text-primary" style="font-size:18px;cursor:pointer;"></i></a>`;
                } else if (item.url) {
                    // imagen almacenada (url relativa, e.g. storage/...)
                    const safeUrl = item.url;
                    imageCell +=
                        `<a href="javascript:void(0)" onclick="showPreview('${safeUrl}')" title="Ver imagen"><i class="fas fa-image text-primary" style="font-size:18px;cursor:pointer;"></i></a>`;
                } else {
                    imageCell += '&mdash;';
                }
                imageCell += '</td>';

                const fila = document.createElement("tr");
                fila.innerHTML = `
				<td class="align-middle">${item.detalle}</td>
				${imageCell}
				<td class="align-middle text-center">${item.cantidad}</td>
				<td class="align-middle">${item.estadoTexto}</td>
				<td class="text-right align-middle"><button class="btn btn-xs btn-outline-danger" onclick="eliminarFila(${index})" title="Quitar"><i class="fas fa-trash"></i></button></td>
			`;
                tbody.appendChild(fila);
            });
        }

        function eliminarFila(index) {
            const removed = datos.splice(index, 1);
            // si el detalle eliminado tenía una imagen comprimida en imagesBlobs, eliminarla
            if (removed && removed[0] && removed[0].image_index !== null && removed[0].image_index !== undefined) {
                delete imagesBlobs[removed[0].image_index];
            }
            renderizarTabla3();
        }

        // --- Preview helpers: mostrar imágenes (URL relativas o blobs temporales) ---
        let _currentObjectUrl = null;

        function showPreview(url) {
            const img = document.getElementById('modalPreviewImg');
            if (!img || !url) return;
            let finalUrl = url;
            try {
                const u = String(url).trim();
                const lower = u.toLowerCase();
                // Si es blob:, data:, ya es absoluta (http/https) o inicia con '/', usar tal cual.
                const isBlob = lower.startsWith('blob:');
                const isData = lower.startsWith('data:');
                const isAbsolute = lower.startsWith('http://') || lower.startsWith('https://') || u.startsWith('/');
                if (!isBlob && !isData && !isAbsolute) {
                    // rutas tipo 'storage/...' o similares: anteponer base sólo si no es absoluta
                    finalUrl = "{{ url('/') }}/" + u.replace(/^\/+/, '');
                }
            } catch (e) {
                // en caso de cualquier error, usar la URL tal cual
                finalUrl = url;
            }
            img.src = finalUrl;
            $('#modalPreviewImage').modal('show');
        }

        function previewBlob(index) {
            const entry = imagesBlobs[index];
            if (!entry || !entry.blob) return;
            // revocar previo si existe
            if (_currentObjectUrl) {
                URL.revokeObjectURL(_currentObjectUrl);
                _currentObjectUrl = null;
            }
            _currentObjectUrl = URL.createObjectURL(entry.blob);
            // pasar blob: URL directamente (showPreview detectará blob: y no antepondrá base)
            showPreview(_currentObjectUrl);
        }

        // revocar objectURL al cerrar modal
        $('#modalPreviewImage').on('hidden.bs.modal', function() {
            if (_currentObjectUrl) {
                URL.revokeObjectURL(_currentObjectUrl);
                _currentObjectUrl = null;
            }
            const img = document.getElementById('modalPreviewImg');
            if (img) img.src = '#';
        });

        // exponer para handlers inline
        window.showPreview = showPreview;
        window.previewBlob = previewBlob;
        // --- FIN preview helpers ---

        // Registrar nueva dotación (envía detalles como JSON string en FormData) - ahora adjunta images[]
        async function registrarDotacion() {
            const fecha = $('#fecha3').val();
            const responsable = $('#responsable_entrega').val(); // USAR responsable_entrega
            if (!fecha || !responsable) {
                alert('Por favor complete Fecha y Responsable de entrega.');
                return;
            }
            if (!datos || datos.length === 0) {
                alert('Debe agregar al menos un detalle.');
                return;
            }

            const formData = new FormData();
            formData.append('fecha', fecha);
            formData.append('responsable_entrega', responsable);
            formData.append('detalles', JSON.stringify(datos));
            formData.append('rrhhcontrato_id', {{ $contratoActivo?->id ?? 'null' }});
            formData.append('empleado_id', {{ $contratoActivo?->empleado->id ?? 'null' }});

            // attach images blobs
            for (const idx in imagesBlobs) {
                if (imagesBlobs.hasOwnProperty(idx)) {
                    const item = imagesBlobs[idx];
                    // key as images[index] so Laravel receives as array
                    formData.append('images[' + idx + ']', item.blob, item.name);
                }
            }

            try {
                const resp = await fetch('{{ route('dotaciones.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const text = await resp.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error('Respuesta inesperada del servidor: ' + text);
                }
                if (data.success) {
                    cargarTablaDotaciones();
                    $('#modalDotaciones').modal('hide');
                    limpiar3();
                    // reset images state
                    imagesBlobs = {};
                    imageCounter = 0;
                    Swal.fire('Excelente', data.message || 'Dotación registrada correctamente.', 'success');
                } else {
                    const errMsg = data.message || 'Error en servidor';
                    const errs = (data.errors || []).join('\n');
                    console.error('Error registrar dotación:', data);
                    Swal.fire('Error', errMsg + (errs ? ('\n' + errs) : ''), 'error');
                }
            } catch (error) {
                console.error('Fetch registrarDotacion error:', error);
                alert('Ocurrió un error al registrar la dotación. Revise la consola para más detalles.');
            }
        }

        // updateDotacion: attach images similar to registrar
        async function updateDotacion() {
            const id = $('#rrhhdotacion_id').val();
            const fecha = $('#fecha3').val();
            const responsable = $('#responsable_entrega').val(); // USAR responsable_entrega

            if (!fecha || !responsable) {
                Swal.fire('Error', 'Por favor complete Fecha y Responsable de entrega.', 'error');
                return;
            }
            if (!datos || datos.length === 0) {
                Swal.fire('Error', 'Debe agregar al menos un detalle.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('rrhhdotacion_id', id);
            formData.append('fecha', fecha);
            formData.append('responsable_entrega', responsable);
            formData.append('detalles', JSON.stringify(datos));

            for (const idx in imagesBlobs) {
                if (imagesBlobs.hasOwnProperty(idx)) {
                    const item = imagesBlobs[idx];
                    formData.append('images[' + idx + ']', item.blob, item.name);
                }
            }

            try {
                const resp = await fetch('{{ route('dotaciones.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const text = await resp.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error('Respuesta inesperada del servidor: ' + text);
                }
                if (data.success) {
                    cargarTablaDotaciones();
                    $('#modalDotaciones').modal('hide');
                    limpiar3();
                    imagesBlobs = {};
                    imageCounter = 0;
                    Swal.fire('Excelente', data.message || 'Dotación actualizada correctamente.', 'success');
                } else {
                    const errMsg = data.message || 'Error en servidor';
                    const errs = (data.errors || []).join('\n');
                    console.error('Error actualizar dotación:', data);
                    Swal.fire('Error', errMsg + (errs ? ('\n' + errs) : ''), 'error');
                }
            } catch (error) {
                console.error('Fetch updateDotacion error:', error);
                alert('Ocurrió un error al actualizar la dotación. Revise la consola para más detalles.');
            }
        }

        // --- AÑADIR: función faltante cargarTablaDotaciones ---
        let tablaDotaciones = null;

        function cargarTablaDotaciones() {
            // si ya existe la tabla, recargar datos (sin reiniciar paginación)
            if (tablaDotaciones) {
                tablaDotaciones.ajax.reload(null, false);
                return;
            }

            tablaDotaciones = $('#tabla-dotaciones').DataTable({
                ajax: '{{ route('dotaciones.data', $contratoActivo ? $contratoActivo->id : 0) }}',
                columns: [{
                        data: 'id',
                        title: 'ID',
                        className: 'text-center'
                    },
                    {
                        data: 'fecha',
                        title: 'Fecha',
                        className: 'text-center'
                    },
                    {
                        data: 'responsable_entrega',
                        title: 'Responsable Entrega',
                        className: 'text-left'
                    },
                    {
                        data: 'actions',
                        title: 'Acciones',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
                language: {
                    url: '{{ asset('plugins/es-ES.json') }}'
                },
                // Evitar que errores de servidor rompan DataTables: mostrar mensaje en consola
                error: function(xhr, error, thrown) {
                    console.error('DataTables AJAX error:', {
                        xhr,
                        error,
                        thrown
                    });
                }
            });

            setTimeout(() => {
                tablaDotaciones.columns.adjust().draw();
            }, 300);
        }
        // Exponer la función globalmente para evitar ReferenceError si se invoca antes de su declaración
        window.cargarTablaDotaciones = cargarTablaDotaciones;
        // --- FIN función cargarTablaDotaciones ---

        // New: verDetalle function to fetch dotación with detalles and show modal
        // function verDetalle(id) {
        //     console.log(id);

        //     const formData = new FormData();
        //     formData.append('rrhhdotacion_id', id);

        //     fetch('{{ route('dotaciones.edit') }}', {
        //         method: 'POST',
        //         body: formData,
        //         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (!data.success) {
        //             throw new Error(data.message || 'No se encontró la dotación');
        //         }
        //         const d = data.message;
        //         document.getElementById('ver_dotacion_id').textContent = ' #' + d.id;
        //         document.getElementById('ver_fecha').textContent = d.fecha;
        //         document.getElementById('ver_responsable').textContent = d.responsable_entrega ?? '';

        //         // render detalles
        //         const tbody = document.getElementById('ver_bodyDetalles');
        //         tbody.innerHTML = '';
        //         (d.detalles || []).forEach(det => {
        //             const tr = document.createElement('tr');
        //             const estadoTxt = det.estado?.nombre ?? (det.rrhhestadodotacion?.nombre ?? '');
        //             tr.innerHTML = `<td>${det.detalle}</td><td class="text-center">${det.cantidad}</td><td>${estadoTxt}</td>`;
        //             tbody.appendChild(tr);
        //         });

        //         $('#modalVerDotacion').modal('show');
        //     })
        //     .catch(error => {
        //         console.error('Error verDetalle:', error);
        //         Swal.fire('Error', error.message || 'Ocurrió un error al obtener detalles.', 'error');
        //     });
        // }

        // init
        $(document).ready(function() {
            renderizarTabla3();
        });
    </script>

    <script>
        // File input preview handlers
        document.addEventListener('DOMContentLoaded', function() {
            const inputImagen = document.getElementById('imagen3');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const removePreviewBtn = document.getElementById('removePreviewBtn');

            function resetPreview() {
                if (inputImagen) inputImagen.value = '';
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                }
                if (previewImage) {
                    previewImage.src = '#';
                }
                // restore custom-file-label text
                const label = document.querySelector('label[for="imagen3"].custom-file-label');
                if (label) label.textContent = 'Seleccionar imagen';
            }

            if (inputImagen) {
                inputImagen.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    const label = document.querySelector('label[for="imagen3"].custom-file-label');
                    if (label) label.textContent = file ? file.name : 'Seleccionar imagen';

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            previewImage.src = ev.target.result;
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // not image or no file
                        previewContainer.style.display = 'none';
                        previewImage.src = '#';
                    }
                });
            }

            if (removePreviewBtn) {
                removePreviewBtn.addEventListener('click', function() {
                    resetPreview();
                });
            }

            // Integrar con la función limpiar3 existente: interceptamos y limpiamos preview
            const originalLimpiar3 = window.limpiar3;
            if (typeof originalLimpiar3 === 'function') {
                window.limpiar3 = function() {
                    // call original limpiar3
                    try {
                        originalLimpiar3();
                    } catch (e) {
                        console.error(e);
                    }
                    // then reset preview
                    resetPreview();
                };
            }
        });
    </script>

    <script>
        // Agregar/asegurar función limpiar3 disponible globalmente
        function limpiar3() {
            try {
                const hoy = new Date();
                const fechaFormateada = hoy.toISOString().split('T')[0];
                const fechaInput = document.getElementById('fecha3');
                if (fechaInput) fechaInput.value = fechaFormateada;

                const estadoSelect = document.getElementById('rrhhestadodotacion_id');
                if (estadoSelect) estadoSelect.value = '';

                const detalleInput = document.getElementById('detalle3');
                if (detalleInput) detalleInput.value = '';

                const cantidadInput = document.getElementById('cantidad3');
                if (cantidadInput) cantidadInput.value = '1';

                const responsableInput = document.getElementById('responsable_entrega');
                if (responsableInput) responsableInput.value = '';

                // limpiar arrays y estados temporales
                datos = [];
                imagesBlobs = {};
                imageCounter = 0;

                // intentar limpiar preview si existe
                if (typeof resetPreview === 'function') {
                    try {
                        resetPreview();
                    } catch (e) {
                        console.debug('resetPreview error', e);
                    }
                } else {
                    // fallback: ocultar preview directo
                    const previewContainer = document.getElementById('previewContainer');
                    const previewImage = document.getElementById('previewImage');
                    if (previewContainer) previewContainer.style.display = 'none';
                    if (previewImage) previewImage.src = '#';
                }

                // re-render tabla detalles vacía
                if (typeof renderizarTabla3 === 'function') {
                    try {
                        renderizarTabla3();
                    } catch (e) {
                        console.debug('renderizarTabla3 error', e);
                    }
                }

                // ajustar botones
                const btnEdit3 = document.getElementById('btnEdit3');
                const btnRegist3 = document.getElementById('btnRegist3');
                if (btnEdit3) btnEdit3.classList.add('d-none');
                if (btnRegist3) btnRegist3.classList.remove('d-none');

                // limpiar id oculto
                const rrhhdotacionId = document.getElementById('rrhhdotacion_id');
                if (rrhhdotacionId) rrhhdotacionId.value = '';
            } catch (err) {
                console.error('Error en limpiar3:', err);
            }
        }
        // Exponer globalmente para que onclick y otros scripts lo encuentren
        window.limpiar3 = limpiar3;
    </script>

    <script>
        // editar3: cargar dotación para edición (incluye url/imagen)
        async function editar3(rrhhdotacion_id) {
            // mostrar spinner y ocultar campos si existen
            const spinner3 = document.getElementById('spinner3');
            const body3 = document.getElementById('data3');
            if (spinner3) spinner3.classList.remove('d-none');
            if (body3) body3.classList.add('d-none');

            // reset temporary image state
            imagesBlobs = {};
            imageCounter = 0;

            const formData = new FormData();
            formData.append('rrhhdotacion_id', rrhhdotacion_id);

            try {
                const resp = await fetch('{{ route('dotaciones.edit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await resp.json();

                if (!data.success) {
                    throw new Error(data.message || 'No se encontró la dotación');
                }

                const d = data.message;

                // set basic fields
                const rrIdInput = document.getElementById('rrhhdotacion_id');
                if (rrIdInput) rrIdInput.value = d.id ?? rrhhdotacion_id;
                if (document.getElementById('fecha3')) document.getElementById('fecha3').value = d.fecha ?? '';
                if (document.getElementById('responsable_entrega')) document.getElementById('responsable_entrega')
                    .value = d.responsable_entrega ?? '';

                // map detalles: conservar url/imagen si existen
                datos = (d.detalles || []).map(det => ({
                    detalle: det.detalle,
                    cantidad: det.cantidad,
                    estado: det.rrhhestadodotacion_id ?? det.rrhhestadodotacion_id,
                    estadoTexto: det.estado?.nombre ?? '',
                    image_index: null, // no hay imagen nueva todavía
                    url: det.url ?? null,
                    imagen: det.imagen ?? null
                }));

                // render tabla y mostrar modal
                renderizarTabla3();

                // toggle botones
                const btnEdit3 = document.getElementById('btnEdit3');
                const btnRegist3 = document.getElementById('btnRegist3');
                if (btnEdit3) btnEdit3.classList.remove('d-none');
                if (btnRegist3) btnRegist3.classList.add('d-none');

                $('#modalDotaciones').modal('show');
            } catch (err) {
                console.error('Error editar3:', err);
                Swal.fire('Error', err.message || 'Ocurrió un error al cargar la dotación.', 'error');
            } finally {
                if (spinner3) spinner3.classList.add('d-none');
                if (body3) body3.classList.remove('d-none');
            }
        }
        // Exponer globalmente
        window.editar3 = editar3;
    </script>
@endsection
