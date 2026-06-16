<div class="container-fluid px-3 py-2" style="margin-top: 110px;">
    @section('title', 'Mis Boletas')
    <!-- Header / back -->
    <div class="d-flex align-items-center mb-3">
        <a class="btn btn-link p-0 me-2" href="{{ route('vigilancia.profile') }}" aria-label="Volver">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h4 class="mb-0">Mis Boletas</h4>

    </div>

     <!-- Profile card -->
    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <div class="flex-shrink-0 me-3">
                    @php
                    $avatar = $empleado->avatar ?? null;
                    $placeholder = 'https://ui-avatars.com/api/?name=' . urlencode($empleado->nombres.'
                    '.$empleado->apellidos ?? 'Empleado') . '&background=0D6EFD&color=fff&rounded=true&size=128';
                    $avatarUrl = $empleado->imgperfil ? asset('storage/'.$empleado->imgperfil) : $placeholder;
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle"
                        style="width:84px;height:84px;object-fit:cover;">
                </div>

                <!-- Info -->
                <div class="flex-grow-1">
                    <h5 class="mb-0 text-primary">{{ $empleado->nombres ?? 'Nombre' }} {{ $empleado->apellidos ??
                        'Apellidos' }}</h5>
                    <small class="text-blue d-block mb-2"><strong>{{ $empleado->area->nombre ?? 'Cargo / Puesto'
                            }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-grid">
            <h5 class="card-title text-center text-muted">Boletas recibidas</h5>
            <div class="table-responsive" wire:ignore>
                <table class="table table-striped table-nowrap" style="font-size: 11px">
                    <thead class="bg-success text-white">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>FECHA</th>
                            <th>TIPO</th>
                            <th>DESC.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boletas as $boleta)
                            <tr>
                                <td class="align-middle text-center">{{ $boleta->id }}</td>
                                <td class="align-middle text-center text-nowrap">
                                    {{ Str::substr($boleta->fechahora, 0, 10) }}
                                    <br>
                                    {{ Str::substr($boleta->fechahora, 11) }}
                                </td>
                                <td class="align-middle text-center">{{ $boleta->tipoboleta->nombre }}</td>
                                <td class="align-middle text-center">{{ numDecimal($boleta->descuento) }}</td>
                                <td class="align-middle text-end">
                                    <button class="btn btn-sm btn-info" wire:click='verInfo({{ $boleta->id }})'><i
                                            class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No existen registros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $boletas->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalles" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Datos de la Boleta </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        @if ($selBoleta)
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="text-end"><strong>Empleado:</strong></td>
                                        <td>
                                            {{ $selBoleta->empleado->nombres . ' ' . $selBoleta->empleado->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Emitido:</strong></td>
                                        <td>
                                            {{ $selBoleta->fechahora }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Lugar:</strong></td>
                                        <td>
                                            {{ $selBoleta->cliente->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Detalles:</strong></td>
                                        <td>
                                            {{ $selBoleta->detalles }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Tipo:</strong></td>
                                        <td>
                                            {{ $selBoleta->tipoboleta->nombre }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Descuento:</strong></td>
                                        <td>
                                            Bs. {{ $selBoleta->descuento }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Supervisor:</strong></td>
                                        <td>
                                            {{ $selBoleta->supervisor->nombres . ' ' . $selBoleta->supervisor->apellidos }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-ban"></i>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>



</div>
@section('js')
    <script>
        Livewire.on('openModal', () => {
            var myModal = new bootstrap.Modal(document.getElementById('modalDetalles'), {
                keyboard: false
            });
            myModal.show();
        });
    </script>
@endsection
