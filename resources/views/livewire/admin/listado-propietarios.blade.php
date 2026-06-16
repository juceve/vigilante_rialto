<div>
    @section('title', 'Propietarios')

    <div class="content-header">
        <h4 class="m-0 text-dark">Propietarios</h4>
    </div>

    <div class="card">
        <div class="card-header bg-info">
            Listado de Propietarios Registrados
            <div class="float-right">
                @can('propietarios.create')
                    <button class="btn btn-info btn-sm" wire:click="create">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                @endcan

            </div>
        </div>

        <div class="card-body table-responsive">
            <div class="row mb-3">
                <!-- Buscador -->
                <div class="col-12 col-md-6 col-xl-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Ingrese su búsqueda..."
                            wire:model.debounce.500ms="search">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-xl-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <select class="form-control" wire:model.debounce.300ms="activoFiltro">
                            <option value="">-- Todos --</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>


                <!-- Selección de filas -->
                <div class="col-12 col-md-3 col-xl-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Filas:</small></span>
                        </div>
                        <select class="form-control text-center" wire:model="perPage">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Establecimiento</th>
                        <th>Teléfono</th>
                        <th>Cant. Vinculos</th>
                        <th>Activo</th>
                        <th width="185px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($propietarios as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->cliente ? $item->cliente->nombre : 'Sin asignar' }}</td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->residencias->count() }}</td>
                            <td>
                                <span class="badge {{ $item->activo ? 'badge-success' : 'badge-danger' }}">
                                    {{ $item->activo ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm" wire:click="show({{ $item->id }})"
                                    title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @can('propietarios.edit')
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $item->id }})"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" wire:click="addResidencia({{ $item->id }})"
                                        title="Agregar Residencia">
                                        <i class="fas fa-home"></i>
                                    </button>
                                @endcan
                                @can('propietarios.destroy')
                                    <button class="btn btn-danger btn-sm" onclick="eliminar({{ $item->id }})"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No existen registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="float-right mt-3">
                {{ $propietarios->links() }}
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="modalPropietario" tabindex="-1" aria-labelledby="modalPropietario" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div
                    class="modal-header
                @switch($modalMode)
                    @case('create')
                        bg-primary
                        @break
                    @case('edit')
                        bg-warning
                        @break
                    @default
                        bg-info
                @endswitch
                text-white">
                    <h5 class="modal-title">
                        @if ($modalMode == 'create')
                            Nuevo Propietario
                        @elseif($modalMode == 'edit')
                            Editar Propietario
                        @else
                            Ver Propietario
                        @endif
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">


                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" wire:model.defer="nombre"
                                @if ($modalMode === 'show') disabled @endif>
                            @error('nombre')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Cédula</label>
                            <input type="text" class="form-control" wire:model.defer="cedula"
                                @if ($modalMode === 'show') disabled @endif>
                            @error('cedula')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" wire:model.defer="telefono"
                                @if ($modalMode === 'show') disabled @endif>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model.defer="email"
                                @if ($modalMode === 'show') disabled @endif>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Dirección</label>
                            <input type="text" class="form-control" wire:model.defer="direccion"
                                @if ($modalMode === 'show') disabled @endif>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Ciudad</label>
                            <input type="text" class="form-control" wire:model.defer="ciudad"
                                @if ($modalMode === 'show') disabled @endif>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Activo</label>
                            <select class="form-control" wire:model.defer="activo"
                                @if ($modalMode === 'show') disabled @endif>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Establecimientos</label>
                            <select class="form-control" wire:model.defer="cliente_id"
                                @if ($modalMode === 'show') disabled @endif>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                        Cerrar</button>
                    @if ($modalMode !== 'show')
                        <button class="btn @if ($modalMode === 'create') btn-primary @else btn-warning @endif"
                            wire:click="save">
                            @if ($modalMode === 'create')
                                Registrar
                            @else
                                Guardar Cambios
                            @endif
                            <i class="fas fa-save"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalNuevaResidencia" tabindex="-1" data-backdrop="static" data-keyboard="false"
        aria-labelledby="modalNuevaResidenciaLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered custom-modal">
            <div class="modal-content">
                <div class="modal-header  bg-primary">
                    <h5 class="modal-title" id="modalNuevaResidenciaLabel">
                        Registrar Residencia - Propietario: {{ $propietario->nombre ?? '' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='resetAll'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Datos de la Nueva Residencia</label>
                    <div class="row mb-3">
                        <div class="col-12 col-md-1 mb-3">
                            <small for="numeropuerta">N° Casa</small>
                            <input type="text" class="form-control form-control-sm" id="numeropuerta"
                                wire:model.defer="numeropuerta">
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <small for="calle">Calle</small>
                            <input type="text" class="form-control form-control-sm" id="calle"
                                wire:model.defer="calle">
                        </div>

                        <div class="col-12 col-md-1 mb-3">
                            <small for="nrolote">N° Lote</small>
                            <input type="text" class="form-control form-control-sm" id="nrolote"
                                wire:model.defer="nrolote">
                        </div>
                        <div class="col-12 col-md-1 mb-3">
                            <small for="manzano">Manzano</small>
                            <input type="text" class="form-control form-control-sm" id="manzano"
                                wire:model.defer="manzano">
                        </div>
                        <div class="col-12 col-md-1 mb-3">
                            <small for="piso">Piso</small>
                            <input type="text" class="form-control form-control-sm" id="piso"
                                wire:model.defer="piso">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <small for="calle">Notas</small>
                            <input type="text" class="form-control form-control-sm" id="calle"
                                wire:model.defer="notas">
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="button" class="btn btn-block btn-sm btn-primary"
                                wire:click="storeResidencia">Registrar
                                Residencia <i class="fas fa-save"></i></button>
                        </div>
                    </div>

                    @if ($propietario)

                        <hr>
                        <h5>Residencias Vinculadas</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped" style="font-size: 13px;">
                                <thead>
                                    <tr class="text-center table-info">
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            N° Casa
                                        </th>
                                        <th>
                                            Calle
                                        </th>
                                        <th>
                                            N° Lote
                                        </th>
                                        <th>
                                            Manzano
                                        </th>
                                        <th>
                                            Piso
                                        </th>
                                        <th class="text-left">
                                            Notas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($propietario->residencias as $item)
                                        <tr class="text-center">
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->numeropuerta }}</td>
                                            <td>{{ $item->calle }}</td>
                                            <td>{{ $item->nrolote }}</td>
                                            <td>{{ $item->manzano }}</td>
                                            <td>{{ $item->piso }}</td>
                                            <td class="text-left">{{ $item->notas }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center"><i>No existen registros</i></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary col-4" data-dismiss="modal"
                        wire:click='resetAll'><i class="fas fa-ban"></i> Cerrar</button>


                </div>
            </div>
        </div>
    </div>

</div>
@section('js')
    <script>
        function eliminar(id) {
            swal.fire({
                title: 'Eliminar Registro',
                text: "¿Estás seguro? No podrás deshacer esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('delete', id);
                }
            });
        }
    </script>
    <script>
        Livewire.on('openModal', () => {
            $('#modalPropietario').modal('show');
        })
        Livewire.on('closeModal', () => {
            $('#modalPropietario').modal('hide');
        });

        Livewire.on('openModalResidencia', () => {
            $('#modalNuevaResidencia').modal('show');
        })

        Livewire.on('closeModalResidencia', () => {
            $('#modalNuevaResidencia').modal('hide');
        });
    </script>
@endsection
@section('css')
    <style>
        @media (max-width: 991.98px) {
            .custom-modal {
                max-width: 100% !important;
                /* ocupa todo el ancho */
                margin: 0;
                /* quita márgenes */
            }

            .custom-modal .modal-content {
                height: 100vh;
                /* opcional: ocupa alto completo */
                border-radius: 0;
                /* opcional: estilo más fullscreen */
            }
        }
    </style>
@endsection
