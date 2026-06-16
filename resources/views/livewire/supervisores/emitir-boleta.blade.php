<div style="margin-top: 95px">

    @section('title')
        Boletas
    @endsection

    <div class="alert alert-secondary" role="alert" style="font-size: 13px;">
        <div class="row">
            <div class="col-2">
                <a href="{{ route('supervisores.listadoboletas', $inspeccionActiva->id) }}"
                    class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10">
                <div class="text-secondary">
                    <h4>
                        <strong>EMISIÓN DE BOLETAS</strong>
                    </h4>
                    <i class="fas fa-user-secret"></i>
                    {{ $inspeccionActiva->designacionsupervisor->empleado->nombres . ' ' . $inspeccionActiva->designacionsupervisor->empleado->apellidos }}
                </div>
            </div>
        </div>

    </div>
    <div class="container">

        <div class="card">
            <div class="card-header bg-secondary text-white">
                DATOS
            </div>
            <div class="card-body">


                <div class="form-group mb-2">
                    <label><strong>Selección de empleados</strong></label>
                    <div class="mb-2 position-relative" style="max-width: 100%;">



                        <!-- Input buscador con debounce y spinner -->
                        <div class="position-relative">
                            <input type="search" class="form-control pe-4" placeholder="Buscar empleados..."
                                wire:model.debounce.800ms="searchEmpleado" autocomplete="off">

                            <!-- Spinner Livewire -->
                            <div wire:loading wire:target="searchEmpleado"
                                class="position-absolute top-50 end-0 translate-middle-y me-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lista filtrada desplegable -->
                        @if(strlen($searchEmpleado) > 0)
                            <ul class="list-group position-absolute w-100 z-index-1"
                                style="max-height: 200px; overflow-y: auto;">
                                @foreach ($empleados as $empleado)
                                    @if (stripos($empleado['nombre'], $searchEmpleado) !== false && !in_array($empleado['id'], $empleadosSeleccionados))
                                        <li class="list-group-item list-group-item-action p-1" style="cursor: pointer;"
                                            wire:click="seleccionar({{ $empleado['id'] }})">
                                            {{ $empleado['nombre'] }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                        <!-- Badges de seleccionados -->
                        <div class="d-flex flex-wrap gap-1 mb-1 mt-1">
                            @foreach ($empleadosSeleccionados as $id)
                                @php
                                    $empleado = collect($empleados)->first(fn($e) => $e['id'] == $id);
                                @endphp
                                @if($empleado)
                                    <span class="badge bg-primary d-flex align-items-center gap-1" style="font-size: 10px;">
                                        {{ $empleado['nombre'] }}
                                        <button type="button" class="btn-close btn-close-white btn-sm"
                                            wire:click="deseleccionar({{ $id }})"></button>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label><strong>Tipo Boleta</strong></label>
                    <select class="form-select" wire:model="selTipoBoletaId">
                        <option value="">Seleccione un tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($selTipoBoleta && count($empleadosSeleccionados))
                    <div class="form-group mb-3">
                        <label><strong>Descuento Bs.</strong></label>
                        <span class="form-control">{{ $selTipoBoleta->monto_descuento }}</span>
                    </div>
                    <div class="form-group mb-3">
                        <label><strong>Detalles</strong></label>
                        <textarea class="form-control" rows="2" placeholder="Descripción corta del motivo" wire:model.lazy="detalles"></textarea>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-danger py-3" onclick="emitirBoleta()">
                           <strong> EMITIR BOLETA  <i class="fas fa-receipt"></i></strong>
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    function emitirBoleta(){
        Swal.fire({
                title: "EMITIR BOLETA",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",
                cancelButtonColor: "#2c3e50",
                confirmButtonText: "Si, emitir boleta",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('emitirBoleta');
                }
            });
    }
</script>

@endsection
