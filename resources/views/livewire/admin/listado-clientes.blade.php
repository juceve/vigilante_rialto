<div>
    <div class="row">
        <div class="col-12 col-md-10">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Ingrese su busqueda..."
                    wire:model.debounce.500ms='busqueda'>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><small>Filas: </small></span>
                </div>
                <select class="form-control text-center" wire:model='filas'>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

    </div>
    <div class="table">
        <table class="table table-striped table-hover">
            <thead class="thead">
                <tr class="table-info">
                    <th>No</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Oficina</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                <tr 
                @if (!tieneDesignacionesCliente($cliente->id))
                    class='table-danger' title="Cliente sin designaciones activas"
                @endif
                >
                    <td>{{ ++$i }}</td>

                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    <td>{{ $cliente->oficina }}</td>
                    <td>
                        @if ($cliente->status)
                        <span class="badge badge-pill badge-success">Activo</span>
                        @else
                        <span class="badge badge-pill badge-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                data-toggle="dropdown">Opciones</button>
                            {{-- <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false"> --}}
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">

                                <a class="dropdown-item" href="{{ route('clientes.show', $cliente->id) }}"><i
                                        class="fa fa-fw fa-eye text-secondary"></i> Info</a>
                                @can('clientes.edit')
                                <a class="dropdown-item" href="{{ route('clientes.edit', $cliente->id) }}"><i
                                        class="fa fa-fw fa-edit text-secondary"></i> Editar</a>

                                <a class="dropdown-item" href="{{ route('usuariocliente', $cliente->id) }}">
                                    <i class="fas fa-user-plus text-secondary"></i> Usuario externo</a>
                                @endcan
                                @can('residencias.index')
                                <a class="dropdown-item" href="{{ route('admin.residencias', $cliente->id) }}">
                                    <i class="fas fa-home text-secondary"></i> Residencias</a>
                                @endcan
                                @can('residencias.solicitudes')
                                <a class="dropdown-item" href="javascript:void(0)"
                                    wire:click="generaLink({{ $cliente->id }})">
                                    <i class="fas fa-plus text-secondary"></i> Form. Propietarios</a>
                                @endcan
                                @can('admin.clientes.dotaciones.index')
                                <a class="dropdown-item" href="{{ route('admin.clientes.dotaciones', $cliente->id) }}">
                                    <i class="fas fa-boxes text-secondary"></i>
                                    Dotaciones
                                </a>
                                @endcan
                                @can('admin.registros.rondas')
                                <a class="dropdown-item" href="{{ route('clientes.rondas', $cliente->id) }}">
                                    <i class="fas fa-street-view text-secondary"></i>
                                    Rondas
                                </a>
                                @endcan


                                @can('turnos.index')
                                <a class="dropdown-item" href="{{ route('admin.turnos-cliente', $cliente->id) }}"><i
                                        class="fas fa-clock text-secondary"></i> Turnos</a>
                                @endcan
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                    class="delete" onsubmit="return false">
                                    @csrf
                                    @method('DELETE')
                                    @can('clientes.destroy')
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
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $clientes->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalFormSolicitud" tabindex="-1" aria-labelledby="modalFormSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalFormSolicitudLabel">Nueva Solicitud</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">
                                <i class="fas fa-link"></i> &nbsp;
                                Link
                            </span>
                        </div>
                        <input type="text" class="form-control" aria-label="Sizing example input"
                            style="background-color: #fafafa;" aria-describedby="inputGroup-sizing-default"
                            id="linkInput" wire:model="linkSolicitud" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" title="Copiar enlace" id="copyBtn">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="button" title="Enviar WhatsApp"
                                id="whatsappBtn">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    Livewire.on('abrirModalLink', () => {
            $('#modalFormSolicitud').modal('show');
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copyBtn');
            const whatsappBtn = document.getElementById('whatsappBtn');
            const linkInput = document.getElementById('linkInput');

            // Copiar al portapapeles
            copyBtn.addEventListener('click', function() {
                linkInput.select();
                linkInput.setSelectionRange(0, 99999); // Para móviles
                navigator.clipboard.writeText(linkInput.value)
                    .then(() => {
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Enlace copiado al portapapeles',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                    })
                    .catch(err => {
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'error',
                            title: 'Error al copiar el enlace',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
            });

            // Abrir WhatsApp
            whatsappBtn.addEventListener('click', function() {
                const url = encodeURIComponent(linkInput.value);
                const whatsappURL = `https://wa.me/?text=${url}`;
                window.open(whatsappURL, '_blank');
            });
        });
</script>
@endsection