<div>
    @section('title')
    RECIBOS
    @endsection
    @section('content_header')
    <h4>RECIBOS GENERADOS</h4>
    @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Recibos
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
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

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
                            <table class="table table-striped table-bordered ">
                                <thead class="table-info">
                                    <tr class="text-uppercase text-center">
                                        <th>Corr.</th>
                                        <th>Cite</th>

                                        <th>Cliente</th>
                                        <th>Mescobro</th>


                                        <th style="max-width: 100px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citerecibos as $citerecibo)
                                    <tr class="text-center">
                                        <td>{{ $citerecibo->correlativo }}</td>
                                        <td>{{ $citerecibo->cite }}</td>
                                        <td class="text-left">{{ $citerecibo->cliente }}</td>
                                        <td>{{ $citerecibo->mescobro }}</td>

                                        <td class="text-right">
                                            <a class="btn btn-sm btn-info "
                                                href="{{ route('pdf.recibo', $citerecibo->id."|0") }}" title="Reimprimir"
                                                target="_blank"><i class="fa fa-fw fa-print"></i></a>
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('pdf.recibo', $citerecibo->id."|1") }}" title="Reimprimir con Qr"
                                                target="_blank"><i class="fa fa-fw fa-qrcode"></i></a>
                                            @if ($citerecibo->estado)
                                            <button class="btn btn-sm btn-warning" title="Editar"
                                                wire:click='editar({{ $citerecibo->id }})' data-placement="left"
                                                data-toggle="modal" data-target="#modalNuevo"
                                                onclick="boton('update')"><i class="fa fa-fw fa-edit"></i></button>

                                            <button class="btn btn-sm btn-danger" title="Anular"
                                                onclick="anular({{ $citerecibo->id }})"><i
                                                    class="fa fa-fw fa-ban"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $citerecibos->links() }}
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
                    <div class="row">

                        <div class="col-12 col-md-6 mb-3">
                            <label>Fecha carta:</label>
                            <input type="date" class="form-control" wire:model.defer='fecha'>
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label>Mes Cobro:</label>
                            <select name="mescobro" id="mescobro" class="form-control" wire:model='mescobro'>
                                <option value="">Seleccione un Mes</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            @error('c_mescobro')
                            <small class="text-danger">Debe seleccionar un mes</small>
                            @enderror
                        </div>

                        <div class="col-12 col-md-3 mb-3">
                            <label>Gestión Cobro:</label>
                            <select name="gestion" id="gestion" class="form-control" wire:model='gestioncobro'>
                                <option value="">Seleccione una Gestión</option>
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                                <option value="{{ date('Y') - 3 }}">{{ date('Y') - 3 }}</option>
                            </select>
                            @error('gestion')
                            <small class="text-danger">Debe seleccionar una gestión</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Monto Bs.:</label>
                            <input type="number" step="0.01" class="form-control" wire:model.defer='monto'>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            {!! Form::label('selID', 'Cliente:') !!}
                            {!! Form::select('selID', $clientes, null, [
                            'id' => 'selID',
                            'class' => 'form-control',
                            'required' => 'required',
                            'placeholder' => 'Seleccione un Cliente',
                            'wire:model' => 'selID',
                            ]) !!}
                            @error('selID')
                            <small class="text-danger">Debe seleccionar un cliente</small>
                            @enderror
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
                        class="close" data-dismiss="modal">Actualizar
                        <i class="fas fa-save"></i></button>

                </div>
            </div>
        </div>
    </div>
</div>
@section('js')

<script>
    Livewire.on('renderizarpdf', data => {
            var win = window.open("../pdf/recibo/" + data, '_blank');
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