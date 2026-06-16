<div>
    @section('title', 'Caja Chica')

    @section('content_header')
        <div class="container-fluid">
            <div class="content">
                <h4>CAJAS DE SUPERVISORES
                </h4>
            </div>
        </div>
    @endsection
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h5>LISTADO DE CAJAS</h5>
                <button class="btn btn-primary btn-sm" type="button" wire:click="create">Nueva <i
                        class="fas fa-plus"></i></button>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Supervisor</span>
                        </div>
                        <select class="form-control" wire:model="empleado_id">
                            <option value="">Todos los supervisores</option>
                            @foreach ($supervisores as $supervisor1)
                                <option value="{{ $supervisor1->id }}">
                                    {{ $supervisor1->nombres . ' ' . $supervisor1->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Gestión</span>
                        </div>
                        <select class="form-control" wire:model="gestion">
                            <option value="">Todas</option>
                            @foreach ($gestiones as $gestion)
                                <option value="{{ $gestion }}">{{ $gestion }}</option>
                            @endforeach
                        </select>
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
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr class="table-info text-center">
                            <th>ID</th>
                            <th class="text-left">SUPERVISOR</th>
                            <th>GESTIÓN</th>
                            <th class="text-right">SALDO Bs.</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cajachicas as $cajachica)
                            <tr class="text-center">
                                <td class="align-middle">{{ $cajachica->id }}</td>

                                <td class="align-middle text-left">
                                    {{ $cajachica->empleado->nombres . ' ' . $cajachica->empleado->apellidos }}</td>
                                <td class="align-middle">{{ $cajachica->gestion }}</td>
                                <td class="align-middle text-right"> {{ number_format($cajachica->saldo_actual, 2) }}
                                </td>
                                <td class="align-middle">
                                    @if ($cajachica->estado === 'ACTIVA')
                                        <span class="badge badge-pill badge-success">{{ $cajachica->estado }}</span>
                                    @else
                                        <span class="badge badge-pill badge-secondary">{{ $cajachica->estado }}</span>
                                    @endif

                                </td>
                                <td class="align-middle text-right">
                                    <button class="btn btn-sm btn-info" title="Ver Info"
                                        wire:click="show({{ $cajachica->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Editar"
                                        wire:click="edit({{ $cajachica->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-success" title="Depositos"
                                        @if ($cajachica->estado === 'CERRADA') disabled readonly @endif
                                        wire:click="depositar({{ $cajachica->id }})">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" title="Ver movimientos"
                                        wire:click="verMovimientos({{ $cajachica->id }})">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $cajachicas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap 4.6 -->
    <div class="modal fade" id="modalForm" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modalFormLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div
                    class="modal-header
                @if ($modalMode === 'Nuevo') bg-primary @endif
                        @if ($modalMode === 'Editar') bg-warning @endif
                        @if ($modalMode === 'Ver') bg-info @endif
                ">
                    <h5 class="modal-title" id="modalFormLabel">
                        @if ($modalMode === 'Nuevo')
                            Nueva
                        @endif
                        @if ($modalMode === 'Editar')
                            Editar
                        @endif
                        @if ($modalMode === 'Ver')
                            Info
                        @endif
                        Caja Chica
                    </h5>
                    <button type="button" class="close" wire:click="cerrarModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Supervisor</span>
                                </div>
                                <select class="form-control  "
                                    @if ($modalMode === 'Ver') readonly disabled @endif wire:model="selEmpleado">
                                    <option value="">Seleccione un Supervisor</option>
                                    @foreach ($supervisores as $supervisor)
                                        <option value="{{ $supervisor->id }}">
                                            {{ $supervisor->nombres . ' ' . $supervisor->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Gestión</span>
                                </div>
                                <select class="form-control  "
                                    @if ($modalMode === 'Ver') readonly disabled @endif wire:model="selGestion">
                                    <option value="">Todas</option>
                                    @foreach ($gestiones as $gestion1)
                                        <option value="{{ $gestion1 }}">{{ $gestion1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Inicio</span>
                                </div>
                                <input type="date" class="form-control  "
                                    @if ($modalMode === 'Ver') readonly @endif wire:model="selInicio">
                            </div>
                        </div>
                        @if ($modalMode === 'Editar')
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Final</span>
                                    </div>
                                    <input type="date" class="form-control  "
                                        @if ($modalMode === 'Ver') readonly @endif wire:model="selFinal">
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Observaciones</span>
                                </div>
                                <input type="text" class="form-control  "
                                    @if ($modalMode === 'Ver') readonly @endif
                                    wire:model.lazy="selObservaciones">
                            </div>
                        </div>
                        @if ($modalMode != 'Nuevo')
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Estado</span>
                                    </div>
                                    <select class="form-control  "
                                        @if ($modalMode === 'Ver') readonly disabled @endif
                                        wire:model="selEstado">
                                        <option value="ACTIVA">ACTIVA</option>
                                        <option value="CERRADA">CERRADA</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal"><i
                            class="fas fa-ban"></i> Cerrar</button>
                    @if ($modalMode != 'Ver')
                        @if ($modalMode === 'Nuevo')
                            <button class="btn btn-primary" onclick="storeCaja()">Registrar <i
                                    class="fas fa-save"></i></button>
                        @else
                            <button class="btn btn-warning" onclick="updateCaja()">Actualizar <i
                                    class="fas fa-save"></i></button>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- DEPOSITOS --}}
    <!-- Modal -->
    <div class="modal fade" id="modalDepositos" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalDepositosLabel" aria-hidden="true" wire:ignore.self>

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalDepositosLabel">
                        Depósitos y Reposiciones
                    </h5>
                    <button type="button" class="close" wire:click="cancelarDeposito">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if ($selCaja)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><strong>Supervisor</strong></td>
                                        <td colspan="3">
                                            {{ $selCaja->empleado->nombres }} {{ $selCaja->empleado->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gestión</strong></td>
                                        <td>{{ $selCaja->gestion }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Saldo actual</strong></td>
                                        <td class="text-success font-weight-bold">
                                            Bs {{ number_format($selCaja->saldo_actual, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-4 col-form-label">
                                Adicionar saldo
                            </label>
                            <div class="col-8">
                                <input type="number" step="0.01"
                                    class="form-control form-control-sm @error('montoDeposito') is-invalid @enderror"
                                    wire:model.defer="montoDeposito">

                                @error('montoDeposito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-4 col-form-label">
                                Concepto
                            </label>
                            <div class="col-8">
                                <input type="text"
                                    class="form-control form-control-sm @error('conceptoDeposito') is-invalid @enderror"
                                    wire:model.defer="conceptoDeposito">

                                @error('conceptoDeposito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelarDeposito">
                        <i class="fas fa-ban"></i> Cerrar
                    </button>

                    <button type="button" class="btn btn-success" onclick="registrarDeposito()">
                        Registrar depósito <i class="fas fa-file-invoice-dollar"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Movimientos -->
    <div class="modal fade" id="modalCajaMov" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalMovimientosLabel">
                        Movimientos de Caja Chica
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" wire:click="cerrarMovimientos">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($selCaja)
                        {{-- Información general --}}
                        <label><strong>INFORMACIÓN GENERAL</strong></label>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="border rounded p-2 h-100">
                                    <strong>Supervisor</strong><br>
                                    {{ $selCaja->empleado->nombres }} {{ $selCaja->empleado->apellidos }}<br>
                                    <strong>Estado:</strong>
                                    <span
                                        class="badge {{ $selCaja->estado === 'ACTIVA' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $selCaja->estado }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="border rounded p-2 h-100 text-center">
                                    <strong>Saldo actual de la caja</strong>
                                    <div class="h4 text-success mb-0">
                                        Bs {{ number_format($selCaja->saldo_actual, 2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="border rounded p-2 h-100">
                                    <strong>Resumen del período</strong>
                                    <div>Ingresos: <span class="text-success">
                                            Bs {{ number_format($totalIngresos, 2) }}
                                        </span></div>
                                    <div>Egresos: <span class="text-danger">
                                            Bs {{ number_format($totalEgresos, 2) }}
                                        </span></div>
                                    <hr class="my-1">
                                    <div><strong>Saldo período:</strong>
                                        Bs {{ number_format($saldoPeriodo, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <label><strong>MOVIMIENTOS DE LA CAJA</strong></label>
                        {{-- Filtros --}}
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label><strong>Gestión</strong></label>
                                <select class="form-control form-control-sm" wire:model="filtroGestion">
                                    <option value="{{ $selCaja->gestion }}">
                                        {{ $selCaja->gestion }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Mes</strong></label>
                                <select class="form-control form-control-sm" wire:model="filtroMes">
                                    <option value="">Todos</option>
                                    @foreach ([
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ] as $num => $mes)
                                        <option value="{{ $num }}">{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Tabla de movimientos --}}
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Concepto</th>
                                        <th>Categoría</th>
                                        <th class="text-right">Monto (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($movimientos as $mov)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge
                                                {{ $mov->tipo === 'INGRESO' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $mov->tipo }}
                                                </span>
                                            </td>
                                            <td>{{ $mov->concepto }}</td>
                                            <td>{{ $mov->categoria ?? '-' }}</td>
                                            <td class="text-right">
                                                {{ $mov->tipo === 'INGRESO' ? '' : '-' }}{{ number_format($mov->monto, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No existen movimientos para el filtro seleccionado
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-right"><strong>Saldo Bs.</strong></td>
                                        <td class="text-right">
                                            <strong>{{ number_format($cajachica->saldo_actual, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='cerrarMovimientos'>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>




</div>
@section('js')
    <script>
        Livewire.on('openModal', () => {
            $('#modalForm').modal('show');
        })
        Livewire.on('closeModal', () => {
            $('#modalForm').modal('hide')
        })
        Livewire.on('openDepositos', () => {
            $('#modalDepositos').modal('show');
        })
        Livewire.on('closeDepositos', () => {
            $('#modalDepositos').modal('hide')
        })
        Livewire.on('abrirModalMovimientos', () => {
            $('#modalCajaMov').modal('show');
        })
        Livewire.on('cerrarModalMovimientos', () => {
            $('#modalCajaMov').modal('hide')
        })
    </script>
    <script>
        function storeCaja() {
            Swal.fire({
                title: "REGISTRAR CAJA CHICA",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, registrar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('store');
                }
            });
        }

        function updateCaja() {
            Swal.fire({
                title: "ACTUALIZAR CAJA CHICA",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, actualizar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('update');
                }
            });
        }

        function registrarDeposito() {
            Swal.fire({
                title: "REGISTRAR DEPOSITO EN CAJA",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, registrar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('registrarDeposito');
                }
            });
        }
    </script>

@endsection
