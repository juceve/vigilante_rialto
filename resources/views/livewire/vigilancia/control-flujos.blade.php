<div class="px-3" style="margin-top: 7rem">
    @section('title')
        Control de Flujos
    @endsection

    <div class="row mb-1 ">
        <div class="col-1">
            <a href="javascript:history.back()" class="text-silver"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
        </div>
        <div class="col-10">
            <h4 class="text-secondary text-center">FLUJO DE INGRESOS Y SALIDAS</h4>
        </div>
        <div class="col-1"></div>
    </div>
    <div class="card">
        <div class="card-header font-weight-bold bg-info text-white">
            Registros de uso de Pases
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-4 mb-2">
                    <label><small>Fecha Inicio</small></label>
                    <input type="date" class="form-control form-control-sm" wire:model='inicio'>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <label><small>Fecha Final</small></label>
                    <input type="date" class="form-control form-control-sm" wire:model='final'>
                </div>

            </div>
            <div class="row">
                <div class="col-12 mb-2">
                    <label><small>Busqueda: </small></label>
                    <input type="search" class="form-control form-control-sm" wire:model.debounce.500ms='search'
                        placeholder="Busqueda por nombre">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 13px">
                    <thead>
                        <tr class="bg-secondary text-white" style="white-space: nowrap;">
                            <th class="text-center">NRO</th>
                            <th>FECHA Y HORA</th>
                            <th>NOMBRE</th>
                            <th class="text-center">MOTIVOD</th>
                            <th class="text-center">FLUJO</th>
                            <th class="text-center">DATOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($flujopases as $item)
                            <tr class="align-middle" style="white-space: nowrap;">
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $item->fecha . ' ' . $item->hora }}</td>
                                <td>{{ $item->pase_nombre }}</td>
                                <td class="text-center">{{ $item->motivo_nombre }}</td>
                                <td class="text-center">
                                    @if ($item->tipo === 'INGRESO')
                                        <span class="badge rounded-pill bg-success">{{ $item->tipo }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">{{ $item->tipo }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" title="Datos Residencia"
                                        wire:click='detalleResidencia({{ $item->residencia_id }})'>
                                        <i class="fas fa-home-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                    </tbody>
                </table>
                <div style="float: right;">
                    {{-- {{ $travelers->links() }} --}}
                </div>

            </div>
        </div>
    </div>


    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="modalResidencia" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitleId">
                        Datos de la Residencia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table-responsive">
                    @if ($residencia)
                        <table class="table table-striped table-bordered" style="font-size: 13px;">
                            <tbody>
                                <tr>
                                    <td colspan="2"><strong>Propietario:</strong>
                                        {{ $residencia->propietario->nombre }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telefono:</strong> {{ $residencia->propietario->telefono }}</td>
                                    <td><strong>Nro. Puerta:</strong> {{ $residencia->numeropuerta }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Calle:</strong> {{ $residencia->calle }}</td>
                                    <td><strong>Manzano:</strong> {{ $residencia->manzano }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nro Lote:</strong> {{ $residencia->nrolote }}</td>
                                    <td><strong>Piso:</strong> {{ $residencia->piso }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-ban"></i> CERRAR
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush
@section('js')
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalResidencia'))

        Livewire.on('abrirModal', () => {
            myModal.show();
        });

        Livewire.on('cerrarModal', () => {
            myModal.hide();
        });
    </script>
@endsection
