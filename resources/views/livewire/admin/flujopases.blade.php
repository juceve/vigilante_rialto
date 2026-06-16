<div>
    @section('title')
    Registro de Visitas con Pases
    @endsection

    @section('content_header')
    <div class="container-fluid">
        <h4>Registro de Visitas con Pases</h4>
    </div>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        {!! Form::select('cliente_id', $clientes, null,
                        ['class'=>'form-control','placeholder'=>'Seleccione un cliente','wire:model'=>'cliente_id']) !!}
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model='inicio' aria-label="inicio">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model='final' aria-label="final">
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Filas</span>
                            </div>
                            <select class="form-control" wire:model="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">Todos</option>
                            </select>

                        </div>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="vertical-align: middle">
                        <thead>
                            <tr class="table-info">
                                <th>ID</th>
                                <th>VISITANTE</th>
                                <th>CLIENTE</th>
                                <th class="text-center">DOC. IDENTIDAD</th>
                                <th>RESIDENTE</th>
                                <th class="text-center">INGRESO</th>
                                <th class="text-center">SALIDA</th>
                                <th>ESTADO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rows) && $rows->count())
                                @foreach($rows as $item)
                                <tr>
                                    {{-- ahora el ID es el id del registro flujopase (ingreso) --}}
                                    <td>{{ $item->flujopase_id }}</td>
                                    <td>{{ $item->visitante }}</td>
                                    <td>{{ $item->cliente ?? '' }}</td>
                                    <td class="text-center">{{ $item->cedula }}</td>
                                    <td>{{ $item->residente }}</td>
                                    <td class="text-center">
                                        {{ $item->fecha_ingreso ? \Carbon\Carbon::parse($item->fecha_ingreso . ' ' . ($item->hora_ingreso ?? '00:00:00'))->format('d/m/Y H:i') : '--' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item->fecha_salida ? \Carbon\Carbon::parse($item->fecha_salida . ' ' . ($item->hora_salida ?? '00:00:00'))->format('d/m/Y H:i') : '--' }}
                                    </td>
                                    <td>
                                        @if($item->estado == 'En proceso')
                                            <span class="badge badge-pill badge-success">En proceso</span>
                                        @else
                                            <span class="badge badge-pill badge-secondary">Finalizado</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- pasar flujopase_id a verInfo --}}
                                        <button class="btn btn-info btn-sm" title="Ver info"
                                            wire:click="verInfo({{ $item->flujopase_id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="8">No se encontraron resultados.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        @if (!is_null($rows))
                        {{ $rows->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal detalle visita (diseño refactorizado: profesional y estructurado) -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white align-items-start">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title mb-1" id="modalInfoLabel">DETALLES DEL REGISTRO</h5>
                    </div>
                    <div class="ml-auto text-right">
                        @if(isset($visita['estado']))
                        @if($visita['estado'])
                        <span class="badge badge-pill badge-light text-success px-3 py-2">En proceso</span>
                        @else
                        <span class="badge badge-pill badge-light text-secondary px-3 py-2">Finalizado</span>
                        @endif
                        @endif
                        <button type="button" class="close text-white ml-2" data-dismiss="modal" aria-label="Close"
                            onclick="$('#modalInfo').modal('hide')">
                            <span aria-hidden="true" style="font-size:1.4rem;">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body">
                    @if(!empty($visita))
                    <div class="row">
                        <!-- LEFT: Datos principales (Visitante + Cliente + Residente) -->
                        <div class="col-lg-7">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3 text-muted">Información principal <br> <strong>Registro de Visita: {{ str_pad($visita['flujopase_id'], 5, '0', STR_PAD_LEFT) }}</strong> </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="small text-muted">Visitante</div>
                                            <div class="font-weight-medium">{{ strtoupper($visita['visitante']) ?? '--'
                                                }}</div>
                                            <div class="small text-muted">Cédula</div>
                                            <div class="font-weight-medium">{{ $visita['docidentidad'] }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="small text-muted">Cliente</div>
                                            <div class="font-weight-medium">{{ $visita['cliente'] ?? '--' }}</div>
                                        </div>

                                        <!-- Nueva fila: Cédula -->
                                        @if(!empty($visita['docidentidad']))
                                        <div class="col-md-6 mb-2">

                                        </div>
                                        @endif



                                        {{-- Residencia detallada --}}
                                        @if(!empty($visita['residencia_calle']) ||
                                        !empty($visita['residencia_numeropuerta']) || !empty($visita['residencia_piso'])
                                        || !empty($visita['residencia_nrolote']) ||
                                        !empty($visita['residencia_manzano']))
                                        <div class="col-12 mt-2">
                                            <hr class="my-2">
                                            @if(!empty($visita['residente']))

                                            <div class="small text-muted">Residente</div>
                                            <div class="font-weight-medium">{{ strtoupper($visita['residente']) }}</div>

                                            @endif
                                            <div class="small text-muted mb-1">Datos de la residencia</div>
                                            <div class="row">
                                                @if(!empty($visita['residencia_calle']))
                                                <div class="col-sm-6 mb-1"><strong>Calle:</strong> {{
                                                    $visita['residencia_calle'] }}</div>
                                                @endif
                                                @if(!empty($visita['residencia_numeropuerta']))
                                                <div class="col-sm-6 mb-1"><strong>N° Puerta:</strong> {{
                                                    $visita['residencia_numeropuerta'] }}</div>
                                                @endif
                                                @if(!empty($visita['residencia_piso']))
                                                <div class="col-sm-6 mb-1"><strong>Piso:</strong> {{
                                                    $visita['residencia_piso'] }}</div>
                                                @endif
                                                @if(!empty($visita['residencia_nrolote']))
                                                <div class="col-sm-6 mb-1"><strong>Nro Lote:</strong> {{
                                                    $visita['residencia_nrolote'] }}</div>
                                                @endif
                                                @if(!empty($visita['residencia_manzano']))
                                                <div class="col-sm-6 mb-1"><strong>Manzano:</strong> {{
                                                    $visita['residencia_manzano'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($visita['observaciones']))
                                        <div class="col-12 mt-3">
                                            <hr class="my-2">
                                            <div class="small text-muted">Observaciones generales</div>
                                            <div class="small text-secondary">{{ $visita['observaciones'] }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: Tiempos, motivo, operador -->
                        <div class="col-lg-5">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3 text-muted">Tiempos y detalles</h6>

                                    <div class="mb-2">
                                        <div class="small text-muted">Ingreso</div>
                                        <div class="font-weight-medium">
                                            @if(!empty($visita['fechaingreso']) || !empty($visita['horaingreso']))
                                            {{ trim((formatearFecha($visita['fechaingreso']) ?? '') . ' ' . ($visita['horaingreso'] ??
                                            '')) }}
                                            @else
                                            --
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="small text-muted">Salida</div>
                                        <div class="font-weight-medium">
                                            @if(!empty($visita['fechasalida']) || !empty($visita['horasalida']))
                                            {{ trim((formatearFecha($visita['fechasalida']) ?? '') . ' ' . ($visita['horasalida'] ??
                                            '')) }}
                                            @else
                                            --
                                            @endif
                                        </div>
                                    </div>

                                    @if(!empty($visita['motivo']))
                                    <div class="mb-2">
                                        <div class="small text-muted">Motivo</div>
                                        <div class="font-weight-medium">{{ $visita['motivo'] }}</div>
                                    </div>
                                    @endif

                                    @if(!empty($visita['empleado']))
                                    <div class="mb-2">
                                        <div class="small text-muted">Operador</div>
                                        <div class="font-weight-medium">{{ $visita['empleado'] }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Anotaciones compactas -->
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3 text-muted">Anotaciones</h6>

                                    <div class="mb-3">
                                        <div class="small ">Ingreso:</div>
                                        <div class="small text-secondary">
                                            @if(!empty($visita['anotacion_ingreso']))
                                            {!! nl2br(e($visita['anotacion_ingreso'])) !!}
                                            @else
                                            <em class="text-muted">Sin anotaciones de ingreso.</em>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="small ">Salida:</div>
                                        <div class="small text-secondary">
                                            @if(!empty($visita['anotacion_salida']))
                                            {!! nl2br(e($visita['anotacion_salida'])) !!}
                                            @else
                                            <em class="text-muted">Sin anotaciones de salida.</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @else
                    <div class="text-center text-muted py-4">No se encontró información para este registro.</div>
                    @endif
                </div>

                <div class="modal-footer border-0">
                    <button type="button" wire:click="cerrarModal" class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para abrir/cerrar modal desde eventos Livewire -->
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('show-modal', event => {
                $('#modalInfo').modal('show');
            });
            window.addEventListener('hide-modal', event => {
                $('#modalInfo').modal('hide');
            });
        });
    </script>
</div>