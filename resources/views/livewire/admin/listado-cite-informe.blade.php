<div>
    @section('title')
    Informes
    @endsection
    @section('content_header')
    <h4>Informes Generados</h4>
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Informes
                            </span>

                            <div class="float-right">
                                <button class="btn btn-info btn-sm float-right" data-placement="left"
                                    data-toggle="modal" data-target="#modalNuevo" onclick="boton('create')"
                                    wire:click='resetAll'>
                                    Nuevo <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><small>Busqueda: </small></span>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                                        wire:model.debounce.500ms='busqueda'>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-1"></div>
                            <div class="col-12 col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><small>Gestión: </small></span>
                                    </div>
                                    <select class="form-control" wire:model='gestion'>
                                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                        <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><small>Filas: </small></span>
                                    </div>
                                    <select class="form-control text-center" wire:model='filas'>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead table-info">
                                    <tr class="text-uppercase text-center">
                                        <th>corr.</th>

                                        <th>Cite</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Referencia</th>
                                        <th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citeinformes as $citeinforme)
                                    <tr class="text-center">
                                        <td>{{ $citeinforme->correlativo }}</td>

                                        <td>{{ $citeinforme->cite }}</td>
                                        <td>{{ $citeinforme->fecha }}</td>
                                        <td class="text-left">{{ $citeinforme->cliente }}</td>
                                        <td class="text-left">{{ $citeinforme->referencia }}</td>
                                        <td>
                                            @if ($citeinforme->estado)
                                            <span class="badge badge-pill badge-success">Activo</span>
                                            @else
                                            <span class="badge badge-pill badge-secondary">Anulado</span>
                                            @endif
                                        </td>

                                        <td align="left" style="width: 180px;">
                                            <a class="btn btn-sm btn-info "
                                                href="{{ route('pdf.informe', $citeinforme->id."|0") }}" title="Reimprimir"
                                                target="_blank"><i class="fa fa-fw fa-print"></i></a>
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('pdf.informe', $citeinforme->id."|1") }}" title="Reimprimir con Qr"
                                                target="_blank"><i class="fa fa-fw fa-qrcode"></i></a>
                                            @if ($citeinforme->estado)
                                            <button class="btn btn-sm btn-warning" title="Editar"
                                                wire:click='editar({{ $citeinforme->id }})' data-placement="left"
                                                data-toggle="modal" data-target="#modalNuevo"
                                                onclick="boton('update')"><i class="fa fa-fw fa-edit"></i></button>

                                            <button class="btn btn-sm btn-danger" title="Anular"
                                                onclick="anular({{ $citeinforme->id }})"><i
                                                    class="fa fa-fw fa-ban"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($citeinformes)
                        {{ $citeinformes->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalNuevo" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="modalNuevoLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header table-secondary">
                    <h5 class="modal-title" id="modalNuevoLabel">DETALLES INFORME</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="row">

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group{{ $errors->has('selID') ? ' has-error' : '' }}">
                                    {!! Form::label('selID', 'Cliente:') !!}
                                    {!! Form::select('selID', $clientes, null, [
                                    'id' => 'selID',
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'placeholder' => 'Seleccione un Cliente',
                                    'wire:model' => 'selID',
                                    ]) !!}
                                    @error('selID')
                                    <small class="text-danger">Debe seleccionar un Cliente</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Representante:</label>
                                <input type="text" class="form-control" wire:model='i_representante'>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Objeto:</label>
                                <input type="text" class="form-control" wire:model.defer='i_objeto'>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Fecha:</label>
                                <input type="date" class="form-control" wire:model.defer='i_fecha'>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label>Referencia:</label>
                                <input type="text" class="form-control" wire:model.defer='i_referencia'>
                            </div>

                            <div class="col-12 mb-3">
                                <label>Punto:</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Descripción"
                                        aria-label="causal" aria-describedby="button-addon2" wire:model='i_causal'>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" id="button-addon2"
                                            wire:click='i_agregarCausal'>Agregar <i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            @if ($causales)
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 14px;">
                                        <thead class="table-info">
                                            <tr>
                                                <td align="center">DETALLES</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $i = 0;
                                            @endphp
                                            @foreach ($causales as $item)
                                            <tr>
                                                <td>{{ $item }}</td>
                                                <td align="right" style="width: 15px;"><button
                                                        class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                        wire:click='delICausal({{ $i }})'><i
                                                            class="fas fa-trash"></i></button></td>
                                            </tr>
                                            @php
                                            $i++;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif


                        </div>
                    </div>
                </div>
                <div class="modal-footer" wire:ignore>
                    <button class="btn btn-primary col-12 col-md-4" wire:click='previa'>Vista Previa <i
                            class="fas fa-eye"></i></button>
                    <button class="btn btn-success col-12 col-md-4" id="registrar" wire:click='registrar' class="close"
                        data-dismiss="modal">Registrar
                        <i class="fas fa-save"></i></button>
                    <button class="btn btn-warning col-12 col-md-4" id="actualizar" wire:click='actualizar'
                        class="close" data-dismiss="modal">Actualizar <i class="fas fa-save"></i></button>

                </div>
            </div>
        </div>
    </div>
</div>
@section('js')


<script>
    Livewire.on('renderizarpdf', data => {
            var win = window.open("../pdf/informe/" + data, '_blank');
            win.focus();
        });
</script>
<script>
    function boton(tipo) {
            if (tipo == 'create') {
                $('#registrar').show();
                $('#actualizar').hide();
            } else {
                $('#registrar').hide();
                $('#actualizar').show();
            }
        }

        function anular(cite_id) {
            Swal.fire({
                title: "Anular Cite",
                text: "Esta seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Anular",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('anular', cite_id);
                }
            });
        }
</script>
@endsection