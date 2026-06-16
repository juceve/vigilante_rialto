<div>
    <div class="card">
        <div class="card-header bg-info text-white" style="font-size: 18px">
            <span>Flujo de Ingresos y Salidas</span>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Buscar por nombre del visitante"
                            wire:model.debounce.600ms='search'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Del:</span>
                        </div>
                        <input type="date" class="form-control" wire:model='inicio'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Al:</span>
                        </div>
                        <input type="date" class="form-control" wire:model='final'>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="flujo"><i class="fas fa-exchange-alt"></i></label>
                        </div>
                        <select class="custom-select" id="flujo" wire:model='flujo'>
                            <option value=''>Todos</option>                            
                            <option value="INGRESO">INGRESOS</option>
                            <option value="SALIDA">SALIDAS</option>
                            
                        </select>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered hover">

                    <thead>
                        <tr class="bg-secondary text-white" style="white-space: nowrap;">
                            <th class="text-center">NRO</th>
                            <th>ESTABLECIMIENTO</th>
                            <th>VISITANTE</th>
                            <th>FLUJO - FECHA - HORA</th>
                            <th class="text-center">MOTIVO</th>
                            <th class="text-center">NRO. PUERTA</th>
                            <th class="text-center">NRO. LOTE</th>
                            <th class="text-center">PISO</th>
                            <th class="text-center">CALLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($flujopases as $item)
                            <tr class="align-middle" style="white-space: nowrap;">
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $item->cliente_nombre }}</td>
                                <td>{{ $item->pase_nombre }}</td>
                                <td>
                                    @if ($item->tipo === 'INGRESO')
                                        <span class="badge badge-success"
                                            style="font-size: 12px;">{{ $item->tipo . ': ' }} <i class="fas fa-calendar"></i> {{ $item->fecha }}
                                            <i class="fas fa-clock"></i> {{ $item->hora }}</span>
                                    @else
                                        <span class="badge badge-warning"
                                            style="font-size: 12px;">{{ $item->tipo . ': ' }} <i class="fas fa-calendar"></i> {{ $item->fecha }}
                                            <i class="fas fa-clock"></i> {{ $item->hora }}</span>
                                    @endif
                                </td>

                                <td class="text-center">{{ $item->motivo_nombre }}</td>
                                <td class="text-center">{{ $item->numeropuerta }}</td>
                                <td class="text-center">{{ $item->nrolote }}</td>
                                <td class="text-center">{{ $item->piso }}</td>
                                <td class="text-center">{{ $item->calle }}</td>


                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center"><i>No existen registros.</i></td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>


        </div>

    </div>
</div>
