<div>
    @section('title')
    Turnos de Cliente
    @endsection
    @section('content_header')
    <h4>Turnos de Cliente</h4>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <span id="card_title">
                        CLIENTE: <strong>{{ $cliente->nombre }}</strong>
                    </span>

                    <div class="float-right">
                        <a href="javascript:history.back()" class="btn btn-info btn-sm float-right"
                            data-placement="left">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    @can('turnos.create')
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistro"><i
                            class="fas fa-plus"></i> Agregar</button>
                    @endcan
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="table-info">
                            <tr>
                                <th>
                                    Nro.
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Hora Inicio
                                </th>
                                <th>
                                    Hora Fin
                                </th>
                                <th>

                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($turnos as $turno)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $turno->nombre }}</td>
                                <td>{{ $turno->horainicio }}</td>
                                <td>{{ $turno->horafin }}</td>
                                <td align="right">
                                    @can('turnos.edit')
                                    <button class="btn btn-sm btn-outline-warning" title="Editar turno"
                                        data-toggle="modal" data-target="#modalEditar"
                                        wire:click='cargaTurno({{$turno->id}})'><i class="fas fa-edit"></i></button>
                                    @endcan
                                    {{-- @can('turnos.ctrlpuntos')
                                    <a href="{{route('puntoscontrolv2',$turno->id)}}"
                                        class="btn btn-outline-success btn-sm" title="Puntos de Control"><i
                                            class="fas fa-map-marked-alt"></i></a>
                                    @endcan --}}
                                    @can('turnos.destroy')
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminar({{ $turno->id }})"
                                        title="Eliminar de la DB"><i class="fas fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" role="dialog" aria-labelledby="modalRegistroLabel"
        aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header table-info">
                    <h5 class="modal-title" id="modalRegistroLabel">Nuevo Turno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" onsubmit="return false">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" placeholder="Nombre de Turno" required
                                wire:model.defer='nombre'>
                        </div>
                        <div class="form-group">
                            <label>Hora Inicio:</label>
                            <input type="time" class="form-control" required wire:model.defer='horainicio'>
                        </div>
                        <div class="form-group">
                            <label>Hora Fin:</label>
                            <input type="time" class="form-control" required wire:model.defer='horafin'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="fas fa-times-circle"></i> Cerrar</button>

                        <button class="btn btn-info" wire:click='registrarTurno' wire:loading.attr="disabled">
                            <i class="fas fa-save"></i> Registrar
                            <div wire:loading wire:target="registrarTurno">
                                <div class="spinner-grow spinner-grow-sm"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel"
        aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header table-info">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Turno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" onsubmit="return false">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" placeholder="Nombre de Turno" required
                                wire:model.defer='enombre'>
                        </div>
                        <div class="form-group">
                            <label>Hora Inicio:</label>
                            <input type="time" class="form-control" required wire:model.defer='ehorainicio'>
                        </div>
                        <div class="form-group">
                            <label>Hora Fin:</label>
                            <input type="time" class="form-control" required wire:model.defer='ehorafin'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="fas fa-times-circle"></i> Cerrar</button>

                        <button class="btn btn-info" wire:click='editarTurno' wire:loading.attr="disabled"
                            data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-save"></i> Actualizar
                            <div wire:loading wire:target="editarTurno">
                                <div class="spinner-grow spinner-grow-sm"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('js')
<script src="{{ asset('vendor/jquery/scripts.js') }}"></script>
@include('vendor.mensajes')
<script>
    function eliminar(id) {
            Swal.fire({
                title: 'Eliminar Turno',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'No, cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('delete',id);
                }
            })
        }
</script>
@endsection
