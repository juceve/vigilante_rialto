<div class="container-fluid px-3 py-2" style="margin-top: 110px;">
    @section('title', 'Vacaciones')
    <!-- Header / back -->
    <div class="d-flex align-items-center mb-3">
        <a class="btn btn-link p-0 me-2" href="{{route('vigilancia.profile')}}" aria-label="Volver">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h4 class="mb-0">Permisos y Vacaciones</h4>

    </div>

     <!-- Profile card -->
    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <div class="flex-shrink-0 me-3">
                    @php
                    $avatar = $empleado->avatar ?? null;
                    $placeholder = 'https://ui-avatars.com/api/?name=' . urlencode($empleado->nombres.'
                    '.$empleado->apellidos ?? 'Empleado') . '&background=0D6EFD&color=fff&rounded=true&size=128';
                    $avatarUrl = $empleado->imgperfil ? asset('storage/'.$empleado->imgperfil) : $placeholder;
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle"
                        style="width:84px;height:84px;object-fit:cover;">
                </div>

                <!-- Info -->
                <div class="flex-grow-1">
                    <h5 class="mb-0 text-primary">{{ $empleado->nombres ?? 'Nombre' }} {{ $empleado->apellidos ??
                        'Apellidos' }}</h5>
                    <small class="text-blue d-block mb-2"><strong>{{ $empleado->area->nombre ?? 'Cargo / Puesto'
                            }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-grid">
            <h5 class="card-title text-center text-muted">Solicitudes registradas</h5>
            <div class="table-responsive" wire:ignore>
                <table class="table table-striped" style="font-size: 11px">
                    <thead class="bg-success text-white">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Solicitado</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permisos as $permiso)
                        <tr class="text-center">
                            <td class="align-middle">{{$permiso->id}}</td>
                            <td class="align-middle">{{$permiso->created_at}}</td>
                            <td class="align-middle">{{$permiso->rrhhtipopermiso->nombre}}</td>
                            <td class="align-middle">
                                @if ($permiso->activo==1)
                                @switch($permiso->status)
                                @case('SOLICITADO')
                                <span class="badge bg-warning text-dark">Solicitado</span>
                                @break
                                @case('APROBADO')
                                <span class="badge bg-success text-white">Aprobado</span>
                                @break
                                @case('RECHAZADO')
                                <span class="badge bg-danger text-white">Rechazado</span>
                                @break
                                @endswitch
                                @else
                                <span class="badge bg-secondary text-white">Anulado</span>
                                @endif
                            </td>
                            <td class="text-end align-middle">
                                @if ($permiso->activo)
                                <button class="btn btn-warning" title="Ver Detalles"
                                    wire:click="view({{ $permiso->id }})"><i class="fas fa-eye"></i></button>
                                @else
                                <button class="btn btn-secondary" title="Ver Detalles" disabled><i
                                        class="fas fa-eye"></i></button>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No existen resultados.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $permisos->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <button class="btn btn-primary mt-2 py-3" wire:click='nuevaSolicitud'>Nueva Solicitud <i
                    class="fas fa-folder-plus"></i></button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDetalles" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="staticBackdropLabel">Datos de la Solicitud </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="font-size: 12px;">
                            @if ($permisoSel)
                            <tbody>
                                <tr>
                                    <td><strong>Fecha Solicitud</strong></td>
                                    <td>{{formatearFecha(substr($this->permisoSel->created_at, 0, 10))}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo Permiso</strong></td>
                                    <td>{{ $this->permisoSel->rrhhtipopermiso->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha Inicio</strong></td>
                                    <td>{{ $this->permisoSel->fecha_inicio ?
                                        formatearFecha($this->permisoSel->fecha_inicio) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha Fin</strong></td>
                                    <td>{{ $this->permisoSel->fecha_fin ? formatearFecha($this->permisoSel->fecha_fin) :
                                        'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Motivo</strong></td>
                                    <td>{{ $this->permisoSel->motivo ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado</strong></td>
                                    <td>
                                        @if ($permisoSel->activo==1)
                                        @switch($permisoSel->status)
                                        @case('SOLICITADO')
                                        <span class="badge bg-warning text-dark">Solicitado</span> &nbsp;
                                        <button class="btn btn-sm btn-danger" style="font-size: 10px;"
                                            onclick="anular({{ $this->permisoSel->id }})">
                                            <i class="fas fa-trash"></i> Anular Solicitud
                                        </button>
                                        @break
                                        @case('APROBADO')
                                        <span class="badge bg-success text-white">Aprobado</span>
                                        @break
                                        @case('RECHAZADO')
                                        <span class="badge bg-danger text-white">Rechazado</span>
                                        @break
                                        @endswitch
                                        @else
                                        <span class="badge bg-secondary text-white">Anulado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Documento <br> Adjunto</strong></td>
                                    <td>
                                        @php
                                        $doc = $this->permisoSel->documento_adjunto ?? null;
                                        @endphp
                                        @if ($doc)
                                        @php
                                        // resolver URL: si ya es absoluta usarla, si no asumir storage
                                        $isAbsolute = preg_match('/^https?:\\/\\//i', $doc);
                                        $docUrl = $isAbsolute ? $doc : asset('storage/' . ltrim($doc, '/'));
                                        $filename = basename($doc);
                                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                                        @endphp
                                        @if ($isImage)
                                        <!-- abrir en popup -->
                                        <a href="javascript:void(0)"
                                            onclick="showDocPreview('{{ $docUrl }}','{{ $filename }}')"
                                            title="Ver imagen">
                                            <img src="{{ $docUrl }}" alt="Adjunto" class="img-thumbnail"
                                                style="max-height:80px;">
                                        </a>
                                        <div class="mt-1">
                                            {{-- <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="showDocPreview('{{ $docUrl }}','{{ $filename }}')">Ver</button>
                                            --}}
                                            {{-- <a href="{{ $docUrl }}" download="{{ $filename }}"
                                                class="btn btn-sm btn-outline-primary">Descargar</a> --}}
                                        </div>
                                        @else
                                        <a href="{{ $docUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-download"></i> Descargar {{ $filename }}
                                        </a>
                                        @endif
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-ban"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalSolicitud" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="staticBackdropLabel">Nueva Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <small class="text-muted"><strong>Tipo de Permiso</strong></small>
                            <select id="tipo" class="form-select" wire:model.lazy="rrhhtipopermiso_id">
                                <option value="">-- Seleccione --</option>
                                @foreach ($rrhhtipopermisos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <small class="text-muted"><strong>Fecha Inicio</strong></small>
                            <input type="date" id="fecha_inicio" class="form-control" wire:model.lazy="fecha_inicio">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <small class="text-muted"><strong>Fecha Fin</strong></small>
                            <input type="date" id="fecha_fin" class="form-control" wire:model.lazy="fecha_fin">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <small class="text-muted"><strong>Motivo</strong></small>
                            <textarea type="text" id="motivo" class="form-control" rows="2" wire:model.lazy="motivo"
                                placeholder="Descripción corta"></textarea>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <small class="text-muted"><strong>Adjuntar <i class="fas fa-paperclip"></i></strong></small>
                            <!-- permitir cámara en móviles (capture) y múltiples tipos de archivo -->
                            <input type="file" id="file" class="form-control" wire:model="file"
                                accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.doc,.docx,.xls,.xlsx"
                                capture="environment"
                                onchange="handleFileInputChange(this)">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="file,store"><i class="fas fa-ban"></i>
                        Cerrar</button>
                    <!-- Al hacer click, marcamos que se inicia el registro y Livewire ejecuta store -->
                    {{-- <button type="button" class="btn btn-primary" onclick="startRegistering()" wire:click="store">
                        Registrar <i class="fas fa-save"></i>
                    </button> --}}

                    <button type="button" class="btn btn-primary" onclick="startRegistering()" wire:click="store"
                        wire:loading.attr="disabled" wire:target="file,store">

                        <span wire:loading.remove wire:target="file,store">
                            Registrar <i class="fas fa-save"></i>
                        </span>

                        <span wire:loading wire:target="file,store">
                            Procesando... <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Añadir modal de previsualización de documento (imagen) -->
    <div class="modal fade" id="modalDocPreview" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-2 text-center">
                    <img id="modalDocPreviewImg" src="#" alt="Documento" class="img-fluid" style="max-height:70vh;">
                </div>
                <div class="modal-footer">
                    <a id="modalDocDownload" href="#" download class="btn btn-sm btn-primary"><i
                            class="fas fa-download"></i> Descargar</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>
@section('js')
<script>
    Livewire.on('openModalDetalles', () => {
       var myModal = new bootstrap.Modal(document.getElementById('modalDetalles'), {
           keyboard: false
       });
       myModal.show();
   });
   
   Livewire.on('openModalSolicitud', () => {
       var myModal = new bootstrap.Modal(document.getElementById('modalSolicitud'), {
           keyboard: false
       });
       myModal.show();
   });

    Livewire.on('closeModalDetalles', () => {
       var myModal = bootstrap.Modal.getInstance(document.getElementById('modalDetalles'));
       if (myModal) {
           myModal.hide();
       }
   });

   Livewire.on('closeModalSolicitud', () => {
       var myModal = bootstrap.Modal.getInstance(document.getElementById('modalSolicitud'));
       if (myModal) {
           myModal.hide();
       }
   });

  // Bandera global para saber si la petición actual corresponde al registro
  window.__isRegistering = false;

  function startRegistering() {
      // marcar que vamos a registrar; Livewire.hook detectará el envio y mostrará Swal
      window.__isRegistering = true;
      // Nota: la petición Livewire seguirá el wire:click="store"
  }

  // Mostrar SweetAlert2 cuando Livewire envía un mensaje (si corresponde a registro)
  if (window.Livewire) {
      Livewire.hook('message.sent', (message, component) => {
          if (window.__isRegistering) {
              // mostrar modal de espera
              Swal.fire({
                  title: 'REGISTRANDO...',
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });
          }
      });

      // Cuando se procesa la respuesta, cerrar el Swal inmediatamente
      Livewire.hook('message.processed', (message, component) => {
          if (window.__isRegistering) {
              try { Swal.close(); } catch (e) {}
              window.__isRegistering = false;
          }
      });
  }

   function anular(id) {
       Swal.fire({
           title: '¿Está seguro de anular la solicitud?',
           text: "Esta acción no se puede deshacer.",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#d33',
           cancelButtonColor: '#3085d6',
           confirmButtonText: 'Sí, anular',
           cancelButtonText: 'Cancelar'
       }).then((result) => {
           if (result.isConfirmed) {
               Livewire.emit('anular', id);
           }
       });
    }

    // Mostrar imagen/documento en popup modal (si es imagen)
    function showDocPreview(url, filename) {
        try {
            const img = document.getElementById('modalDocPreviewImg');
            const down = document.getElementById('modalDocDownload');
            if (!img || !down) {
                // fallback: abrir en nueva pestaña
                window.open(url, '_blank');
                return;
            }
            img.src = url;
            down.href = url;
            down.setAttribute('download', filename || '');
            const myModal = new bootstrap.Modal(document.getElementById('modalDocPreview'));
            myModal.show();
        } catch (e) {
            console.debug('showDocPreview error', e);
            window.open(url, '_blank');
        }
    }

    // Limpiar src al cerrar el modal para liberar memoria / evitar flash
    document.getElementById('modalDocPreview')?.addEventListener('hidden.bs.modal', function () {
        const img = document.getElementById('modalDocPreviewImg');
        if (img) img.src = '#';
        const down = document.getElementById('modalDocDownload');
        if (down) down.href = '#';
    });

    /**
     * Comprimir una imagen en cliente hasta que quede por debajo de maxKB (aprox).
     * Devuelve un File comprimido.
     */
    async function compressImageFile(file, maxKB = 100) { // <- objetivo cambiado a 100 KB
        if (!file || !/^image\//i.test(file.type)) return file;
        return await new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onerror = reject;
            reader.onload = () => {
                const img = new Image();
                img.onerror = reject;
                img.onload = () => {
                    // Escalar si es necesario para evitar canvas enorme
                    let width = img.width;
                    let height = img.height;
                    const maxDim = Math.max(width, height);
                    if (maxDim > 1920) {
                        const scale = 1920 / maxDim;
                        width = Math.round(width * scale);
                        height = Math.round(height * scale);
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Intentar reducir calidad progresivamente hasta alcanzar tamaño
                    let quality = 0.92;
                    const mime = file.type === 'image/png' ? 'image/jpeg' : file.type; // for PNG prefer JPEG for compression
                    (function tryCompress() {
                        canvas.toBlob((blob) => {
                            if (!blob) {
                                return reject(new Error('No se pudo generar blob'));
                            }
                            const sizeKB = blob.size / 1024;
                            if (sizeKB <= maxKB || quality <= 0.05) {
                                const ext = mime.includes('png') ? 'png' : 'jpg';
                                const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, '') + '.' + ext, { type: mime, lastModified: Date.now() });
                                return resolve(newFile);
                            }
                            quality -= 0.07;
                            tryCompress();
                        }, mime, quality);
                    })();
                };
                img.src = reader.result;
            };
            reader.readAsDataURL(file);
        });
    }

    /**
     * Handler del input file: si es imagen > 100KB la comprime antes de reemplazar el input.files
     * y dispara el evento change para que Livewire actualice el modelo.
     */
    async function handleFileInputChange(input) {
        try {
            if (!input || !input.files || input.files.length === 0) return;
            const file = input.files[0];
            if (!file) return;
            if (/^image\//i.test(file.type) && file.size > 100 * 1024) { // <- umbral cambiado a 100 KB
                const compressed = await compressImageFile(file, 100); // <- pasar 100 KB
                // Reemplazar el archivo del input para que Livewire suba el comprimido
                const dt = new DataTransfer();
                dt.items.add(compressed);
                input.files = dt.files;
                // Forzar evento change para que Livewire detecte el nuevo archivo
                input.dispatchEvent(new Event('change', { bubbles: true }));
                // Notificar al usuario brevemente
                try {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Comprimiendo imagen...',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } catch (e) {
                    console.debug('Swal no disponible para notificación', e);
                }
            }
        } catch (err) {
            console.error('Error al comprimir imagen:', err);
            // En caso de error no bloquear el flujo: dejar el archivo original
        }
    }

</script>
@endsection