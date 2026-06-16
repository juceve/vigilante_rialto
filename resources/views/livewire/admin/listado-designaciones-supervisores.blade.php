<div>
    @section('title')
        Designaciones de Supervisores
    @endsection
    @section('content_header')
        <h4>Designaciones de Supervisores</h4>
    @endsection
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h5>LISTADO DE DESIGNACIONES</h5>
                <button class="btn btn-primary btn-sm" type="a" wire:click='create'>Nueva <i
                        class="fas fa-plus"></i></button>
                {{-- <a href="{{ route('designaciones.create') }}" class="btn btn-primary btn-sm" type="a" >Nueva <i
                        class="fas fa-plus"></i></a> --}}
            </div>

            <div class="row">
                <div class="col-12 col-md-10">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                            wire:model.debounce.500ms='busqueda'>
                    </div>
                </div>
                {{-- <div class="col-12 col-md-2">
                    <button class="btn btn-success btn-block" wire:click='exporExcel'><i class="fas fa-file-excel"></i>
                        Exportar</button>
                </div> --}}
                <div class="col-12 col-md-2">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Filas: </small></span>
                        </div>
                        <select class="form-control text-center" wire:model='filas'>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead table-info">
                        <tr>
                            <th>No</th>

                            <th>Empleado</th>
                            <th>Fecha Inicio</th>


                            <th>Estado</th>
                            {{-- <th>Oficina</th> --}}
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($resultados as $item)
                            <tr>
                                <td>{{ ++$i }}</td>

                                <td>{{ $item->empleado->nombres . ' ' . $item->empleado->apellidos }}</td>
                                <td>{{ $item->fechaInicio }}</td>
                                <td>
                                    @if ($item->estado)
                                        <span class="badge badge-pill badge-primary">Activo</span>
                                    @else
                                        <span class="badge badge-pill badge-secondary">Inactivo</span>
                                    @endif


                                </td>
                                <td align="right">
                                    <button class="btn btn-sm btn-warning" title="Editar"
                                        wire:click="editar({{ $item->id }})"><i class="fas fa-edit"></i></button>
                                    @if (!$item->estado)
                                        <button class="btn btn-sm btn-danger" title="Eliminar"
                                        onclick="confirmarEliminacion({{ $item->id }})"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $resultados->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalCreate" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalCreateLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div
                    class="modal-header
                @if (!$editMode) bg-primary
                @else
                    bg-warning @endif
                ">
                    <h5 class="modal-title" id="modalCreateLabel">
                        @if ($editMode)
                            Editar
                        @else
                            Nueva
                        @endif
                        Designación
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='resetForm'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Inicio</span>
                                </div>
                                <input type="date" class="form-control  @error('fechaInicio') is-invalid @enderror"
                                    wire:model='fechaInicio'>
                            </div>
                            @error('fechaInicio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                         <div class="col-12 col-md-6 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fin</span>
                                    </div>
                                    <input type="date" class="form-control  @error('fechaFin') is-invalid @enderror"
                                        wire:model='fechaFin'>
                                </div>
                                @error('fechaFin')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Supervisor</span>
                                </div>
                                <select class="form-control  @error('supervisor_id') is-invalid @enderror"
                                    wire:model='supervisor_id'>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($supervisores as $supervisor)
                                        <option value="{{ $supervisor->id }}">
                                            {{ $supervisor->nombres . ' ' . $supervisor->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('supervisor_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12 col-md-10 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Clientes</span>
                                </div>
                                <select class="form-control  @error('arrayClientes') is-invalid @enderror"
                                    wire:model='cliente_id'>
                                    <option value="">-- Seleccione Cliente --</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">
                                            {{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <button class="btn btn-outline-success btn-block" type="button"
                                wire:click='agregarCliente'>
                                Agregar <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            @error('arrayClientes')
                                <small class="text-danger">Debe agregar al menos 1 Cliente</small>
                            @enderror
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-sm" style="font-size: 13px;">
                                    <thead>
                                        <tr class="table-info text-center">
                                            <th>ID</th>
                                            <th>CLIENTE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($arrayClientes as $client)
                                            <tr>
                                                <td class="text-center">{{ $client['id'] }}</td>
                                                <td>{{ $client['nombre'] }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger btn-sm" type="button"
                                                        wire:click='quitarCliente({{ $loop->index }})'>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="3">No hay clientes agregados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-clock"></i> &nbsp; Turno</span>
                                </div>
                                <select class="form-control" wire:model='turnoguardia_id'>
                                    <option value="">-- Seleccione un Turno --</option>
                                    @foreach ($turnoguardias as $turnog)
                                        <option value="{{ $turnog->id }}">{{ $turnog->nombre }} -
                                            [{{ $turnog->horainicio }} -
                                            {{ $turnog->horafin }}]</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('turnoguardia_id')
                                <small class="text-danger">El campo Turno es obligatorio</small>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list"></i> &nbsp; Obs.:</span>
                                </div>
                                <input type="text" class="form-control" wire:model='observaciones'
                                    placeholder="Observaciones">
                            </div>
                        </div>
                        <div class="col-12v mb-3">
                            <label>Días laborales:</label> <button class="btn btn-sm btn-outline-info"
                                wire:click="seleccionarTodosDias">Sel. Todo <i
                                    class="fas fa-check-double"></i></button>
                            <div class="row">
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="domingo"
                                            wire:model="domingo">
                                        <label class="form-check-label" for="domingo">Domingo</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lunes"
                                            wire:model="lunes">
                                        <label class="form-check-label" for="lunes">Lunes</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="martes"
                                            wire:model="martes">
                                        <label class="form-check-label" for="martes">Martes</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="miercoles"
                                            wire:model="miercoles">
                                        <label class="form-check-label" for="miercoles">Miércoles</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jueves"
                                            wire:model="jueves">
                                        <label class="form-check-label" for="jueves">Jueves</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="viernes"
                                            wire:model="viernes">
                                        <label class="form-check-label" for="viernes">Viernes</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sabado"
                                            wire:model="sabado">
                                        <label class="form-check-label" for="sabado">Sábado</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @if ($editMode)
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="inputGroupSelect01">Activo</label>
                                    </div>
                                    <select class="custom-select" id="inputGroupSelect01" wire:model="estado">

                                        <option value="1">SI</option>
                                        <option value="0">NO</option>

                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click='resetForm'><i
                            class="fas fa-ban"></i> Cerrar</button>
                    @if ($editMode)
                        <button type="button" class="btn btn-warning" wire:click='update'>Guardar <i
                                class="fas fa-save"></i></button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click='registrar'>Registrar <i
                                class="fas fa-save"></i></button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        Livewire.on('openModalCreate', () => {
            $('#modalCreate').modal('show');
        });

        Livewire.on('closeModalCreate', () => {
            $('#modalCreate').modal('hide');
        });
    </script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminar', id);

                }
            });
        }
    </script>
@endsection
