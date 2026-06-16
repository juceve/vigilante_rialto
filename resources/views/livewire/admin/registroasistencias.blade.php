<div>
    @section('title')
        Asistencias Web
    @endsection
    @section('content_header')
        <h4>Asistencias Web</h4>
    @endsection

    <!-- Tabla -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Registro de Asistencias</h3>
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <!-- Cliente -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select wire:model="cliente_id" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Fecha Inicio -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="date" wire:model="fechaInicio" class="form-control">
                    </div>
                </div>

                <!-- Fecha Fin -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input type="date" wire:model="fechaFin" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tipo Desig.</label>
                        <select wire:model="tipo_designacion" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-9">
                    <!-- Buscador -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" wire:model.debounce.500ms="empleado"
                            placeholder="Buscar por empleado">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <!-- Controles de paginación -->
                    <div class="d-flex justify-content-between align-items-center float-left float-md-right mb-3">

                        Mostrar &nbsp;
                        <select wire:model="perPage" class="form-control form-control-sm d-inline-block"
                            style="width: auto;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        &nbsp;filas

                    </div>
                </div>
            </div>


            <table class="table table-bordered table-hover text-center">
                <thead class="table-info">
                    <tr>
                        <th class="text-left" style="width: 250px; vertical-align: middle;">EMPLEADO</th>
                        @foreach ($dias as $dia)
                            <th style="font-size: 12px; vertical-align: middle;">
                                {{ strtoupper(str_replace('.', '', $dia->locale('es')->isoFormat('ddd DD MMM YY'))) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($designaciones as $desig)
                        <tr>
                            <td class="text-left">
                                <b>{{ $desig->empleado->nombres . ' ' . $desig->empleado->apellidos }}</b><br>
                                <small class="text-success"><strong>Empresa:
                                        {{ $desig->turno->cliente->nombre ?? 'Sin empresa' }}</strong></small> <br>
                                @php
                                    $dl = $desig->designaciondias;
                                @endphp
                                <small><span class="text-primary">Días Laborales:</span> [
                                    {{ $dl->lunes ? 'Lu ' : '' }}
                                    {{ $dl->martes ? 'Ma ' : '' }}
                                    {{ $dl->miercoles ? 'Mi ' : '' }}
                                    {{ $dl->jueves ? 'Ju ' : '' }}
                                    {{ $dl->viernes ? 'Vi ' : '' }}
                                    {{ $dl->sabado ? 'Sa ' : '' }}
                                    {{ $dl->domingo ? 'Do ' : '' }}
                                    ]
                                </small> <br>
                                <small class="text-info">Turno: {{ $desig->turno->nombre ?? '' }}</small>
                                <small>[{{ $desig->turno->horainicio ?? '' }} -
                                    {{ $desig->turno->horafin ?? '' }}]</small>




                            </td>

                            @foreach ($dias as $dia)
                                @php
                                    $asis = \App\Models\Asistencia::where('designacione_id', $desig->id)
                                        ->whereDate('fecha', $dia->toDateString())
                                        ->first();

                                    $diaLibre = $desig->dialibres->firstWhere('fecha', $dia->toDateString());

                                    $permiso = \App\Models\Rrhhpermiso::where('empleado_id', $desig->empleado_id)
                                        ->whereDate('fecha_inicio', '<=', $dia->toDateString())
                                        ->whereDate('fecha_fin', '>=', $dia->toDateString())
                                        ->where('status', 'APROBADO')
                                        ->first();

                                    $fueraRango =
                                        $dia->lt(Carbon\Carbon::parse($desig->fechaInicio)) ||
                                        $dia->gt(Carbon\Carbon::parse($desig->fechaFin));

                                    $horaActual = \Carbon\Carbon::now();
                                    $horaInicioTurno = \Carbon\Carbon::parse($desig->turno->horainicio ?? '00:00');
                                    $horaFinTurno = \Carbon\Carbon::parse($desig->turno->horafin ?? '23:59');

                                    $badges = [];
                                    $toleranciaIngreso = $horaInicioTurno->copy()->addMinutes(5); // 5 min de tolerancia

                                    if ($fueraRango) {
                                        $badges[] = [
                                            'class' => 'badge-secondary',
                                            'texto' => 'S/D',
                                            'tooltip' => 'Sin Designación',
                                        ];
                                    } elseif ($permiso) {
                                        $badges[] = [
                                            'class' => 'badge-primary',
                                            'texto' => 'PERMISO',
                                            'tooltip' => 'Permiso Aprobado: ' . ($permiso->motivo ?? ''),
                                        ];
                                    } elseif ($diaLibre) {
                                        $badges[] = [
                                            'class' => 'badge-info',
                                            'texto' => 'Libre',
                                            'tooltip' => 'Día Libre: ' . ($diaLibre->observaciones ?? ''),
                                        ];
                                    } elseif ($asis) {
                                        // Ingreso
                                        if ($asis->ingreso) {
                                            $horaIngreso = Carbon\Carbon::parse($asis->ingreso);
                                            if ($horaIngreso->lte($toleranciaIngreso)) {
                                                $badgeClass = 'badge-success';
                                                $tooltip = 'Ingreso a tiempo (' . $horaIngreso->format('H:i') . ')';
                                            } else {
                                                $badgeClass = 'badge-warning';
                                                $tooltip = 'Ingreso tarde (' . $horaIngreso->format('H:i') . ')';
                                            }
                                            $badges[] = [
                                                'class' => $badgeClass,
                                                'texto' => 'I: ' . $horaIngreso->format('H:i'),
                                                'tooltip' => $tooltip,
                                            ];
                                        }

                                        // Salida
                                        if ($asis->salida) {
                                            $horaSalida = Carbon\Carbon::parse($asis->salida);

                                            // Comparar solo la hora del turno con la hora de salida
                                            $horaFinTurnoSoloHora = $horaFinTurno->format('H:i');
                                            $horaSalidaSoloHora = $horaSalida->format('H:i');

                                            if ($horaSalidaSoloHora >= $horaFinTurnoSoloHora) {
                                                $badgeClass = 'badge-info';
                                                $tooltip = 'Salida correcta (' . $horaSalida->format('H:i') . ')';
                                            } else {
                                                $badgeClass = 'badge-warning';
                                                $tooltip =
                                                    'Salida antes de tiempo (' . $horaSalida->format('H:i') . ')';
                                            }

                                            $badges[] = [
                                                'class' => $badgeClass,
                                                'texto' => 'S: ' . $horaSalida->format('H:i'),
                                                'tooltip' => $tooltip,
                                            ];
                                        }
                                    } else {
                                        // Sin marcación
                                        if ($dia->isToday() && $horaActual->lt($horaInicioTurno)) {
                                            $badges[] = [
                                                'class' => 'badge-secondary',
                                                'texto' => 'S/M',
                                                'tooltip' => 'Aún no ha comenzado su horario laboral',
                                            ];
                                        } elseif ($dia->lt($horaActual) || $dia->eq($horaActual->toDateString())) {
                                            // Día activo pasado o día actual después de hora de inicio
                                            $badges[] = [
                                                'class' => 'badge-danger',
                                                'texto' => 'S/M',
                                                'tooltip' => 'Sin marcación',
                                            ];
                                        } else {
                                            // Días futuros
                                            $badges[] = ['class' => '', 'texto' => '-', 'tooltip' => ''];
                                        }
                                    }
                                @endphp

                                <td style="vertical-align: middle;">
                                    @foreach ($badges as $b)
                                        <span class="badge {{ $b['class'] }}" data-toggle="tooltip"
                                            title="{{ $b['tooltip'] }}">
                                            {{ $b['texto'] }}
                                        </span>
                                    @endforeach

                                    {{-- Botón marcado manual --}}
                                    @if ($dia->isToday() && !$fueraRango && !$diaLibre && !$permiso)
                                        <div class="mt-1">
                                            @if (!$asis || !$asis->ingreso)
                                                @if ($horaActual->gte($horaInicioTurno))
                                                    <button class="btn btn-sm btn-primary"
                                                        wire:click="abrirMarcadoManual({{ $desig->id }}, 'ingreso')">Marcar
                                                        Ingreso</button>
                                                @endif
                                            @elseif (!$asis->salida && $this->puedeMarcarSalida($desig, $asis))
                                                @if ($horaActual->gte($horaFinTurno))
                                                    <button class="btn btn-sm btn-secondary"
                                                        wire:click="abrirMarcadoManual({{ $desig->id }}, 'salida')">Marcar
                                                        Salida</button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($dias) + 1 }}" class="text-center text-muted">No se encontraron
                                registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="float-left mt-3">
                <button class="btn btn-danger" wire:click='pdf'>Exportar <i class="fas fa-file-pdf"></i></button>
            </div>
            <div class="float-right mt-3">
                {{ $designaciones->links() }}
            </div>
        </div>
    </div>

    <!-- Modal marcado manual -->
    <div wire:ignore.self class="modal fade" id="marcadoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Marcado Manual: {{ $marcadoEmpleado->nombres ?? '' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Hora {{ ucfirst($marcadoTipo) }}</label>
                    <input type="time" class="form-control" wire:model="marcadoHora">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarMarcadoManual">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('mostrar-modal', event => {
                $('#marcadoModal').modal('show');
            });
            window.addEventListener('cerrar-modal', event => {
                $('#marcadoModal').modal('hide');
            });

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        Livewire.on('renderizarpdf', () => {
            var win = window.open("../pdf/planilla-asistencias", '_blank');
            win.focus();
        });
    </script>
@endsection
