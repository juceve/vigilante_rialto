<div style="margin-top: 95px">
    @section('title')
        Control de Asistencias
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
                        <strong>CONTROL DE ASISTENCIA</strong>
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
                ESTADO ACTUAL EN MARCACIONES
            </div>
            {{-- @dump($empleados) --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="bg-primary text-white">
                            <tr >
                                <th>EMPLEADO</th>
                                <th class="text-center">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designaciones as $designacione)
                                <tr wire:click="selectEmpleado({{ $designacione->id }})">
                                    <td>

                                        {{ $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos}}
                                        <br>
                                        <span style="font-size: 12px;">
                                            <strong>
                                                {{ $designacione->turno->nombre }}
                                                [{{ $designacione->turno->horainicio }} -
                                                {{ $designacione->turno->horafin }}]
                                            </strong>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $result = yaMarque2($designacione->id);
                                        @endphp
                                        @switch($result[0])
                                        @case(0)
                                        <span class="badge rounded-pill text-bg-danger">Sin <br> Marcado</span>
                                        @break
                                        @case(1)
                                        <span class="badge rounded-pill text-bg-success">
                                            {{substr($result[1],0,10)}} <br>
                                            {{substr($result[1],11)}}
                                        </span>
                                        @break
                                        @case(2)
                                        <span class="badge rounded-pill text-bg-primary">Finalizado <br> correcto</span>
                                        @break
                                        @case(3)
                                        <span class="badge rounded-pill text-bg-secondary">Pendiente</span>
                                        @break
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-grid mt-3">
             <a href="{{ route('supervisores.panel', $inspeccionActiva->id) }}"
                    class="btn btn-secondary d-flex align-items-center justify-content-center">
                    <i class="fas fa-arrow-left"></i> Volver
                </a> <br>
        </div>
    </div>

    <div class="modal fade" id="modalInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    INFORMACIÓN DEL EMPLEADO
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if ($selEmpleado)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td ><strong>Nombre:</strong></td>
                                        <td class="align-middle">
                                            {{ $selEmpleado->empleado->nombres . ' ' . $selEmpleado->empleado->apellidos }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td ><strong>Establecimiento:</strong></td>
                                        <td class="align-middle">{{ $selEmpleado->turno->cliente->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle"><strong>Turno:</strong></td>
                                        <td class="align-middle">{{ $selEmpleado->turno->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle"><strong>Ingreso:</strong></td>
                                        <td class="align-middle">{{ $selEmpleado->turno->horainicio }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle"><strong>Salida:</strong></td>
                                        <td class="align-middle">{{ $selEmpleado->turno->horafin }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle"><strong>Telefono:</strong></td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                {{ $selEmpleado->empleado->telefono }}
                                                <a href="https://wa.me/591{{ $selEmpleado->empleado->telefono }}"
                                                    target="_blank" class="btn btn-sm btn-success float-end"><i
                                                        class="fab fa-whatsapp"></i>
                                                    Contactar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
