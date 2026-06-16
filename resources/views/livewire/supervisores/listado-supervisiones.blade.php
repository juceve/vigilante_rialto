<div style="margin-top: 95px">
    @section('title')
        Checklists
    @endsection

    <div class="alert alert-secondary" role="alert" style="font-size: 12px;">
        <div class="row">
            <div class="col-2">
                <a href="{{ route('supervisores.panel', $inspeccionActiva->id) }}"
                    class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10">
                <span class="text-secondary">
                    <i class="fas fa-building"></i> <strong> {{ $inspeccionActiva->cliente->nombre }}</strong>
                </span>
            </div>
        </div>

    </div>
    <div class="container d-grid">
        <button class="btn btn-primary mb-3" wire:click='iniciarCuestionario'>INICIAR CHECKLIST <i
                class="fas fa-clipboard"></i></button>
        <div class="card">
            <div class="card-header bg-secondary text-white text-center">
                CHECKLIST EJECUTADOS
            </div>
            <div class="card-body">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="basic-addon1">Del</span>
                    <input type="date" class="form-control" wire:model="fechaInicio">
               &nbsp;
                    <span class="input-group-text" id="basic-addon2">Al</span>
                    <input type="date" class="form-control" wire:model="fechaFin">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 11px;">
                        <thead>
                            <tr class="table-info">
                                <th>FECHA</th>
                                <th>CHECKLIST</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ejecuciones as $item)
                                <tr>
                                    <td class="align-middle">{{$item->fecha}}</td>
                                    <td class="align-middle">{{ $item->chklListaschequeo->titulo }}</td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('supervisores.infocuestionario', [$item->id,$inspeccionActiva->id]) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $ejecuciones->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        Livewire.on('openPreguntaCuestionario', data => {
            let opciones = {};
            data.forEach(item => {
                opciones[item.id] = item.titulo;
            });

            Swal.fire({
                icon: 'info',
                title: 'Selecciona un checklist',
                text: 'Debes elegir un checklist para continuar',
                input: 'select',
                inputOptions: opciones,
                inputPlaceholder: 'Elige una opción...',
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                allowOutsideClick: false,
                inputValidator: value => {
                    return new Promise((resolve) => {
                        if (value) resolve();
                        else resolve('Debes seleccionar un checklist');
                    });
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const cuestionarioId = result.value;
                    // Redirige según tu ruta
                    window.location.href =
                        `../ejecutar-cuestionario/${cuestionarioId}/${@json($inspeccionActiva->id)}`;
                }
            });
        });
    </script>
@endsection
