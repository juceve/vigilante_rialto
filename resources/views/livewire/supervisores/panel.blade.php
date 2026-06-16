<div style="margin-top: 95px">
    @section('title')
        Panel de Control
    @endsection

    <div class="alert alert-secondary" role="alert">
        <span class="text-dark" style="margin-left: 1rem"> <i class="fas fa-shield"></i>
            <strong>{{ Auth::user()->name }}</strong></span> <br>
        <span class="text-muted" style="margin-left: 1rem"> <i class="fas fa-building" style="font-size: 13px;"></i> <small
                style="font-size: 13px;"><strong>{{ $inspeccionActiva->cliente->nombre }}</strong></small></span>
    </div>
    <div class="card shadow mb-5 bg-body-tertiary rounded" style="width: 92%; margin-left: 1rem">
        <div class="card-header text-center text-white bg-secondary">
            <strong>PANEL DE CONTROL</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-4 d-grid mb-3">
                    <a href="{{ route('supervisores.listadosupervisiones', $inspeccionActiva->id) }}"
                        class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4">

                        <i class="far fa-clipboard" style="font-size: 30px;"></i> <br>
                        <small>Checklist</small>

                    </a>
                    {{-- <button class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4"
                        wire:click='iniciarCuestionario'>

                        <i class="far fa-clipboard" style="font-size: 30px;"></i> <br>
                        <small>Supervisión</small>

                    </button> --}}
                </div>
                <div class="col-6 col-md-4 d-grid mb-3">
                    <a href="{{ route('supervisores.listadoboletas', $inspeccionActiva->id) }}"
                        class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4 ">

                        <i class="fas fa-receipt" style="font-size: 30px;"></i> <br>
                        <small>Boletas</small>

                    </a>
                </div>
                <div class="col-6 col-md-4 d-grid mb-3">
                    <a href="{{ route('supervisores.ctrlasistencia', $inspeccionActiva->id) }}"
                        class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4 ">

                        <i class="fas fa-user-clock" style="font-size: 30px;"></i> <br>
                        <small>Asistencias</small>

                    </a>
                </div>
                <div class="col-6 col-md-4 d-grid mb-3">
                    <a href="{{ route('supervisores.ctrlrondas', $inspeccionActiva->id) }}" class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4">

                        <i class="fas fa-street-view" style="font-size: 30px;"></i> <br>
                        <small>Rondas</small>

                    </a>
                </div>

                <div class="col-6 col-md-4 d-grid mb-3">
                    <a href="{{ route('supervisores.diaslibres', $inspeccionActiva->id) }}"
                        class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4">

                        <i class="fas fa-calendar-check" style="font-size: 30px;"></i> <br>
                        <small>Diás Libres</small>

                    </a>
                </div>
                <div class="col-6 col-md-4 d-grid mb-3">
                      <a href="{{ route('supervisores.novedades', $inspeccionActiva->id) }}"
                        class="btn btn-outline-secondary shadow bg-body-tertiary rounded py-4">

                        <i class="fas fa-newspaper" style="font-size: 30px;"></i> <br>
                        <small>Novedades</small>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container d-grid">
        <button class="btn btn-secondary text-warning py-3" onclick="finalizarInsp()" wire:loading.attr="disabled">

            <div wire:loading wire:target="finalizarInspeccionActiva">
                Procesando... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </div>
            <div wire:loading.remove wire:target="finalizarInspeccionActiva">
                Finalizar Inspección <i class="fas fa-sign-out-alt"></i>
            </div>



        </button>
    </div>
</div>
@section('js')
    <script>
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
    <script>
        Livewire.on('openPreguntaCuestionario', data => {
            let opciones = {};
            data.forEach(item => {
                opciones[item.id] = item.titulo;
            });

            Swal.fire({
                icon: 'info',
                title: 'Selecciona un cuestionario',
                text: 'Debes elegir un cuestionario para continuar',
                input: 'select',
                inputOptions: opciones,
                inputPlaceholder: 'Elige una opción...',
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                allowOutsideClick: false,
                inputValidator: value => {
                    return new Promise((resolve) => {
                        if (value) resolve();
                        else resolve('Debes seleccionar un cuestionario');
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
