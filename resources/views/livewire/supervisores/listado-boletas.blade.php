<div style="margin-top: 95px">

    @section('title')
        Boletas
    @endsection

    <div class="alert alert-secondary" role="alert" style="font-size: 12px;">
        <div class="row">
            <div class="col-2">
                <a href="{{ route('supervisores.panel', $inspeccionActiva->id) }}"
                    class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10">
                <span class="text-secondary">
                    <i class="fas fa-building"></i> <strong> {{ $inspeccionActiva->cliente->nombre }}</strong>
                </span>
            </div>
        </div>

    </div>

    <div class="container d-grid">
        <a href="{{ route('supervisores.emitirboleta',$inspeccionActiva->id) }}" class="btn btn-primary mb-3">EMITIR BOLETA <i
                class="fas fa-receipt"></i></a>
        <div class="card">
            <div class="card-header bg-secondary text-white text-center">
                BOLETAS EMITIDAS
            </div>
            <div class="card-body">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="basic-addon1">Del</span>
                    <input type="date" class="form-control" wire:model="fechaInicio">
                    &nbsp;
                    <span class="input-group-text" id="basic-addon2">Al</span>
                    <input type="date" class="form-control" wire:model="fechaFin">
                </div>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                    <input type="search" class="form-control" wire:model.debounce.1000ms="search"
                        placeholder="Buscar por empleado">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 11px;">
                        <thead>
                            <tr class="table-info text-center">
                                <th>ID</th>
                                <th>FECHA</th>
                                <th>EMPLEADO/BOLETA</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($resultados as $item)
                                <tr>
                                    <td class="align-middle text-center">{{ $item->id }}</td>
                                    <td class="align-middle text-center">{{ $item->fechahora }}</td>
                                    <td class="align-middle">
                                        {{ $item->empleado->nombres . ' ' . $item->empleado->apellidos }} <br>
                                        <span class="text-primary"
                                            style="font-size: 9px;">{{$item->tipoboleta->nombre}}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-sm btn-primary" wire:click="view({{ $item->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No existen resultados.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                @if ($resultados)
                    <div class="d-flex justify-content-end">
                        {{ $resultados->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalFull" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-center">BOLETA NRO.:
                        @if ($selBoleta)
                            {{ cerosIzq2($selBoleta->id) }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        @if ($selBoleta)
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="text-end"><strong>Empleado:</strong></td>
                                        <td>
                                            {{ $selBoleta->empleado->nombres . ' ' . $selBoleta->empleado->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Emitido:</strong></td>
                                        <td>
                                            {{ $selBoleta->fechahora }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Lugar:</strong></td>
                                        <td>
                                            {{ $selBoleta->cliente->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Detalles:</strong></td>
                                        <td>
                                            {{ $selBoleta->detalles }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Tipo:</strong></td>
                                        <td>
                                            {{ $selBoleta->tipoboleta->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Descuento:</strong></td>
                                        <td>
                                            Bs. {{ $selBoleta->descuento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Supervisor:</strong></td>
                                        <td>
                                            {{ $selBoleta->supervisor->nombres . ' ' . $selBoleta->supervisor->apellidos}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</div>
@section('js')
    <script>


        Livewire.on('openModal', () => {
            console.log('ingreso a open');

            const modalEl = document.getElementById('modalFull');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('modalFull');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });


    </script>
@endsection
