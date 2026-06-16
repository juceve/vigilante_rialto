<div>

    <div class="card">
        <div class="card-header bg-light">
            LISTADO DE RESIDENCIAS REGISTRADAS
            <div class="float-right">

                <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalResidencia">
                    <i class="fa fa-plus"></i> Nuevo
                </button>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                            wire:model.debounce.500ms='search'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Estado: </small></span>
                        </div>
                        <select class="form-control" wire:model="filtro_estado">
                            <option value="">Todos</option>
                            <option value="CREADO">Creado</option>
                            <option value="VERIFICADO">Verificado</option>
                            <option value="CANCELADO">Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="input-group mb-3">
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
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th style="cursor:pointer" wire:click="sortBy('propietario_id')">
                                Propietario
                                @if ($sortField == 'propietario_id')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th style="cursor:pointer" wire:click="sortBy('numeropuerta')">
                                Nro. Puerta
                                @if ($sortField == 'numeropuerta')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th style="cursor:pointer" wire:click="sortBy('estado')">
                                Estado
                                @if ($sortField == 'estado')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($residencias as $residencia)
                            <tr>
                                <td>{{ $residencia->id }}</td>

                                <td>{{ $residencia->propietario_id ? $residencia->propietario->nombre : 'Sin Asignación' }}
                                </td>
                                <td>{{ $residencia->numeropuerta ? $residencia->numeropuerta : '-' }}</td>
                                <td>{{ $residencia->estado }}</td>

                                <td class="text-right">

                                    <button class="btn btn-sm btn-warning"
                                        wire:click="edit({{ $residencia->id }}, 'view')" title="Ver Info">
                                        <i class="fa fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-info"
                                        wire:click="edit({{ $residencia->id }}, 'edit')" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button class="btn btn-primary btn-sm" title="Asignar Propietario"
                                        data-toggle="modal" data-target="#modalAsignacionPropietario"
                                        wire:click="$set('residenciaSel', {{ $residencia->id }})">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $residencias->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalResidencia" tabindex="-1" data-backdrop="static" data-keyboard="false"
        aria-labelledby="modalResidenciaLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div
                    class="modal-header @switch($mode) @case('create') text-white  bg-primary @break @case('edit') text-white  bg-info @break @case('view') text-dark bg-warning @endswitch
                    ">
                    <h5 class="modal-title" id="modalResidenciaLabel">
                        @switch($mode)
                            @case('create')
                                Registrar Residencia
                            @break

                            @case('edit')
                                Editar Residencia
                            @break

                            @case('view')
                                Detalles Residencia
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
                            <label for="numeropuerta">Nro. Puerta</label>
                            <input type="text" class="form-control" id="numeropuerta" wire:model.defer="numeropuerta"
                                @if ($mode != 'view') placeholder="Nr. Puerta" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label for="piso">Piso</label>
                            <input type="text" class="form-control" id="piso" wire:model.defer="piso"
                                @if ($mode != 'view') placeholder="Piso" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label for="nrolote">Nro. Lote</label>
                            <input type="text" class="form-control" id="nrolote" wire:model.defer="nrolote"
                                @if ($mode != 'view') placeholder="Nro. Lote" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label for="manzano">Manzano</label>
                            <input type="text" class="form-control" id="manzano" wire:model.defer="manzano"
                                @if ($mode != 'view') placeholder="Manzano" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="calle">Calle</label>
                            <input type="text" class="form-control" id="calle" wire:model.defer="calle"
                                @if ($mode != 'view') placeholder="Calle" @endif
                                @if ($mode === 'view') disabled @endif>
                        </div>
                        @if ($mode === 'edit')
                            <div class="col-12 col-md-6 mb-3">
                                <label for="cedula_propietario">Estado</label>
                                <select class="form-control" id="estado" wire:model.defer="estado"
                                    @if ($mode === 'view') disabled @endif>
                                    <option value="CREADO">Creado</option>
                                    <option value="VERIFICADO">Verificado</option>
                                    <option value="CANCELADO">Cancelado</option>
                                </select>
                            </div>
                        @endif


                    </div>
                    @error('at_least_one')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click='resetAll'><i
                            class="fa fa-ban"></i> Cerrar</button>
                    @switch($mode)
                        @case('create')
                            <button type="button" class="btn btn-primary" wire:click="create">Registrar Residencia
                                <i class="fa fa-save"></i></button>
                        @break

                        @case('edit')
                            <button type="button" class="btn btn-info" wire:click="update">Guardar Cambios <i
                                    class="fa fa-save"></i></button>
                        @break

                        @default
                    @endswitch

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalAsignacionPropietario"
        tabindex="-1" aria-labelledby="modalAsignacionPropietarioLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAsignacionPropietarioLabel">Asignación de Propietario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='resetPropietario'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Cedula</span>
                                </div>
                                <input type="search" class="form-control" placeholder="Cedula Propietario"
                                    wire:model.lazy='searchCedula'>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button"
                                        wire:click='buscarPropietario'><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($propietario)
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $propietario->nombre }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Telefono</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $propietario->telefono }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Email</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $propietario->email }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Direccion</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $propietario->direccion }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Ciudad</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $propietario->ciudad }}"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='resetPropietario'><i class="fa fa-ban"></i> Cerrar</button>
                    @if ($propietario)
                        <button type="button" class="btn btn-primary" onclick="reasignar()">Reasignar Propietario <i class="fa fa-user-plus" aria-hidden="true"></i></button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

</div>
@section('js')
    <script>
        Livewire.on('openModal', () => {
            $('#modalResidencia').modal('show');
        });

        Livewire.on('closeModal', () => {
            $('#modalResidencia').modal('hide');
        });

        Livewire.on('cerrarReasignacion', () => {
            $('#modalAsignacionPropietario').modal('hide');
        });
    </script>
    <script>
        function reasignar() {
            swal.fire({
                title: 'Reasignar Propietario',
                text: '¿Estás seguro de que deseas reasignar al propietario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, reasignar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('reasignarPropietario');
                }
            });
        }
    </script>
@endsection
