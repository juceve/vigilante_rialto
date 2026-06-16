<div>
    @section('title')
    Registro de Pánico
    @endsection
    @section('content_header')
    <h4>Registro de Pánico</h4>
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
                        {!! Form::select('visto_id', [''=>'Todos','1'=>'Revisado','0'=>'Sin revisar'],
                        null, ['class'=>'form-control','wire:model'=>'visto_id']) !!}
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered dataTableLiv">
                        <thead>
                            <tr class="table-info">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Prioridad
                                </th>
                                <th>
                                    Operador
                                </th>
                                <th>
                                    Fecha - Hora
                                </th>
                                <th>
                                    Estado
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($actividades))
                            @foreach ($actividades as $actividad)
                            <tr>
                                <td>
                                    {{ $actividad->id }}
                                </td>
                                <td>
                                    @switch($actividad->prioridad)
                                    @case('ALTA')
                                    <span class="badge badge-pill badge-danger">{{ $actividad->prioridad }}</span>
                                    @break

                                    @case('NORMAL')
                                    <span class="badge badge-pill badge-primary">{{ $actividad->prioridad }}</span>
                                    @break

                                    @case('BAJA')
                                    <span class="badge badge-pill badge-secondary">{{ $actividad->prioridad }}</span>
                                    @break

                                    @default
                                    @endswitch
                                </td>
                                <td>
                                    {{ $actividad->user->name }}
                                </td>
                                <td>
                                    {{ $actividad->fechahora }}
                                </td>
                                <td>
                                    @if ($actividad->visto)
                                    <span class="badge badge-pill badge-success">Revisado</span>
                                    @else
                                    <span class="badge badge-pill badge-warning">Sin Revisar</span>
                                    @endif
                                </td>
                                <td align="right">
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#messageView"
                                        wire:click='cargaMensaje({{ $actividad->id }})'><i class="fas fa-eye"></i>
                                        Revisar</button>
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="messageView" tabindex="-1" aria-labelledby="messageViewLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageViewLabel">Detalles del Registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Prioridad</label>
                                <input type="text" class="form-control bg-white" wire:model='prioridad' readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Fecha y Hora</label>
                                <input type="text" class="form-control bg-white" wire:model='fechahora' readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Operador</label>
                                <input type="text" class="form-control bg-white" wire:model='user' readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control bg-white" wire:model='visto' readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Detalle</label>
                                <textarea readonly rows="2" class="form-control bg-white"
                                    wire:model='detalle'></textarea>
                            </div>
                        </div>
                    </div>

                    <label>Ubicación:</label>
                    <div id="mapa1" style="width: 100%;height: 350px;">
                        @if ($lat && $lng)
                        <iframe src="../ubicacion/{{ $lat }}/{{ $lng }}" style="width: 100%; height: 100%"
                            name="ubicacion"></iframe>
                        @endif

                    </div>

                    @if ($imagenes)
                    <hr>
                    <label for="">CAPTURAS:</label>
                    <div class="row">
                        @foreach ($imagenes as $item)
                        <div class="col col-12 col-md-3">
                            <a href="#{{ $item->id }}">
                                <img src="{{ asset('storage/' . $item->url) }}" style="height: 100px;">
                            </a>
                            <article class="light-box" id="{{ $item->id }}">

                                <img src="{{ asset('storage/' . $item->url) }}" class="img-fluid">

                                <a href="#" class="light-box-close">X</a>
                            </article>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    $('.dataTableLiv').DataTable().destroy();
    $(".dataTableLiv").dataTable({
        "destroy": true,
            order: [[0, 'asc']],
            searching: false,       
        });
</script>
@endsection