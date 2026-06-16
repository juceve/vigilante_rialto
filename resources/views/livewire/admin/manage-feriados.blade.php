<div>
    @section('title')
        Feriados
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Feriados</h4>
                <div class="">
                    @can('rrhhferiados.create')
                        <button class="btn btn-primary" wwire:click="resetInput" data-toggle="modal" data-target="#diaModal">
                            <i class="fa fa-plus"></i> Nuevo
                        </button>
                    @endcan

                </div>
            </div>
        </div>
    @endsection
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listado de Días Feriados</h3>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Buscar por nombre"
                            aria-describedby="basic-addon1" wire:model.debounce.500ms="busqueda">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex justify-content-right align-items-center">
                        <span>Ver</span>&nbsp;
                        <select class="form-control" wire:model="perPage">
                            @foreach ($perPageOptions as $opcion)
                                <option value="{{ $opcion }}">{{ $opcion }} filas</option>
                            @endforeach
                        </select>&nbsp;
                        <span>filas</span>
                    </div>
                </div>
            </div>


            <table class="table table-bordered table-hover ">
                <thead class="table-info">
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Recurrente</th>
                        <th>Factor</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dias as $dia)
                        <tr>
                            <td>{{ $dia->nombre }}</td>
                            <td>{{ $dia->fecha ?? '-' }}</td>
                            <td>{{ $dia->fecha_inicio ?? '-' }}</td>
                            <td>{{ $dia->fecha_fin ?? '-' }}</td>
                            <td>{{ $dia->recurrente ? 'Sí' : 'No' }}</td>
                            <td>{{ $dia->factor }}</td>
                            <td>{{ $dia->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                @can('rrhhferiados.edit')
                                    <button class="btn btn-primary btn-sm" wire:click="edit({{ $dia->id }})"
                                        data-toggle="modal" data-target="#diaModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endcan
                                @can('rrhhferiados.destroy')
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $dia->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="float-right mt-2">
                {{ $dias->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="diaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $dia_id ? 'Editar' : 'Nuevo' }} Día Especial</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" wire:model.defer="nombre">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <input type="radio" name="tipo_fecha" id="radio_fecha_unica" class="mr-2" checked>
                            <label for="radio_fecha_unica" class="mb-0">Fecha única</label>
                        </div>
                        <input type="date" id="input_fecha_unica" class="form-control" wire:model.defer="fecha">
                        @error('fecha')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <input type="radio" name="tipo_fecha" id="radio_rango" class="mr-2">
                            <label for="radio_rango" class="mb-0">Rango de fechas</label>
                        </div>
                        <input type="date" id="input_fecha_inicio" class="form-control mb-2"
                            wire:model.defer="fecha_inicio" placeholder="Inicio" disabled>
                        <input type="date" id="input_fecha_fin" class="form-control" wire:model.defer="fecha_fin"
                            placeholder="Fin" disabled>
                        @error('fecha_inicio')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        @error('fecha_fin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Factor</label>
                        <input type="number" step="0.01" min="0" class="form-control"
                            wire:model.defer="factor">
                        @error('factor')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" wire:model.defer="recurrente">
                        <label class="form-check-label">Recurrente</label>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" wire:model.defer="activo">
                        <label class="form-check-label">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $dia_id ? 'update' : 'store' }}">
                        {{ $dia_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>



</div>
@section('js')
    <script>
        window.addEventListener('close-modal', event => {
            $('#diaModal').modal('hide');
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const radioFechaUnica = document.getElementById("radio_fecha_unica");
            const radioRango = document.getElementById("radio_rango");
            const inputFechaUnica = document.getElementById("input_fecha_unica");
            const inputFechaInicio = document.getElementById("input_fecha_inicio");
            const inputFechaFin = document.getElementById("input_fecha_fin");

            function toggleFechas() {
                if (radioFechaUnica.checked) {
                    inputFechaUnica.disabled = false;
                    inputFechaInicio.disabled = true;
                    inputFechaFin.disabled = true;
                    inputFechaInicio.value = null;
                    inputFechaFin.value = null;
                } else {
                    inputFechaUnica.disabled = true;
                    inputFechaInicio.disabled = false;
                    inputFechaFin.disabled = false;
                    inputFechaUnica.value = null;
                }
            }

            radioFechaUnica.addEventListener("change", toggleFechas);
            radioRango.addEventListener("change", toggleFechas);

            // Inicializar estado
            toggleFechas();
        });
    </script>
@endsection
