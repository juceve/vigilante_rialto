<div>
    @section('title')
    Registro de Tareas
    @endsection
    @section('content_header')
    <div class="container-fluid">

        <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2 mt-2">
            <h4>Registro de Tareas</h4>

            <div class="float-right">
                @can('tareas.create')
                <button class="btn btn-info btn-sm float-right" data-placement="left" data-target="#modalNuevo"
                    data-toggle="modal">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
                @endcan
            </div>
        </div>
    </div>
    @endsection

    <div class="container-fluid">
        <div class="card">

            <div class="card-body">
                <label for="">Filtrar:</label>
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        {!! Form::select('cliente_id', $clientes, null,
                        ['class'=>'form-control','placeholder'=>'Seleccione un cliente','wire:model'=>'selCliente']) !!}
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
                        {!! Form::select('estado', [''=>'Todos','1'=>'En proceso','0'=>'Finalizado'],
                        null, ['class'=>'form-control','wire:model'=>'estado']) !!}
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    @if (!is_null($resultados))
                    <div class="row w-100">
                        <div class="col-12 col-md-8 mb-3">
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-search"></i></span>
                                </div>
                                <input type="search" class="form-control" placeholder="Busqueda..."
                                    aria-label="Busqueda..." aria-describedby="basic-addon1"
                                    wire:model.debounce.500ms='search'>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <button class="btn btn-success btn-block" wire:click='exporExcel'><i
                                    class="fas fa-file-excel"></i>
                                Exportar</button>
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <a href="{{route('pdf.tareas')}}" class="btn btn-danger btn-block" target="_blank"><i
                                    class="fas fa-file-pdf"></i> Exportar</a>
                        </div>
                    </div>

                </div>

                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="vertical-align: middle">
                        <thead>
                            <tr class="table-info">
                                <th class="text-center">ID</th>
                                <th class="text-center">FECHA</th>
                                <th>CLIENTE</th>
                                <th>EMPLEADO</th>
                                {{-- <th>CONTENIDO</th> --}}
                                <th class="text-center">ESTADO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                            @forelse ($resultados as $item)
                            <tr>
                                <td class="text-center">{{$item->id}}</td>
                                <td class="text-center">{{$item->fecha}}</td>
                                <td>{{$item->nombrecliente}}</td>
                                <td>{{$item->nombreempleado}}</td>
                                {{-- <td>{{$item->contenido}}</td> --}}
                                <td class="text-center">
                                    @if ($item->estado)
                                    <span class="badge badge-pill badge-success">Pendiente</span>
                                    @else
                                    <span class="badge badge-pill badge-secondary">Finalizado</span>
                                    @endif
                                </td>
                                <td class="text-right" style="width: 100px;">
                                    <button class="btn btn-info btn-sm" title="Ver info"
                                        wire:click='verInfo({{$item->id}})' data-toggle='modal'
                                        data-target='#modalInfo'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @can('tareas.destroy')
                                    <button class="btn btn-danger btn-sm" title="Eliminar"
                                        onclick='eliminar({{$item->id}})'>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
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
            </div>
            <div class="d-flex justify-content-end">
                @if (!is_null($resultados))
                {{ $resultados->links() }}
                @endif

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel"><strong>INFO TAREA - ID: {{$tarea->id}}</strong></h5>
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
                                        <td>{{$tarea->nombrecliente}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>EMPLEADO:</strong></td>
                                        <td>{{$tarea->nombreempleado}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>FECHA:</strong></td>
                                        <td>{{$tarea->fecha}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>CONTENIDO:</strong></td>
                                        <td>{{$tarea->contenido}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ESTADO:</strong></td>
                                        <td>
                                            @if ($tarea->estado)
                                            <span class="badge badge-pill badge-success">Pendiente</span>
                                            @else
                                            <span class="badge badge-pill badge-secondary">Finalizado</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                @if (count($imgs))
                                <h6>CAPTURAS:</h6>
                                <hr>
                                <div class="row">
                                    @foreach ($imgs as $img)

                                    <div class="col-sm-4">
                                        <a href="{{asset('storage/'.$img)}}" data-toggle="lightbox" data-title="Galeria"
                                            data-gallery="gallery">
                                            <img src="{{asset('storage/'.$img)}}" class="img-fluid img-thumbnail mb-2"
                                                style="height: 150px;" />
                                        </a>
                                    </div>
                                    @endforeach

                                </div>
                                @else
                                <span>No se encontraron capturas.</span><img src="{{asset('images/sinimagen.jpg')}}"
                                    class="img-fluid img-thumbnail">@endif

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

    <div class="modal fade" id="modalNuevo" tabindex="-1" aria-labelledby="modalNuevoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoLabel"><strong>NUEVA TAREA</strong></h5>
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

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label>FECHA:</label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="date" class="form-control" wire:model.defer='fecha'>
                            @error('fecha')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-4 mb-3">
                            <label>CLIENTE:</label>
                        </div>
                        <div class="col-8 mb-3">

                            {{-- {!! Form::label('cliente_id', 'Cliente') !!} --}}
                            {!! Form::select('cliente_id',$clientes, null, ['id' => 'cliente_id', 'class' =>
                            'form-control', 'wire:model'=>'cliente_id', 'placeholder'=>'Seleccione un cliente']) !!}
                            @error('cliente_id')
                            <small class="text-danger">{{$message}}</small>
                            @enderror

                        </div>
                        <div class="col-4 mb-3">
                            <label>GUARDIA:</label>
                        </div>
                        <div class="col-8 mb-3">

                            <select class="form-control" wire:model.defer='empleado_id'>
                                <option value="">Seleccione un guardia</option>
                                @if ($guardias)
                                @foreach ($guardias as $item)
                                <option value="{{$item->id}}">{{$item->empleado}}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('empleado_id')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-4 mb-3">
                            <label>CONTENIDO:</label>
                        </div>
                        <div class="col-8 mb-3">
                            <textarea class="form-control" rows="2" placeholder="Descripción de la tarea"
                                wire:model.defer='contenido'></textarea>
                            @error('contenido')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                        CANCELAR</button>
                    <button type="button" class="btn btn-primary" wire:click='registrar'>REGISTRAR <i
                            class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('css')
<link rel="stylesheet" href="{{asset('vendor/ekko-lightbox/ekko-lightbox.css')}}">
@endsection
@section('js')
<script src="{{asset('vendor/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
    $(function () {
      $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
          alwaysShowClose: true
        });
      });
    })
</script>

<script>
    Livewire.on('cerrarModalNuevo',()=>{
        $('#modalNuevo').modal('hide');
    });
</script>
<script>
    function eliminar(id){
        
        Swal.fire({
        title: "ELIMINAR REGISTRO",
        text: "Está seguro de realizar esta operación?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "No, cancelar",
        }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit('destroy',id);
        }
        });
    }
</script>
@endsection