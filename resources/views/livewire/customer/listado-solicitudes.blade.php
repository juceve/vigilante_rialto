<div>
    @section('title')
        Solicitudes de Propietarios
    @endsection



    <div class="card">
        <div class="card-header  bg-light">
            <div class="d-sm-flex align-items-center justify-content-between">
                <label>
                    LISTADO DE SOLICITUDES DE PROPIETARIOS
                </label>
                <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalFormSolicitud">
                    <i class="fa fa-plus"></i> Nueva Solicitud
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <!-- Buscador -->
                <div class="col-12 col-md-9 col-xl-10">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Ingrese su búsqueda..."
                            wire:model.debounce.500ms="search">
                    </div>
                </div>


                <!-- Selección de filas -->
                <div class="col-12 col-md-3 col-xl-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><small>Filas:</small></span>
                        </div>
                        <select class="form-control text-center" wire:model="perPage">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="table-responsive" wire:ignore.self>
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr class="table-info">
                            <th style="cursor:pointer" wire:click="sortBy('nombre')">
                                Nombre Propietario
                                @if ($sortField == 'nombre')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th style="cursor:pointer" wire:click="sortBy('cedula_propietario')">
                                Cédula
                                @if ($sortField == 'cedula_propietario')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="text-center" style="cursor:pointer" wire:click="sortBy('cantidad')">
                                Cantidad de Solicitudes
                                @if ($sortField == 'cantidad')
                                    <i class="fa fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residencias as $residencia)
                            <tr>
                                <td>{{ $residencia->nombre }}</td>
                                <td>{{ $residencia->cedula_propietario ?? '-' }}</td>
                                <td class="text-center">{{ $residencia->cantidad }}</td>
                                <td class="text-right">

                                        <a href="{{ route('customer.aprobaciones', [
                                            'propietario_id' => $residencia->propietario_id,
                                            'cliente_id' => $cliente_id,
                                        ]) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fa fa-file-text"></i> Revisar
                                        </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No se encontraron registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $residencias->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalFormSolicitud" tabindex="-1" aria-labelledby="modalFormSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalFormSolicitudLabel">Nueva Solicitud</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-link"></i> &nbsp;
                                        Link</span>
                                </div>
                                <input type="text" class="form-control" id="linkInput" wire:model="linkSolicitud"
                                    readonly style="background-color: #fafafa;">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-primary" type="button" title="Copiar enlace"
                                id="copyBtn">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-success" type="button" title="Enviar WhatsApp"
                                id="whatsappBtn">
                                <i class="fa fa-whatsapp"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>
@section('js')
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
