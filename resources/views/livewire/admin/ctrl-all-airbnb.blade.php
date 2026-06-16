<div>
    <div>
        <div class="card">
            <div class="card-header font-weight-bold bg-info text-white">LISTADO DE REGISTROS AIRBNB</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3 mb-2">
                        <label><small>Fecha Inicio</small></label>
                        <input type="date" class="form-control form-control-sm" wire:model='inicio'>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <label><small>Fecha Final</small></label>
                        <input type="date" class="form-control form-control-sm" wire:model='final'>
                    </div>
                    <div class="col-12 col-md-3 mb-2">
                        <label><small>Clientes</small></label>
                        <select class="form-control form-control-sm" wire:model='cliente_id'>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                            @endforeach
                        </select>
                      
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-9 mb-2">
                        <label><small>Busqueda: </small></label>
                        <input type="search" class="form-control form-control-sm" wire:model='search'
                            placeholder="Busqueda por nombre del Titular">
                    </div>
                    @if (!is_null($travelers))
                        <div class="col-12 col-md-3 mb-2">
                            <label style="color: white">
                                _sda
                            </label><br>
                            <button class="btn btn-success btn-sm btn-block" wire:click='exporExcel'>Exportar Excel <i class="fas fa-file-excel"></i></button>
                        </div>
                    @endif


                </div>
                <div class="mb-2">
                    <span class="badge badge-pill badge-danger">Vence en -1 hora</span>
                    <span class="badge badge-pill badge-warning">Vence en -1 día</span>
                    <span class="badge badge-pill badge-success">Reg. Activado</span>
                    <span class="badge badge-pill badge-secondary">Reg. No activado</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered " style="font-size: 12px">
                        <thead>
                            <tr class="bg-secondary text-white text-center">
                                <th>ID</th>
                                <th>TITULAR</th>
                                <th>INFO DPTO.</th>
                                <th>INGRESO</th>
                                <th>SALIDA</th>
                                <th>REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if (!is_null($travelers))
                                @forelse ($travelers as $item)
                                    <tr @if (verifAirbnb($item->departure_date) == 'success') @if ($item->status != 'CREADO')
                                    {{ verifAirbnb($item->departure_date) }}
                                    @else class="table-secondary" @endif
                                    @else class="table-{{ verifAirbnb($item->departure_date) }}" @endif
                                        >
                                        <td class="text-center align-middle">{{ $item->id }}</td>
                                        <td class="align-middle ">{{ $item->name }}</td>
                                        <td class="align-middle ">{{ $item->department_info }}</td>
                                        <td class="text-center align-middle ">{{ $item->arrival_date }}</td>
                                        <td class="text-center align-middle ">{{ $item->departure_date }}</td>
                                        <td class="text-center align-middle ">
                                            <a href="{{ route('downloadpdf', $item->id) }}"
                                                class="btn btn-danger btn-sm" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6"><i>No existen resultados</i></td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td class="text-center" colspan="6"><i>No existen resultados</i></td>
                                </tr>
                            @endif


                        </tbody>
                    </table>
                    <div style="float: right;">
                        @if (!is_null($travelers))
                            {{ $travelers->links() }}
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
