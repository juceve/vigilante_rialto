<div>
    @section('title')
        Dotaciones
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Dotaciones a Clientes</h4>
                <div class="">
                    <a href="{{ route('clientes.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i>
                        Volver</a>

                </div>
            </div>
        </div>
    @endsection
    <div class="card">
        <div class="card-header bg-info">
            Dotaciones - {{ $cliente->nombre }}
            <div class="float-right">
                @can('admin.clientes.dotaciones.create')
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDotacion">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-between align-items-center">

                <div class="col-12 col-md-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Estado: </small></span>
                        </div>
                        <select class="form-control" wire:model="filtro_estado">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-2 text-md-right">
                    <div class="input-group mb-3 justify-content-end">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Filas: </small></span>
                        </div>
                        <select class="form-control text-center" wire:model='perPage'>
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


            </div>
            <div class="table-responsive" wire:ignore.self>
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr class="table-info">
                            <th style="cursor:pointer" wire:click="sortBy('id')">
                                ID
                                @if ($sortField == 'id')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>

                            <th style="cursor:pointer" wire:click="sortBy('fecha')">
                                Fecha Generada
                                @if ($sortField == 'fecha')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th style="cursor:pointer" wire:click="sortBy('responsable_entrega')">
                                Responsable Entrega
                                @if ($sortField == 'responsable_entrega')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>

                            <th style="cursor:pointer" wire:click="sortBy('status')">
                                Estado
                                @if ($sortField == 'status')
                                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($resultados as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>{{ formatearFecha($item->fecha) }}
                                </td>
                                <td>{{ $item->responsable_entrega }}</td>
                                <td>
                                    @if ($item->status)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>


                                <td class="text-right">

                                    <button class="btn btn-sm btn-primary"
                                        wire:click="edit({{ $item->id }}, 'view')" title="Ver Info">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @can('admin.clientes.dotaciones.edit')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $item->id }}, 'edit')"
                                            title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    <button class="btn btn-sm btn-success" wire:click="actaPDF({{ $item->id }})"
                                        title="Acta de Dotación PDF" style="padding-left: 10px; padding-right: 10px;">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>
                                    @can('admin.clientes.dotaciones.destroy')
                                        <button class="btn btn-sm btn-danger" onclick="eliminar({{ $item->id }})"
                                            title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endcan

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No existen registros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $resultados->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDotacion" tabindex="-1" data-backdrop="static" data-keyboard="false"
        aria-labelledby="modalDotacionLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div
                    class="modal-header @switch($mode) @case('create') bg-primary @break @case('edit') bg-info @break @case('view') bg-warning @endswitch
                    ">
                    <h5 class="modal-title" id="modalDotacionLabel">
                        @switch($mode)
                            @case('create')
                                Registrar Dotación
                            @break

                            @case('edit')
                                Editar Dotación
                            @break

                            @case('view')
                                Detalles Dotación
                            @break
                        @endswitch

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click='resetAll'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-3 mb-3">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" wire:model.lazy="fecha"
                                @if ($mode != 'view') placeholder="Fecha" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="responsable_entrega">Responsable Entrega</label>
                            <input type="text" class="form-control" id="responsable_entrega"
                                wire:model.lazy="responsable_entrega"
                                @if ($mode != 'view') placeholder="Responsable Entrega" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label for="piso">Estado</label>
                            <select name="estado" id="estado" class="form-control" wire:model.lazy="status"
                                @if ($mode != 'view') placeholder="Estado" @endif
                                @if ($mode === 'view') disabled @endif>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    @if ($mode != 'view')
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 mb-3">
                                <label><small>Descripción</small></label>
                                <input type="text" id="detalleInput"
                                    class="form-control form-control-sm @error('detalle') is-invalid @enderror"
                                    wire:model="detalle" oninput="this.value = this.value.toUpperCase();"
                                    @if ($mode === 'view') disabled @endif />
                                @error('detalle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label><small>Cantidad</small></label>
                                <input type="number"
                                    class="form-control form-control-sm @error('cantidad') is-invalid @enderror"
                                    wire:model="cantidad" min="1"
                                    @if ($mode === 'view') disabled @endif />

                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label><small>Estado Articulo</small></label>
                                <select
                                    class="form-control form-control-sm @error('rrhhestadodotacion_id') is-invalid @enderror"
                                    wire:model="rrhhestadodotacion_id"
                                    @if ($mode === 'view') disabled @endif>
                                    <option value="">Seleccione</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01"><i
                                                class="fas fa-image"></i></span>
                                    </div>
                                    <div class="custom-file">
                                        <!-- Livewire file upload binding -->
                                        <input type="file" class="custom-file-input" id="imagenDot"
                                            aria-describedby="inputGroupFileAddon01" wire:model="imagen"
                                            accept="image/*">
                                        <label class="custom-file-label" for="imagenDot">Seleccione una imagen</label>
                                    </div>
                                </div>
                                <!-- Preview inmediato (antes de addDetalle) usando temporaryUrl() de Livewire -->
                                <div class="mt-2" id="imagenPreviewLocal">
                                    @if (isset($imagen) && $imagen)
                                        <img src="{{ $imagen->temporaryUrl() }}" class="img-fluid img-thumbnail"
                                            style="max-height:140px;">
                                    @endif
                                </div>
                            </div>
                            <!-- Cambiado: añadir utilidades d-flex align-items-end para que el botón quede alineado abajo -->
                            <div class="col-12 col-md-3 d-flex align-items-start mb-3">
                                {{-- <button type="button" class="btn btn-outline-primary w-100 align-self-start"
                                wire:click="addDetalle" @if ($mode === 'view') disabled @endif>
                                Agregar <i class="fas fa-arrow-down"></i>
                            </button> --}}
                                <div wire:loading.remove>
                                    <button type="button" class="btn btn-outline-primary w-100 align-self-start"
                                        wire:click="addDetalle" @if ($mode === 'view') disabled @endif>
                                        Agregar <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                                <div wire:loading>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <strong>Articulos agregados</strong>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover" style="font-size: 13px;">
                            <thead class="thead">
                                <tr class="table-info">
                                    <th class="align-middle">Nro.</th>
                                    <th class="align-middle">Descripción</th>
                                    <th class="align-middle">Imagen</th>
                                    <th class="align-middle text-center">Cantidad</th>
                                    <th class="align-middle">Estado Articulo</th>
                                    <th class="align-middle text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse ($detalles as $detalle)
                                    <tr>
                                        <td class="align-middle">{{ ++$i }}</td>
                                        <td class="align-middle">{{ $detalle['detalle'] }}</td>
                                        <td class="align-middle text-center">
                                            @if (!empty($detalle['url']))
                                                <a href="javascript:void(0)"
                                                    onclick="showPreview('{{ $detalle['url'] }}')"
                                                    title="Ver imagen">
                                                    <i class="fas fa-image text-info" style="font-size:18px;"></i>
                                                </a>
                                            @else
                                                &mdash;
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">{{ $detalle['cantidad'] }}</td>
                                        <td class="align-middle">{{ strtoupper($detalle['estado']) }}</td>
                                        <td class="align-middle text-center">
                                            @if ($mode != 'view')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="removeDetalle({{ $i - 1 }})"
                                                    title="Eliminar Artículo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay artículos agregados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @error('detalles')
                            <small class="text-danger">*Debe agregar al menos 1 item.</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click='resetAll'><i
                            class="fas fa-ban"></i> Cerrar</button>
                    @switch($mode)
                        @case('create')
                            <button type="button" class="btn btn-primary" wire:click="create">Registrar Dotación
                                <i class="fas fa-save"></i></button>
                        @break

                        @case('edit')
                            <button type="button" class="btn btn-info" wire:click="update">Guardar Cambios <i
                                    class="fas fa-save"></i></button>
                        @break

                        @default
                    @endswitch

                </div>
            </div>
        </div>
    </div>


    <!-- Imagen preview modal -->
    <div class="modal fade" id="modalPreviewImageDot" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-2">
                    <img id="modalPreviewImgDot" src="#" alt="Preview" class="img-fluid"
                        style="max-height:70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>

</div>
@section('js')
    <script>
        // Abrir / cerrar modal desde Livewire
        Livewire.on('openModal', () => {
            // limpiar cualquier preview previo del DOM para que el input file no muestre imagen por defecto
            try {
                const previewContainer = document.getElementById('imagenPreviewLocal');
                if (previewContainer) {
                    previewContainer.innerHTML = '';
                    previewContainer.style.display = 'none';
                }
            } catch (e) {
                console.debug('clear preview error', e);
            }
            $('#modalDotacion').modal('show');
        });
        Livewire.on('closeModal', () => {
            $('#modalDotacion').modal('hide');
        });

        // Confirmación de eliminación usando SweetAlert2
        function eliminar(id) {
            Swal.fire({
                title: 'Eliminar Dotación',
                text: '¿Estás seguro de que deseas eliminar la dotación?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'No, cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteDotacion', id);
                }
            });
        }

        // Abrir ventana para renderizar PDF desde Livewire
        Livewire.on('renderizarpdf', () => {
            var win = window.open("../../../pdf/acta-dotacion-cliente/", '_blank');
            if (win) win.focus();
        });

        // Mostrar modal con previsualización de imagen
        function showPreview(url) {
            if (!url) return;
            const img = document.getElementById('modalPreviewImgDot');
            if (!img) return;
            let finalUrl = url;
            try {
                const u = String(url).trim();
                const lower = u.toLowerCase();
                const isBlob = lower.startsWith('blob:');
                const isData = lower.startsWith('data:');
                const isAbsolute = lower.startsWith('http://') || lower.startsWith('https://') || u.startsWith('/');
                if (!isBlob && !isData && !isAbsolute) {
                    finalUrl = "{{ url('/') }}/" + u.replace(/^\/+/, '');
                }
            } catch (e) {
                finalUrl = url;
            }
            img.src = finalUrl;
            $('#modalPreviewImageDot').modal('show');
        }

        // Actualizar texto del label del custom-file al seleccionar archivo
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('imagenDot');
            if (!input) return;
            input.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                const label = document.querySelector('label[for="imagenDot"].custom-file-label');
                if (label) label.textContent = file ? file.name : 'Seleccione una imagen';
            });
        });

        // Opcional: limpiar preview img al cerrar modal preview
        $('#modalPreviewImageDot').on('hidden.bs.modal', function() {
            const img = document.getElementById('modalPreviewImgDot');
            if (img) img.src = '#';
        });

        // Escuchar evento disparado por Livewire para limpiar input file y preview en el cliente
        Livewire.on('imagen-cleared', () => {
            try {
                const input = document.getElementById('imagenDot');
                if (input) {
                    input.value = '';
                }
                const label = document.querySelector('label[for="imagenDot"].custom-file-label');
                if (label) {
                    label.textContent = 'Seleccione una imagen';
                }
                const preview = document.getElementById('imagenPreviewLocal');
                if (preview) {
                    preview.innerHTML = '';
                    preview.style.display = 'none';
                }
            } catch (e) {
                console.debug('imagen-cleared handler error', e);
            }
        });

        // Asegurar limpieza del input cuando se cierra el modal desde JS también
        Livewire.on('closeModal', () => {
            try {
                const input = document.getElementById('imagenDot');
                if (input) input.value = '';
                const label = document.querySelector('label[for="imagenDot"].custom-file-label');
                if (label) label.textContent = 'Seleccione una imagen';
                const preview = document.getElementById('imagenPreviewLocal');
                if (preview) {
                    preview.innerHTML = '';
                    preview.style.display = 'none';
                }
            } catch (e) {
                console.debug('closeModal clear error', e);
            }
        });
    </script>
@endsection
