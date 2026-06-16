<div>

    @section('title')
        INSPECCIONES
    @endsection

    <div class="table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-success text-white text-center">
                    <th colspan="2">
                        CLIENTES ASIGNADOS
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($designaciones->designacionsupervisorclientes as $item)

                    <tr>
                        <td class="align-middle">
                            <strong>{{ $item->cliente->nombre }}</strong>
                            <br>
                            @php
                                $ult = ultInspeccion($item->cliente_id);
                            @endphp
                            @if ($ult)
                                <span style="font-size: 11px;">Última revisión:</span>
                                <span class="badge rounded-pill text-bg-warning" style="font-size: 11px;">
                                    {{ $ult->fin }}
                                </span>
                            @endif

                        </td>
                        <td class="align-middle text-end">
                            @if ($inspeccionActiva)
                                @if ($inspeccionActiva->cliente_id === $item->cliente_id)
                                    <a href="{{route('supervisores.panel', $inspeccionActiva->id)}}" class="btn btn-info text-white"
                                        style="width: 100px">
                                        <i class="fas fa-arrow-right"></i> <br> Continuar
                                    </a>
                                @else
                                    <button class="btn btn-primary text-white" style="width: 100px"
                                        onclick="iniciarInsp({{ $item->cliente_id }})">
                                        <i class="fas fa-user-secret"></i> <br> Iniciar
                                    </button>
                                @endif
                            @else
                                <button class="btn btn-primary text-white" style="width: 100px"
                                    onclick="iniciarInsp({{ $item->cliente_id }})">
                                    <i class="fas fa-user-secret"></i> <br> Iniciar
                                </button>
                            @endif




                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>


</div>
@section('js')
    <script>
        function iniciarInsp(cliente_id) {
            Swal.fire({
                title: 'Iniciar Inspección',
                text: "¿Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1abc9c',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Sí, iniciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('iniciarInspeccion', cliente_id);
                }
            });
        }
        function finalizarInsp(cliente_id) {
            Swal.fire({
                title: 'Finalizar Inspección',
                text: "¿Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1abc9c',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Sí, iniciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('finalizar');
                }
            });
        }
    </script>
@endsection
