<div>
    @section('title')
        Sueldos
    @endsection
    @section('content_header')
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Sueldos</h4>
                <div class="">
                    @can('rrhhsueldos.create')
                        <button class="btn btn-primary" wire:click="openCreateModal" data-toggle="modal" data-target="#modalSueldo">
                            <i class="fa fa-plus"></i> Nuevo
                        </button>
                    @endcan

                </div>
            </div>
        </div>
    @endsection

    <div class="container-fluid">
        @php
            $meses_es = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ];
        @endphp

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-12">Filtros</label>
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <select wire:model="filterGestion" class="form-control">
                            <option value="">-- Gestión (Año) --</option>
                            @foreach ($gestiones as $gestion)
                                <option value="{{ $gestion }}">{{ $gestion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <select wire:model="filterMes" class="form-control">
                            <option value="">-- Mes --</option>
                            @foreach ($meses as $mes)
                                <option value="{{ $mes }}">{{ $meses_es[$mes] ?? $mes }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <select wire:model="filterEstado" class="form-control">
                            <option value="">-- Estado --</option>
                            <option value="CREADO">Creado</option>
                            <option value="PROCESADO">Procesado</option>
                            <option value="ANULADO">Anulado</option>
                        </select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2 col-6 d-flex justify-content-between align-items-center">
                        <span>Ver </span>&nbsp;
                        <select wire:model="perPage" class="form-control">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>&nbsp;
                        <span>filas</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-info">
                            <tr class="text-center">
                                <th style="cursor:pointer" wire:click="sortBy('id')">
                                    ID
                                    @if ($sortField === 'id')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                <th class="text-left" style="cursor:pointer" wire:click="sortBy('titulo')">
                                    Titulo
                                    @if ($sortField === 'titulo')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th style="cursor:pointer" wire:click="sortBy('gestion')">
                                    Gestión
                                    @if ($sortField === 'gestion')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th style="cursor:pointer" wire:click="sortBy('mes')">
                                    Mes
                                    @if ($sortField === 'mes')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- <th style="cursor:pointer" wire:click="sortBy('hora')">
                                    Hora
                                    @if ($sortField === 'hora')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th> --}}
                                <th style="cursor:pointer" wire:click="sortBy('estado')">
                                    Estado
                                    @if ($sortField === 'estado')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th style="cursor:pointer; width: 170px" wire:click="sortBy('created_at')">
                                    Creado
                                    @if ($sortField === 'created_at')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th style="cursor:pointer; width: 170px">
                                    Procesado
                                </th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sueldos as $sueldo)
                                <tr class="text-center">
                                    <td>{{ $sueldo->id }}</td>

                                    <td class="text-left">{{ $sueldo->titulo ?: '--' }}</td>
                                    <td>{{ $sueldo->gestion }}</td>
                                    <td>{{ $meses_es[$sueldo->mes] ?? $sueldo->mes }}</td>

                                    {{-- <td>{{ $sueldo->hora }}</td> --}}
                                    {{-- <td>{{ optional($sueldo->user)->name ?? '-' }}</td> --}}
                                    <td>
                                        @if ($sueldo->estado == 'CREADO')
                                            <span class="badge badge-info">Creado</span>
                                        @elseif($sueldo->estado == 'PROCESADO')
                                            <span class="badge badge-success">Procesado</span>
                                        @elseif($sueldo->estado == 'ANULADO')
                                            <span class="badge badge-secondary">Anulado</span>
                                        @else
                                            <span class="badge badge-warning">{{ $sueldo->estado }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sueldo->created_at }}</td>
                                    <td>
                                        @if ($sueldo->estado === 'PROCESADO')
                                            {{ $sueldo->fecha . ' ' . $sueldo->hora }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($sueldo->estado === 'CREADO')
                                            @can('rrhhsueldos.create')
                                                <a href="{{ route('admin.procesarsueldos', $sueldo->id) }}"
                                                    class="btn btn-info btn-sm" title="Procesar">
                                                    <i class="fa fa-cogs"></i>
                                                </a>
                                            @endcan
                                        @elseif ($sueldo->estado === 'PROCESADO')
                                            <a href="{{ route('pdf.sueldos', $sueldo->id) }}"
                                                class="btn btn-primary btn-sm" title="Resumen PDF" target="_blank">
                                                <i class="fa fa-file-pdf px-1"></i>
                                            </a>
                                            <a href="{{ route('pdf.boletas', $sueldo->id) }}"
                                                class="btn btn-success btn-sm" title="Generar Boletas" target="_blank">
                                                <i class="fa fa-file-signature"></i>
                                            </a>
                                        @endif
                                        @if ($sueldo->estado != 'ANULADO')
                                            @can('rrhhsueldos.edit')
                                                <button class="btn btn-warning btn-sm" title="Editar"
                                                    wire:click="openEditModal({{ $sueldo->id }})" data-toggle="modal"
                                                    data-target="#modalSueldo"><i class="fa fa-edit"></i></button>
                                            @endcan
                                        @endif

                                        @can('rrhhsueldos.destroy')
                                            @if ($sueldo->estado == 'ANULADO')
                                                <button class="btn btn-danger btn-sm" title="Eliminar"
                                                    wire:click="confirmDelete({{ $sueldo->id }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        @endcan

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 flex-column flex-md-row">
                    <div>
                        Mostrando {{ $sueldos->firstItem() }} a {{ $sueldos->lastItem() }} de {{ $sueldos->total() }}
                        registros
                    </div>
                    <div>
                        {{ $sueldos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Sueldo -->
    <div wire:ignore.self class="modal fade" id="modalSueldo" tabindex="-1" role="dialog"
        aria-labelledby="modalSueldoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSueldoLabel">
                        @if ($editMode)
                            Editar Sueldo
                        @else
                            Nuevo Sueldo
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="saveSueldo">
                    <div class="modal-body">
                        @if ($editMode)
                            <div class="form-group">
                                <label for="gestion">Titulo</label>
                                <input type="text" wire:model.defer="titulo" class="form-control" id="titulo"
                                    placeholder="Titulo del Registro">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="gestion">Gestión (Año)</label>
                            <input type="number" wire:model.defer="gestion" class="form-control" id="gestion"
                                min="2000" max="2100" required>
                        </div>
                        <div class="form-group">
                            <label for="mes">Mes</label>
                            <select wire:model.defer="mes" class="form-control" id="mes" required>
                                <option value="">Seleccione...</option>
                                @foreach ($meses_es as $num => $nombre)
                                    <option value="{{ $num }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" wire:model.defer="fecha" class="form-control" id="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" wire:model.defer="hora" class="form-control" id="hora"
                                required>
                        </div> --}}

                        @if ($editMode)
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select wire:model.defer="estado" class="form-control" id="estado" required>
                                    <option value="CREADO">Creado</option>
                                    <option value="PROCESADO">Procesado</option>
                                    <option value="ANULADO">Anulado</option>
                                </select>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            wire:click="closeModal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" onclick="cerrarModal()">
                            @if ($editMode)
                                Actualizar
                            @else
                                Guardar
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        function cerrarModal() {
            $('#modalSueldo').modal('hide');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('swal:confirm-delete', function() {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteSueldo');
                    }
                });
            });
        });
    </script>
@endsection
