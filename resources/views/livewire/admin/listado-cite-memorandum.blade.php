<div>
    @section('title')
    Memorandos
    @endsection
    @section('content_header')
    <h4>Memorandos Generados</h4>
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Listado de Memorandos
                            </span>

                            <div class="float-right">
                                <button class="btn btn-info btn-sm float-right" data-placement="left"
                                    data-toggle="modal" data-target="#modalMemo" onclick="boton('create')"
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
                            <table class="table table-bordered table-striped">
                                <thead class="table-info">
                                    <tr class="text-center text-uppercase">
                                        <th style="width: 50px;">Corr.</th>

                                        <th>Cite</th>
                                        <th>Fecha</th>

                                        <th>Empleado</th>
                                        <th>Estado</th>
                                        <th style="width: 150px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($citememorandums)
                                    @foreach ($citememorandums as $citememorandum)
                                    <tr class="text-center">
                                        <td>{{ $citememorandum->correlativo }}</td>
                                        <td>{{ $citememorandum->cite }}</td>
                                        <td>{{ $citememorandum->fecha }}</td>
                                        <td class="text-left">{{ $citememorandum->empleado }}</td>
                                        <td>
                                            @if ($citememorandum->estado)
                                            <span class="badge badge-pill badge-success">Activo</span>
                                            @else
                                            <span class="badge badge-pill badge-secondary">Anulado</span>
                                            @endif
                                        </td>

                                        <td class="text-left">
                                            <a class="btn btn-sm btn-info "
                                                href="{{ route('pdf.memorandum', $citememorandum->id) }}"
                                                title="Reimprimir" target="_blank"><i class="fa fa-fw fa-print"></i></a>
                                            @if ($citememorandum->estado)
                                            <button class="btn btn-sm btn-warning" title="Editar"
                                                wire:click='editar({{ $citememorandum->id }})' data-placement="left"
                                                data-toggle="modal" data-target="#modalMemo"
                                                onclick="boton('update')"><i class="fa fa-fw fa-edit"></i></button>

                                            <button class="btn btn-sm btn-danger" title="Anular"
                                                onclick="anular({{ $citememorandum->id }})"><i
                                                    class="fa fa-fw fa-ban"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif


                                </tbody>
                            </table>
                        </div>
                        @if ($citememorandums)
                        {{ $citememorandums->links() }}
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="modalMemo" tabindex="-1" role="dialog" aria-labelledby="modalMemoLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header table-secondary">
                    <h5 class="modal-title" id="modalMemoLabel">DETALLE MEMORANDUM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div>
                        <div class="row">

                            <div class="col-12 col-md-6 mb-2">

                                <label for="selID">Empleado:</label>
                                <select name="selID" id="selID" class="form-control" wire:model='selID'>
                                    <option value="">Seleccione un Empleado</option>
                                    @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">
                                        {{ $empleado->nombres . ' ' . $empleado->apellidos }}</option>
                                    @endforeach
                                </select>
                                @error('selID')
                                <small class="text-danger">Debe seleccionar un Empleado</small>
                                @enderror
                            </div>


                            <div class="col-12 col-md-6 mb-2">
                                <label>Fecha:</label>
                                <input type="date" class="form-control" wire:model.defer='m_fecha'>
                            </div>



                            <div class="col-12 mb-2">
                                <label>Cuerpo:</label>
                                <textarea class="form-control" name="motivo" id="motivo" rows="5"
                                    wire:model='m_motivo'></textarea>
                            </div>

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
            var win = window.open("../pdf/memorandum/" + data, '_blank');
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