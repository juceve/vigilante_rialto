<div>
    @section('title')
        Turnos Guardia
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Turnos Guardia</h4>
                <div class="">
                    <button class="btn btn-primary" wire:click="resetInput" data-toggle="modal" data-target="#turnoModal">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listado de Turnos</h3>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Buscar por nombre" wire:model.debounce.500ms="busqueda">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <span class="mr-2">Ver</span>
                    <select class="form-control w-auto" wire:model="perPage">
                        @foreach ($perPageOptions as $opcion)
                            <option value="{{ $opcion }}">{{ $opcion }} filas</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th>Nombre</th>
                        <th>Hora inicio</th>
                        <th>Hora fin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turnos as $turno)
                        <tr>
                            <td>{{ $turno->nombre }}</td>
                            <td>{{ $turno->horainicio }}</td>
                            <td>{{ $turno->horafin }}</td>
                            <td class="text-right">
                                <button class="btn btn-primary btn-sm" wire:click="edit({{ $turno->id }})" data-toggle="modal" data-target="#turnoModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                {{-- <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $turno->id }})">
                                    <i class="fas fa-trash"></i>
                                </button> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="float-right mt-2">
                {{ $turnos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="turnoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $turn_id ? 'Editar' : 'Nuevo' }} Turno Guardia</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" wire:model.defer="nombre">
                        @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Hora inicio</label>
                        <input type="time" class="form-control" wire:model.defer="horainicio">
                        @error('horainicio') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Hora fin</label>
                        <input type="time" class="form-control" wire:model.defer="horafin">
                        @error('horafin') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $turn_id ? 'update' : 'store' }}">
                        {{ $turn_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@section('js')
    <script>
        window.addEventListener('close-modal', event => {
            $('#turnoModal').modal('hide');
        });

        function confirmDelete(id) {
            if (typeof Swal === 'undefined') {
                if (confirm('¿Eliminar registro?')) {
                    Livewire.emit('confirmDelete', id);
                }
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede revertir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('confirmDelete', id);
                }
            });
        }

        Livewire.on('success', message => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Éxito', message, 'success');
            } else {
                alert(message);
            }
        });
    </script>
@endsection
<div>
    {{-- The Master doesn't talk, he acts. --}}
</div>
