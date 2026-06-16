<div>
    @section('title')
    Registros de Hombre Vivo
    @endsection
    @section('content_header')
    <h4>Registros de Hombre Vivo</h4>
    @endsection

    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Detalles
                            </span>

                            <div class="float-right">
                                <a href="{{route('designaciones.index')}}" class="btn btn-info btn-sm float-right"
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
                                <h3 class="card-title">REGISTROS DE HOMBRE VIVO</h3>
                            </div>
                            <div class="col col-6 text-right">
                                {{-- <button href="{{ route('admin.designaciones.pdfHV', $designacione->id) }}"
                                    target="_blank" class="btn btn-danger btn-sm">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button> --}}
                                {{-- <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i>
                                    XLS</button> --}}
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive" wire:ignore>
                            <table class="table table-bordered table-striped dataTableA" style="font-size: 12px;">

                                <thead class="table-info">
                                    <tr align="center" style="vertical-align: middle">
                                        <td><strong>FECHAS</strong></td>
                                        @foreach ($designacione->intervalos as $punto)
                                        <td><strong>{{ $punto->nombre }} <br> {{ $punto->hora }}</strong></td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($registros) > 0)
                                    @foreach ($registros as $registro)
                                    <tr align="center">
                                        @foreach ($registro as $item)
                                        @if (strlen($item[0]) > 5)
                                        <td>{{ $item[0] }}</td>
                                        @else
                                        @switch($item[1])
                                        @case(1)
                                        <td><a class="text-danger" href="javascript:void(0);" title="Sin Marcado">
                                                &#10060;
                                            </a></td>
                                        @break

                                        @case(2)
                                        <td>
                                            {{ $item[0] }}
                                        </td>
                                        @break

                                        @case(0)
                                        <td><a class="text-success" href="javascript:void(0);" title="Ver Info"
                                                data-toggle="modal" data-target="#modalPunto"
                                                wire:click="cargaPunto({{ $item[2] }})">
                                                {{ $item[0] }}
                                            </a></td>
                                        @break
                                        @endswitch
                                        @endif
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    @endif

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
    <div class="modal fade" id="modalPunto" tabindex="-1" aria-labelledby="modalPuntoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPuntoLabel">Marcado de Hombre Vivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Fecha:</label>
                                <input type="text" class="form-control bg-white" wire:model='fecha' readonly>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Hora:</label>
                                <input type="text" class="form-control bg-white" wire:model='hora' readonly>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Anotaciones:</label>
                                <textarea rows="2" class="form-control bg-white" wire:model='anotaciones'
                                    readonly></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Ubicación:</label>
                                <div id="mapa1" style="width: 100%;height: 350px;">
                                    @if ($lat && $lng)
                                    <iframe src="../ubicacion/{{ $lat }}/{{ $lng }}" style="width: 100%; height: 100%"
                                        name="ubicacion"></iframe>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>