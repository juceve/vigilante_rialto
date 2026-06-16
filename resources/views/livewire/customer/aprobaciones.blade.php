<div>
    <div class="container-fluid mb-3 text-dark">
        <div class="content">
            <h4>Residencias pendientes de Aprobación
                <div class="float-right">
                    <a href="{{ route('admin.listadosolicitudes', $cliente_id) }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>
            </h4>
            <strong>Propietario: </strong>{{ $propietario->nombre }} <br>
            <strong>Cedula: </strong>{{ $propietario->cedula }} <br>
            <strong>Establecimiento: </strong>{{ $cliente->nombre }}
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <span class="card-title">Listado de Residencias sin aprobación</span>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Nro. Puerta</th>
                        <th>Notas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residencias as $residencia)
                        <tr class="text-center">
                            <td>{{ $residencia->id }}</td>
                            <td>{{ $residencia->numeropuerta ?? '-' }}</td>
                            <td>{{ $residencia->notas ?? '-' }}</td>
                            <td><span class="badge bg-warning">{{ $residencia->estado }}</span></td>
                            <td>
                                <button wire:click="revisar({{ $residencia->id }})" class="btn btn-sm btn-info">
                                    Revisar <i class="fa fa-search"></i>
                                </button>
                                <button onclick="eliminar({{ $residencia->id }})" class="btn btn-sm btn-danger">
                                    Eliminar <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay residencias pendientes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-success text-white">
            <span class="card-title">Historial de Residencias VERIFICADAS</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Nro. Puerta</th>
                            <th>Piso</th>
                            <th>Calle</th>
                            <th>Nro Lote</th>
                            <th>Manzano</th>
                            <th>Notas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($propietario->residencias->where('estado', 'VERIFICADO') as $verificado)
                            <tr class="text-center">
                                <td>{{ $verificado->id }}</td>
                                <td>{{ $verificado->numeropuerta ?? '-' }}</td>
                                <td>{{ $verificado->notas ?? '-' }}</td>
                                <td>{{ $verificado->piso ?? '-' }}</td>
                                <td>{{ $verificado->calle ?? '-' }}</td>
                                <td>{{ $verificado->nrolote ?? '-' }}</td>
                                <td>{{ $verificado->manzano ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-pill badge-success">{{ $verificado->estado }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal && $residenciaSeleccionada)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Revisar Residencia</h5>
                        <button type="button" class="close" wire:click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>ID</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->id }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Cliente</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->cliente->nombre }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Propietario</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->propietario->nombre ?? 'Sin vínculo' }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Cédula</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->cedula_propietario ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Nro. Puerta</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->numeropuerta ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Piso</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->piso ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Calle</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->calle ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Nro. Lote</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->nrolote ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Manzano</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->manzano ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Notas</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->notas ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>Estado</strong></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ $residenciaSeleccionada->estado }}" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="cerrarModal"><i class="fa fa-ban"></i>
                            Cerrar</button>
                        <button class="btn btn-success"onclick="aprobar()">Aprobar <i
                                class="fa fa-check"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('js')
    <script>
        function aprobar() {
            Swal.fire({
                title: 'Aprobar Solicitud',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('aprobar');
                }
            })
        }
        function eliminar(residencia_id) {
            Swal.fire({
                title: 'Eliminar Solicitud',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminalo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminar', residencia_id);
                }
            })
        }
    </script>
@endsection
