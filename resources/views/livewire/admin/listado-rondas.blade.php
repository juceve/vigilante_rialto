<div>
    @section('title', 'Rondas')

    <div class="content-header">
         <div class="d-flex justify-content-between align-items-center mb-3">
           <h4 class="m-0 text-dark">Rondas - {{ $cliente->nombre }}</h4>
            <a href="{{ route('clientes.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Listado de Rondas</h3>
            <div class="float-right">
                <button class="btn btn-primary btn-sm" wire:click='showModalCrear'>
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="rondas-table">
                    <thead class="table-info">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rondas as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nombre ?? '-' }}</td>
                                <td>{{ $item->descripcion ?? '-' }}</td>
                                <td>
                                    @if ($item->estado)
                                        <span class="badge badge-success">Activa</span>
                                    @else
                                        <span class="badge badge-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-info" title="Editar"
                                        wire:click='showModalEditar({{ $item->id }})'><i
                                            class="fas fa-edit"></i></button>
                                    <a href="{{ route('clientes.ronda_puntos', $item->id) }}"
                                        class="btn btn-sm btn-warning" title="Marcar Puntos de Control">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" title="Eliminar"
                                        onclick="eliminar({{ $item->id }})"><i class="fas fa-trash"></i></button>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No existen rondas registradas.</td>
                            </tr>
                        @endforelse
                </table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalCrearRonda" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div
                    class="modal-header  @if ($editMode) bg-info text-white
                        @else
                            bg-primary text-white @endif ">
                    <h5 class="modal-title" id="exampleModalLabel">
                        @if ($editMode)
                            Editar Ronda
                        @else
                            Nueva Ronda
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- ...campos del formulario... --}}
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="nuevo_nombre" class="form-control" id="nombre"
                            required>
                        @error('nuevo_nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea wire:model.defer="nuevo_descripcion" class="form-control" id="descripcion"></textarea>
                        @error('nuevo_descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select wire:model.defer="nuevo_estado" class="form-control" id="estado">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                        @error('nuevo_estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-ban"></i> Cerrar
                    </button>
                    @if ($editMode)
                        <button type="button" class="btn btn-info" wire:click="actualizarRonda">
                            Actualizar Ronda <i class="fas fa-save"></i>
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="crearRonda">
                            Registrar Ronda <i class="fas fa-save"></i>
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>

@section('js')
    <script>
        window.addEventListener('abrir-modal-crear', () => {
            $('#modalCrearRonda').modal('show');
        });
        window.addEventListener('cerrar-modal-crear', () => {
            $('#modalCrearRonda').modal('hide');
        });
    </script>

    <script>
        function eliminar(id) {
            Swal.fire({
                title: 'ELIMINAR RONDA',
                text: "¿Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarRonda', id);
                }
            })
        }
    </script>

@endsection
