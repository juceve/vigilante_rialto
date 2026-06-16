<div>
    @section('title')
        Pases
    @endsection

    @section('header-title')
        Pases de Ingreso
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white" style="font-size: 18px">
            <span>Listado de Pases</span>

        </div>
        <div class="card-body">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Buscar por nombre"
                    wire:model.debounce.600ms='search'>
            </div>

            <div class="table-responsive">
               <table class="table table-bordered hover">
    <thead class="table-info">
        <tr class="text-center">
            <th>ID</th>
            <th class="text-left">Nombre</th>
            <th class="text-left">Tipo Pase</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Uso Único</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($paseingresos as $item)
            <tr class="text-center">
                <td class="align-middle">{{ $item->id }}</td>
                <td class="align-middle text-left">{{ $item->nombre }}</td>
                <td class="align-middle text-left">{{ $item->motivo->nombre }}</td>

                <td class="align-middle">{{ $item->fecha_inicio }}</td>
                <td class="align-middle">{{ $item->fecha_fin }}</td>
                <td class="align-middle">{{ $item->usounico ? 'SI' : 'NO' }}</td>
                <td class="align-middle">
                    @php
                        $fechaInicio = (new DateTime($item->fecha_inicio))->format('Y-m-d');
                        $fechaFin = (new DateTime($item->fecha_fin))->format('Y-m-d');
                        $hoy = (new DateTime())->format('Y-m-d');
                    @endphp

                    @if (!$item->estado)
                        <span class="badge badge-pill badge-secondary">Cancelado</span>
                    @else
                        @if ($hoy < $fechaInicio)
                            <span class="badge badge-pill badge-info">Pendiente</span>
                        @elseif ($hoy >= $fechaInicio && $hoy <= $fechaFin)
                            <span class="badge badge-pill badge-success">Vigente</span>
                        @else
                            <span class="badge badge-pill badge-warning">Expirado</span>
                        @endif
                    @endif
                </td>

                <td class="text-right" style="width: 120px;min-width: 110px;">
                    <button class="btn btn-sm btn-info" title="Mas Detalles"
                        wire:click="verDetalles({{ $item->id }})" wire:loading.attr="disabled"
                        wire:target='verDetalles({{ $item->id }})'>
                        <i class="fas fa-eye"></i>
                    </button>
                    @php
                        $encryptedId = encriptar($item->id);
                    @endphp
                    <a href="{{ $item->estado ? route('resumenpase', $encryptedId) : 'javascript:void(0);' }}"
                        target="_blank"
                        class="btn btn-sm btn-primary @if (!$item->estado) disabled @endif"
                        title="Ver Credencial">
                        <i class="far fa-address-card"></i>
                    </a>
                    <button class="btn btn-sm btn-warning" title="Deshabilitar"
                        @if (!$item->estado) disabled @endif
                        onclick="deshabilitar({{ $item->id }})">
                        <i class="fas fa-power-off"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center"><i>No existen registros.</i></td>
            </tr>
        @endforelse
    </tbody>
</table>

            </div>
            <div class="float-right">
                {{ $paseingresos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalDetalleLabel">Detalles del Pase de Ingreso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($paseingreso)
                        <h5>Datos del Pase de Ingreso</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nombre</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $paseingreso->nombre }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cedula</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $paseingreso->cedula }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Inicio</span>
                                    </div>
                                    <input readonly type="text" class="form-control"
                                        value="{{ $paseingreso->fecha_inicio }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Fin</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $paseingreso->fecha_fin }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Tipo Pase</span>
                                    </div>
                                    <input readonly type="text" class="form-control"
                                        value="{{ $paseingreso->motivo->nombre }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Uso único</span>
                                    </div>
                                    <input readonly type="text" class="form-control"
                                        value="{{ $paseingreso->usounico ? 'SI' : 'NO' }}">
                                </div>
                            </div>

                        </div>
                    @endif
                    <hr>
                    @if ($residencia)
                        <h5>Datos de la Residencia</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nro. Puerta</span>
                                    </div>
                                    <input readonly type="text" class="form-control"
                                        value="{{ $residencia->numeropuerta }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Piso</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $residencia->piso }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Calle</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $residencia->calle }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nro. Lote</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $residencia->nrolote }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Manzano</span>
                                    </div>
                                    <input readonly type="text" class="form-control" value="{{ $residencia->manzano }}">
                                </div>
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
    </script>
    <script>
        $('#modalDetalle, #modalNuevo').on('hide.bs.modal', function() {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });
    </script>
    <script>
        function deshabilitar(id) {
            Swal.fire({
                title: "Deshabilitar Pase de Ingreso",
                text: "Esta operación no puede deshacerse. Esta seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, deshabilitar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deshabilitar', id);
                }
            });
        }
    </script>
@endsection
@section('css')
    <style>
        .table {
            white-space: nowrap;
            /* Evita que el contenido se divida */
        }
    </style>
@endsection
