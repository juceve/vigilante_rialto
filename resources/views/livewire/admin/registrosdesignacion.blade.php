<div>
    @section('title')
        Registro de Designaciones
    @endsection
    @section('content_header')
        <div class="container-fluid">

            <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2 mt-2">
                <h4>Registro de Designaciones</h4>

                <div class="float-right">
                    @can('designaciones.create')
                        <a href="{{ route('designaciones.create') }}" class="btn btn-info btn-sm float-right"
                            data-placement="left">
                            <i class="fas fa-plus"></i> Nuevo
                        </a>
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
                    <div class="col-md-6 mb-3">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="search" class="form-control" placeholder="Busqueda por empleado"
                                aria-label="Busqueda..." aria-describedby="basic-addon1"
                                wire:model.debounce.500ms='search'>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        {!! Form::select('cliente_id', $clientes, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Todos los Clientes',
                            'wire:model' => 'cliente_id',
                        ]) !!}
                    </div>

                    <div class="col-12 col-md-3">
                        {!! Form::select('estado', ['' => 'Todos los estados', '1' => 'Activo', '0' => 'Finalizado'], null, [
                            'class' => 'form-control',
                            'wire:model' => 'estado',
                        ]) !!}
                    </div>
                </div>

                <div class="table-responsive">
                    @if (!is_null($resultados))
                        <div class="row w-100">


                        </div>

                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="vertical-align: middle; font-size: 13px;">
                        <thead>
                            <tr class="table-info text-center">
                                <th>No</th>
                                <th class="text-left">EMPLEADO</th>
                                <th>CLIENTE</th>
                                <th>TURNO</th>
                                <th>INICIO</th>
                                <th>FINAL</th>
                                <th>ESTADO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!is_null($resultados))
                                @foreach ($resultados as $designacione)
                                    <tr class="text-center">
                                        {{-- <td>{{ ++$i }}</td> --}}

                                        <td>{{ $designacione->id }}</td>
                                        <td class="text-left">{{ $designacione->empleado }}</td>
                                        <td>{{ $designacione->cliente }}
                                        <td>{{ $designacione->turno }} <small>[{{ $designacione->horainicio }} - {{ $designacione->horafin }}]</small><br>
                                            @if ($designacione->tipo === 'NORMAL')
                                                <span class="badge badge-pill badge-primary">Normal</span>
                                            @else
                                                <span class="badge badge-pill badge-warning">Extra</span>
                                            @endif
                                        </td>
                                        <td>{{ $designacione->fechaInicio }}</td>
                                        <td>{{ $designacione->fechaFin }}</td>
                                        <td>
                                            @if (!$designacione->estado)
                                                <span class="badge badge-pill badge-secondary">Finalizado</span>
                                            @else
                                                <span class="badge badge-pill badge-success">Activo</span>
                                            @endif
                                        </td>
                                        <td class="text-right" style="width: 100px">
                                            <div class="btn-group dropleft">
                                                <!-- Botón: se añade data-boundary="window" para que Popper coloque el menu fuera de contenedores con overflow -->
                                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Opciones
                                                </button>
                                                <span class="sr-only">Toggle Dropdown</span>

                                                <!-- Menu: z-index alto para garantizar superposición -->
                                                <div class="dropdown-menu" role="menu" style="z-index: 2000;">
                                                    @if ($designacione->tipo === 'NORMAL')
                                                        <button class="dropdown-item"
                                                            wire:click="addTurnoExtra({{ $designacione->id }})">
                                                            <i class="fas fa-calendar-plus text-secondary"></i>
                                                            Turno Extra
                                                        </button>
                                                    @endif
                                                    @can('admin.registros.hombrevivo')
                                                        <a class="dropdown-item"
                                                            href="{{ route('registroshv', $designacione->id) }}"
                                                            title="">
                                                            <i class="fas fa-fw fa-user-check text-secondary"></i>
                                                            Hombre Vivo
                                                        </a>
                                                    @endcan
                                                    @can('admin.registros.novedades')
                                                        <a class="dropdown-item"
                                                            href="{{ route('regnovedades', $designacione->id) }}"
                                                            title="">
                                                            <i class="fas fa-book text-secondary"></i>
                                                            Novedades
                                                        </a>
                                                    @endcan
                                                    @can('admin.registros.diaslibres')
                                                        <a class="dropdown-item"
                                                            href="{{ route('designaciones.diaslibres', $designacione->id) }}">
                                                            <i class="fas fa-fw fa-calendar-alt text-secondary"></i>
                                                            Días libres</a>
                                                    @endcan
                                                    @can('designaciones.edit')
                                                        <a class="dropdown-item"
                                                            href="{{ route('designaciones.edit', $designacione->id) }}"
                                                            title=""><i class="fa fa-fw fa-edit text-secondary"></i>
                                                            Editar
                                                        </a>
                                                    @endcan
                                                    @can('designaciones.finalizar')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="finalizar({{ $designacione->id }})">
                                                            <i class="far fa-fw fa-calendar-times text-secondary"></i>
                                                            Finalizar
                                                        </button>
                                                    @endcan

                                                    <form
                                                        action="{{ route('designaciones.destroy', $designacione->id) }}"
                                                        method="POST" onsubmit="return false" class="delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('designaciones.destroy')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-fw fa-trash text-secondary"></i>
                                                                Eliminar de la DB
                                                            </button>
                                                        @endcan

                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="float-right">
                        {{ $resultados->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalTurnoExtra" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalTurnoExtraLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTurnoLabel">TURNO EXTRA - @if ($designacioneSelected)
                            {{ $designacioneSelected->empleado->user->name }}
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($designacioneSelected)

                    <span style="font-size: 13px;" class="text-primary"><strong>DESIGNACION ACTUAL</strong></span>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <small><strong>Establecimiento</strong></small>
                                <span
                                    class="form-control form-control-sm">{{ $designacioneSelected->turno->cliente->nombre }}</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <small><strong>Turno actual</strong></small>
                                <span class="form-control form-control-sm">{{ $designacioneSelected->turno->nombre }}
                                    [{{ $designacioneSelected->turno->horainicio }} -
                                    {{ $designacioneSelected->turno->horafin }}]</span>
                            </div>

                        </div>
                    @endif
                    <hr>
                    <span style="font-size: 13px;" class="text-success"><strong>DESIGNACION EXTRA</strong></span>
                    <div class="row">
                        <div class="col-12 col-md-6 mt-3">
                          <small><strong>Clientes</strong></small>
                            {!! Form::select('cliente_id', $clientes, null, [
                                'class' => 'form-control',
                                'wire:model' => 'selCliente_id',
                                'placeholder' => 'Elija un cliente',
                            ]) !!}
                            @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mt-3">
                            <small><strong>Turno Extra</strong></small>
                            <select class="form-control" wire:model="turno_extra_id">
                                <option value="">Seleccione un turno</option>
                                @foreach ($turnos as $turno)
                                    <option value="{{ $turno->id }}">{{ $turno->nombre }} <small>[{{ $turno->horainicio }} - {{ $turno->horafin }}]</small></option>
                                @endforeach
                            </select>

                            @error('turno_extra_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mt-3">
                            <small><strong>Bono aplicado</strong></small>
                            {!! Form::select('rrhhtipobono_id', $tipobonos, null, [
                                'class' => 'form-control',
                                'wire:model' => 'rrhhtipobono_id',
                                'placeholder' => 'Seleccione un bono',
                            ]) !!}
                            @error('rrhhtipobono_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3 mt-3">
                            <small><strong>Intervalo de Hombre Vivo</strong></small>
                            {!! Form::number('intervalo', null, [
                                'class' => 'form-control',
                                'wire:model' => 'intervalo',
                                'title' => 'Intervalo de Hombre Vivo',
                            ]) !!}
                            @error('intervalo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3 mt-3">
                            <small><strong>Fecha Inicio</strong></small>
                            {!! Form::date('fechaInicio', null, [
                                'class' => 'form-control',
                                'wire:model' => 'fechaInicio',
                            ]) !!}
                            @error('fechaInicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="resetearTurnoExtra"><i class="fas fa-ban"></i> Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="registrarTurnoExtra">Registrar
                        &nbsp;<i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        function finalizar(id) {
            Swal.fire({
                title: "FINALIZAR DESIGNACIÓN",
                text: "Está seguro de realizar esta operación?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('finalizar', id);
                }
            });
        }
    </script>
    <script>
        Livewire.on('openModal', () => {
            $('#modalTurnoExtra').modal('show');
        });
        Livewire.on('closeModal', () => {
            $('#modalTurnoExtra').modal('hide');
        });
    </script>
@endsection
