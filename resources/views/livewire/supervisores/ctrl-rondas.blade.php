<div style="margin-top: 95px">

    @section('title')
        Control de Rondas
    @endsection

    <div class="alert alert-secondary" role="alert" style="font-size: 13px;">
        <div class="row">
            <div class="col-2">
                <a href="{{ route('supervisores.panel', $inspeccionActiva->id) }}"
                    class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10">
                <div class="text-secondary">
                    <h4>
                        <strong>CONTROL DE RONDAS</strong>
                    </h4>
                    <span class="text-secondary">
                        <i class="fas fa-building"></i> <strong>{{ $inspeccionActiva->cliente->nombre }}</strong>
                    </span> <br>
                    <i class="fas fa-user-secret"></i>
                    {{ $inspeccionActiva->designacionsupervisor->empleado->nombres . ' ' . $inspeccionActiva->designacionsupervisor->empleado->apellidos }}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header bg-secondary text-white text-center">
                RONDAS EJECUTADAS
            </div>
            <div class="card-body">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-filter"></i>
                    </span>
                    <input type="date" class="form-control" wire:model="fecha">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" style="font-size: 13px;">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>ID</th>
                                <th>EMPLEADO</th>
                                <th>RONDA</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rondas as $ronda)
                                <tr>
                                    <td class="align-middle text-center"><strong>{{ $ronda->id }}</strong></td>
                                    <td class="align-middle"><strong>{{ $ronda->user->name }}</strong></td>
                                    <td class="align-middle">
                                        {{ $ronda->ronda->nombre }} <br>
                                        <small>
                                            <strong><i class="fas fa-hourglass-start"></i> </strong>
                                            {{ $ronda->inicio }} <br>
                                            <strong><i class="fas fa-hourglass-end"></i> </strong>
                                            {{ $ronda->fin ?? 'En progreso' }}
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-sm btn-warning" title="Ver Info"
                                            wire:click="verInfo({{ $ronda->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="float-end">
                    {{ $rondas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h1 class="modal-title fs-5" id="modalInfoLabel">Datos de la Ronda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selRonda)
                        @if ($modalUrl)
                            <iframe id="iframeModal" src="{{ $modalUrl }}"
                                style="width:100%; height:80vh; border:none;"></iframe>
                        @endif
                    @endif
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

            const modalEl = document.getElementById('modalInfo');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('modalInfo');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>
@endsection
