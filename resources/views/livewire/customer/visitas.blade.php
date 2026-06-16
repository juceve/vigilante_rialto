<div>

    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <label>
                        REGISTRO DE VISITAS
                    </label>
                    <a href="javascript:history.back();" class="btn btn-sm btn-light shadow-sm"><i
                            class="fa fa-long-arrow-left fa-sm"></i>
                        Volver</a>
                </div>
            </div>
            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Desde</span>
                            </div>
                            <input type="date" class="form-control" wire:model='inicio' aria-label="inicio"
                                aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" class="form-control" wire:model='final' aria-label="final"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        {!! Form::select('estado', [''=>'Todos','1'=>'En proceso','0'=>'Finalizado'],
                        null, ['class'=>'form-control','wire:model'=>'estado']) !!}
                    </div>
                </div>
                <hr>
                <div class="container">
                    <div class="table-responsive">
                        @if (!is_null($resultados))
                        <div class="row w-100">
                            <div class="col-12 mb-3">
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Busqueda..."
                                        aria-label="Busqueda..." aria-describedby="basic-addon1"
                                        wire:model.debounce.500ms='search'>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm"
                        style="vertical-align: middle; font-size: 13px;">

                        <thead>
                            <tr class="table-info">
                                <th>VISITANTE</th>
                                <th class="text-center">DOC. IDENTIDAD</th>
                                <th>RESIDENTE</th>
                                <th class="text-center">INGRESO</th>
                                <th class="text-center">SALIDA</th>
                                <th>ESTADO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                            @forelse ($resultados as $item)
                            <tr>
                                <td>{{$item->visitante}}</td>
                                <td class="text-center">{{$item->docidentidad}}</td>
                                <td>{{$item->residente}}</td>
                                <td class="text-center">{{$item->fechaingreso.' '.$item->horaingreso}}</td>
                                <td class="text-center">
                                    @if (!$item->estado)
                                    {{$item->fechasalida.' '.$item->horasalida}}
                                    @else
                                    --
                                    @endif
                                </td>
                                <td>
                                    @if ($item->estado)
                                    <span class="badge badge-pill badge-success">En proceso</span>
                                    @else
                                    <span class="badge badge-pill badge-secondary">Finalizado</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" title="Ver info"
                                        wire:click='verInfo({{$item->id}})' data-toggle='modal'
                                        data-target='#modalInfo'>
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="7">No se econtraron resultados.</td>
                            </tr>
                            @endforelse
                            @else
                            <tr>
                                <td class="text-center" colspan="7">No se econtraron resultados.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    @if (!is_null($resultados))
                    {{ $resultados->links() }}
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel"><strong>INFO VISITA - ID: {{$visita->id}}</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading>

                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>

                    </div>
                    <div wire:loading.remove>
                        <div class="row">
                            <div class="col-12 col-md-6">

                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td><strong>CLIENTE:</strong></td>
                                        <td>{{$visita->cliente}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>VISITANTE:</strong></td>
                                        <td>{{$visita->visitante}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DOC. IDENTIDAD:</strong></td>
                                        <td>{{$visita->docidentidad}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>RESIDENTE:</strong></td>
                                        <td>{{$visita->residente}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NRO. VIVIENDA:</strong></td>
                                        <td>{{$visita->nrovivienda}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>INGRESO:</strong></td>
                                        <td>{{$visita->fechaingreso." ".$visita->horaingreso}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>SALIDA:</strong></td>
                                        <td>{{$visita->fechasalida." ".$visita->horasalida}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>MOTIVO:</strong></td>
                                        <td>{{$visita->motivo}}</td>
                                    </tr>
                                    @if ($visita->motivo =="Otros")
                                    <tr>
                                        <td><strong>OTROS:</strong></td>
                                        <td>{{$visita->otros}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>OBSERVACIONES:</strong></td>
                                        <td>{{$visita->observaciones}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ESTADO:</strong></td>
                                        <td>
                                            @if ($visita->estado)
                                            <span class="badge badge-pill badge-success">En proceso</span>
                                            @else
                                            <span class="badge badge-pill badge-secondary">Finalizado</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                @if ($visita->imgs)
                                <h6>CAPTURA:</h6>
                                <img src="{{asset('storage/'.$visita->imgs)}}" class="img-fluid img-thumbnail">
                                @else
                                <img src="{{asset('images/sinimagen.jpg')}}" class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
</div>