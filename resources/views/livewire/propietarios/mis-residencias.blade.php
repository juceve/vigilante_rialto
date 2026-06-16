<div>
    @section('title')
    Mis Residencias
    @endsection

    @section('header-title')
    Mis Residencias
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white" style="font-size: 18px">
            <span>Listado de Residencias Registradas</span>

        </div>
        <div class="card-body">
            <div class="table-responsive rounded-sm">
                <table class="table table-bordered table-striped ">
                    <thead class="table-info">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Nro. Puerta</th>
                            <th>Calle</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($residencias as $item)
                        <tr>
                            <td style="vertical-align: middle">{{ $item->id }}</td>
                            <td style="vertical-align: middle">{{ $item->cliente->nombre??'Sin definir' }}</td>
                            <td style="vertical-align: middle">{{ $item->numeropuerta }}</td>
                            <td style="vertical-align: middle">{{ $item->calle }}</td>
                            <td style="vertical-align: middle">
                                @switch($item->estado)
                                @case('VERIFICADO')
                                <span class="badge badge-success">VERIFICADO</span>
                                @break

                                @case('CREADO')
                                <span class="badge badge-warning">CREADO</span>
                                @break

                                @case('CANCELADO')
                                <span class="badge badge-secondary">CANCELADO</span>
                                @break
                                @endswitch
                            </td>
                            <td class="text-right" style="min-width: 120px; width: 125px; vertical-align: middle;">
                                <button class="btn btn-sm btn-info" title="Mas Detalles"
                                    wire:click="verDetalles({{ $item->id }})"><i class="fas fa-eye"></i></button>

                                <button class="btn btn-sm btn-primary" title="Solicitar Pase de Ingreso"
                                    wire:click='nuevoPase({{ $item->id }})' @if ($item->estado != 'VERIFICADO') disabled
                                    @endif>
                                    <i class="fas fa-ticket-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->

    <!-- Modal -->
    <div class="modal fade" id="modalNuevo" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Nuevo Pase de Ingreso <br>
                        <span class="badge badge-primary">{{ $residencia->cliente->nombre ?? '' }}</span>

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="resetearCampos">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" wire:model.defer="nombre" required
                                placeholder="Nombre del Invitado">
                            @error('nombre')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" wire:model.defer="cedula" required
                                placeholder="Cedula del Invitado">
                            @error('cedula')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tipoPase" class="form-label">Tipo de Pase</label>
                            <select class="form-control" id="tipoPase" wire:model.defer="motivo_id" required>
                                <option value="">Seleccione</option>
                                @foreach ($motivos as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                            @error('motivo_id')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="usounico" class="form-label">Uso Único</label>
                            <select class="form-control" id="usounico" wire:model.defer="usounico" required>
                                <option value="">Seleccione</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            @error('usounico')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" required
                                wire:model.defer="fecha_inicio">
                            @error('fecha_inicio')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" required
                                wire:model.defer="fecha_fin">
                            @error('fecha_fin')
                            <div class="invalid-feedback">
                                <small> {{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="detalles" class="form-label">Detalles</label>
                            <textarea class="form-control" name="detalles" id="detalles" rows="2"
                                placeholder="Detalles de la visita" wire:model.defer="detalles"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetearCampos">
                        <i class="fas fa-ban"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="registrarPase">Registrar <i
                            class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="modalDetalle" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetalle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalle">Detalles de la Residencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($residencia)
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label>ID</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->id }}">

                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Establecimiento</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->cliente->nombre }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Nro. Puerta</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->numeropuerta }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Piso</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->piso }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Calle</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->calle }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Nro. Lote</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->nrolote }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Manzano</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->manzano }}">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label>Estado</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->estado }}">
                        </div>
                        <div class="col-12">
                            <label>Notas</label>
                            <input type="text" class="form-control" readonly value="{{ $residencia->notas }}">
                        </div>

                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    Livewire.on('openModal', () => {
            $('#modalDetalle').modal('show');
        });

        Livewire.on('closeModal', () => {
            $('#modalDetalle').modal('hide');
        });

        Livewire.on('openModalNuevo', () => {
            $('#modalNuevo').modal('show');
        });

        Livewire.on('closeModalNuevo', () => {
            $('#modalNuevo').modal('hide');
        });

        Livewire.on('resumenpase', data => {
            var win = window.open("resumen/" + data, '_blank');
            win.focus();
        })
</script>
<script>
    $('#modalDetalle, #modalNuevo').on('hide.bs.modal', function() {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });
</script>
@endsection