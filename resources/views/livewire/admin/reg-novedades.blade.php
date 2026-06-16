<div>
    @section('title')
    Registro de Novedades
    @endsection
    @section('content_header')
    <h4>Registro de Novedades</h4>
    @endsection
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Detalle Designación
                            </span>

                            <div class="float-right">
                                <a href="{{ route('designaciones.index') }}" class="btn btn-info btn-sm float-right"
                                    data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Operador:</strong>
                                    {{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Empresa:</strong>
                                    {{ $designacione->turno->cliente->nombre }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Turno:</strong>
                                    {{ $designacione->turno->nombre }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Fecha inicio:</strong>
                                    {{ $designacione->fechaInicio }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Fecha fin:</strong>
                                    {{ $designacione->fechaFin }}
                                </div>
                            </div>
                            <div class="col col-12 col-md-4">
                                <div class="form-group">
                                    <strong>Estado:</strong>
                                    @if ($designacione->fechaFin >= date('Y-m-d'))
                                    <span class="badge badge-pill badge-success">Activo</span>
                                    @else
                                    <span class="badge badge-pill badge-secondary">Inactivo</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col col-6">
                                <h3 class="card-title">REGISTROS DE NOVEDADES</h3>
                            </div>
                            <div class="col col-6 text-right">
                                <a href="{{ route('pdfNovedades', $designacione->id) }}" target="_blank"
                                    class="btn btn-danger btn-sm">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive" wire:ignore>
                            <table class="table table-bordered table-striped dataTable">

                                <thead class="table-info">
                                    <tr align="center">
                                        <th>Nro</th>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>DETALLE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($designacione->novedades as $novedad)
                                    <tr align="center">
                                        <td>
                                            {{ $i++ }}
                                        </td>

                                        <td>
                                            {{ $novedad->fecha }}
                                        </td>
                                        <td>
                                            {{ $novedad->hora }}
                                        </td>
                                        <td align="left">
                                            @if (strlen($novedad->contenido) > 30)
                                            {{ substr($novedad->contenido, 0, 30) . '...' }}
                                            @else
                                            {{ $novedad->contenido }}
                                            @endif
                                        </td>
                                        <td align="right">
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#messageView" wire:click='cargaDatos({{ $novedad->id }})'>
                                                <i class="fas fa-eye"></i>
                                                Detalles
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>

    </section>

    <!-- Modal -->
    <div class="modal fade" id="messageView" tabindex="-1" aria-labelledby="messageViewLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered ">
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
                                <label>Fecha</label>
                                <input type="text" class="form-control bg-white" wire:model='fecha' readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>hora</label>
                                <input type="text" class="form-control bg-white" wire:model='hora' readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Detalle</label>
                                <textarea readonly rows="2" class="form-control bg-white"
                                    wire:model='contenido'></textarea>
                            </div>
                        </div>
                    </div>
                    @if ($imagenes)
                    <label>Capturas: </label>
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
                    <label>Ubicación:</label>
                    <div id="mapa1" style="width: 100%;height: 350px;">
                        @if ($lat && $lng)
                        <iframe src="../ubicacion/{{ $lat }}/{{ $lng }}" style="width: 100%; height: 100%"
                            name="ubicacion"></iframe>
                        @endif

                    </div>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>