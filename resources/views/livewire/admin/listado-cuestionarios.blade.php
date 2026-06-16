<div>
    @section('title')
        Checklists
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <h4>Checklists</h4>
        </div>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info">
                <span class="card-title">Checklist Elaborados</span>
                <div class="float-right">
                    <button class="btn btn-info btn-sm" wire:click="create">Nuevo <i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <select class="form-control" wire:model="selCliente">
                                <option value="">Todos los Clientes</option>
                                  @foreach ($clientes as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                  @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="filas" style="font-size: 12px">Mostrar</label>
                            </div>
                            <select class="custom-select" id="filas" wire:model='filas'>
                                <option value="5">5 filas</option>
                                <option value="10">10 filas</option>
                                <option value="25">25 filas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-info">
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Cliente</th>
                                <th>Checklist</th>
                                <th class="text-center">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($resultados as $cuestionario)
                                <tr>
                                    <td class=" text-center align-middle">{{ $cuestionario->id }}</td>
                                    <td class="align-middle">{{ $cuestionario->cliente->nombre }}</td>
                                    <td class="align-middle">{{ $cuestionario->titulo }}</td>
                                    <td class=" text-center align-middle">
                                        @if ($cuestionario->activo)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-right align-middle">
                                        <button class="btn btn-warning btn-sm" title="Editar"
                                            wire:click="edit({{ $cuestionario->id }})"><i
                                                class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">No se encontraron cuestionarios.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="float-right">
                    {{ $resultados->links() }}
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalCuestionario" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="modalCuestionarioLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div
                            class="modal-header 
                        @switch($modalMode)
                            @case('Nuevo')
                                bg-primary
                                @break
                            @case('Editar')
                                bg-warning
                                @break
                            @default
                                bg-info
                        @endswitch
                        text-white">
                            <h5 class="modal-title" id="modalCuestionarioLabel">
                                {{ $modalMode }} Checklist
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                wire:click="resetAll">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="cliente">Cliente</span>
                                        </div>
                                        <select class="form-control @error('cliente_id') is-invalid @enderror"
                                            id="cliente" wire:model.defer="cliente_id">
                                            <option value="">Seleccione un cliente</option>
                                            @if ($modalMode==='Nuevo')
                                                 <option value="0">Todos los Clientes</option>
                                            @endif                                           
                                            @foreach ($clientes as $id => $nombre)
                                                <option value="{{ $id }}">{{ $nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="titulo">Titulo</span>
                                        </div>
                                        <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                            placeholder="Titulo" aria-label="Username" aria-describedby="titulo"
                                            wire:model.defer="titulo">
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="descripcion">Descripcion</span>
                                        </div>
                                        <input type="text"
                                            class="form-control @error('descripcion') is-invalid @enderror"
                                            placeholder="Descripcion" aria-label="Username"
                                            aria-describedby="descripcion" wire:model.defer="descripcion">
                                    </div>
                                </div>
                                @if ($modalMode === 'Editar')
                                    <div class="col-12 col-md-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="estado">Estado</span>
                                            </div>
                                            <select class="form-control" id="activo" wire:model.defer="activo">
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <hr>
                            <h5 class="text-center">Agregar Preguntas</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text 
                                            @if ($modePregunta==='Editar')
                                                bg-warning
                                            @endif
                                            " id="pregunta">Pregunta</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Pregunta"
                                            aria-label="Username" aria-describedby="pregunta"
                                            wire:model.defer="pregunta">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="input-group input-group-sm mb-3"
                                        title="En caso de incumplimiento de la pregunta se generará una boleta con su respectivo descuento.">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text  
                                            @if ($modePregunta==='Editar')
                                                bg-warning
                                            @endif
                                            " id="tipoboleta_id">Genera Boleta</span>
                                        </div>
                                        <select class="form-control" id="activo" wire:model.defer="tipoboleta_id">
                                            <option value="">Sin Boleta</option>
                                            @foreach ($tipoboletas as $id => $nombre)
                                                <option value="{{ $id }}">{{ $nombre }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text  
                                            @if ($modePregunta==='Editar')
                                                bg-warning
                                            @endif
                                            " id="requerida">Requerido</span>
                                        </div>
                                        <select class="form-control" id="requerida" wire:model.defer="requerida">

                                            <option value="1">Si</option>
                                            <option value="0">No</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    @if ($modePregunta === 'Nuevo')
                                        <button class="btn btn-sm btn-outline-success btn-block"
                                            wire:click="agregarPregunta">Agregar
                                            <i class="fas fa-arrow-down"></i></button>
                                    @else
                                        <button class="btn btn-sm btn-outline-warning btn-block"
                                            wire:click="actualizarPregunta">Agregar Cambios
                                            <i class="fas fa-arrow-down"></i></button>
                                    @endif

                                </div>
                            </div>
                            <span>Preguntas agregadas</span>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped" style="font-size: 12px;">
                                    <thead class="table-info">
                                        <tr class="text-center">

                                            <th>Nro</th>
                                            <th>Pregunta</th>
                                            <th>Boleta</th>
                                            <th>Requerido</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($preguntas as $index => $preguntaItem)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle">{{ $preguntaItem['pregunta'] }}</td>
                                                <td class="align-middle">
                                                    @if ($preguntaItem['tipoboleta_id'])
                                                        {{ $preguntaItem['boleta'] }}
                                                    @else
                                                        Sin Boleta
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if ($preguntaItem['requerida'])
                                                        Si
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                                <td class="align-middle text-right">
                                                    @if ($modalMode === 'Editar')
                                                        <button class="btn btn-sm btn-outline-warning"
                                                            wire:click="editarPregunta({{ $index }})"
                                                            title="Editar Pregunta"><i
                                                                class="fas fa-edit"></i></button>
                                                    @endif
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        wire:click="eliminarPregunta({{ $index }})"
                                                        title="Quitar Pregunta"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">No hay preguntas agregadas.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                wire:click="resetAll"><i class="fas fa-ban"></i> Cerrar</button>
                            @switch($modalMode)
                                @case('Nuevo')
                                    <button type="button" class="btn btn-primary" onclick='store()'>Registrar <i
                                            class="fas fa-save"></i></button>
                                @break

                                @case('Editar')
                                    <button type="button" class="btn btn-warning" onclick='update()'>Guardar Cambios <i
                                            class="fas fa-save"></i></button>
                                @break

                                @default
                            @endswitch

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@section('js')
    <script>
        document.addEventListener('livewire:load', function() {
            @this.on('openModalCuestionario', () => {
                $('#modalCuestionario').modal('show');
            });

            @this.on('closeModalCuestionario', () => {
                $('#modalCuestionario').modal('hide');
            });
        });
    </script>

    <script>
        function store() {
            Swal.fire({
                title: 'Registrar Checklist',
                text: "¿Estás seguro de realizar la operación?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.store();
                }
            });
        }

        function update() {
            Swal.fire({
                title: 'Actualizar Checklist',
                text: "¿Estás seguro de realizar la operación?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.update();
                }
            });
        }
    </script>
@endsection
