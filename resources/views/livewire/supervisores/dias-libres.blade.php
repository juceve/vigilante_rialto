<div style="margin-top: 95px">
    @section('title')
        Dias Libres
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
                    <h4><strong>DÍAS LIBRES</strong></h4>
                    <span class="text-secondary">
                        <i class="fas fa-building"></i>
                        <strong>{{ $inspeccionActiva->cliente->nombre }}</strong>
                    </span><br>
                    <i class="fas fa-user-secret"></i>
                    {{ $inspeccionActiva->designacionsupervisor->empleado->nombres . ' ' . $inspeccionActiva->designacionsupervisor->empleado->apellidos }}
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <small class="text-secondary">Filtrar</small>
        <div class="row g-1 mb-3">
            <div class="col-8">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                    <select class="form-select" wire:model="mes">
                        <option value="">Seleccione un mes</option>
                        @foreach ([1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'] as $num => $nombre)
                            <option value="{{ $num }}">{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <select class="form-select form-select-sm" wire:model="gestion">
                    @foreach ($gestiones as $anio)
                        <option value="{{ $anio }}">{{ $anio }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-secondary text-white text-center">CRONOGRAMA MENSUAL</div>
            <div class="card-body">

                <div class="card shadow-sm mb-3">
                    {{-- Header del mes --}}
                    <div class="card-header text-center fw-bold bg-secondary text-white py-2">
                        {{ \Carbon\Carbon::create($gestion, $mes)->translatedFormat('F Y') }}
                    </div>

                    {{-- Cabecera días de la semana --}}
                    <div class="calendar-grid fw-bold text-center small mb-1">
                        @foreach (['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $d)
                            <div>{{ $d }}</div>
                        @endforeach
                    </div>


                    {{-- Cuerpo del calendario --}}
                    @php
                        $inicioMes = \Carbon\Carbon::create($gestion, $mes, 1);
                        $finMes = $inicioMes->copy()->endOfMonth();
                        $primerDiaSemana = $inicioMes->dayOfWeekIso; // 1=Lunes ... 7=Domingo
                        $totalDias = $finMes->day;
                        $dias = [];
                        for ($i = 1; $i <= $totalDias; $i++) {
                            $dias[] = $i;
                        }
                        $contador = 0;
                    @endphp
                    @php
                        $inicioMes = \Carbon\Carbon::create($gestion, $mes, 1);
                        $finMes = $inicioMes->copy()->endOfMonth();
                        $primerDiaSemana = $inicioMes->dayOfWeekIso; // 1-7
                    @endphp

                    <div class="calendar-grid">
                        {{-- Vacíos iniciales --}}
                        @for ($i = 1; $i < $primerDiaSemana; $i++)
                            <div></div>
                        @endfor

                        {{-- Días del mes --}}
                        @for ($dia = 1; $dia <= $finMes->day; $dia++)
                            @php
                                $fecha = \Carbon\Carbon::createFromDate($gestion, $mes, $dia)->format('Y-m-d');
                                $tieneLibre = isset($diasLibresPorFecha[$fecha]);
                            @endphp

                            <div class="calendar-cell {{ $tieneLibre ? 'warning' : '' }}"
                                wire:key="dia-{{ $fecha }}" wire:click="abrirDiaModal('{{ $fecha }}')">
                                <div class="fw-bold">{{ $dia }}</div>
                            </div>
                        @endfor
                    </div>

                </div>



            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="modalInfo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-secondary">
                    <h6 class="modal-title">
                        Día libre – {{ \Carbon\Carbon::parse($diaModal)->format('d/m/Y') }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @php
                        $hoy = date('Y-m-d');
                    @endphp
                    @if ($diaModal >= $hoy)
                        <small><strong>Nuevo Dia Libre</strong></small>
                        <div class="row g-1">
                            <div class="col-12 col-md-4 mb-2">
                                <select class="form-select" wire:model="selDesignacione">
                                    <option value="">Seleccione un empleado</option>
                                    @foreach ($designaciones as $item)
                                        <option value="{{ $item['designacione_id'] }}">{{ $item['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <input type="text" class="form-control" placeholder="Descripción corta"
                                    wire:model.lazy="observaciones">
                            </div>
                            <div class="col-12 col-md-4 mb-md-2 d-grid">
                                <button class="btn btn-primary" wire:click="addDiaLibre">Agregar <i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <hr>
                    @endif

                    @forelse ($detalleDiaModal as $dl)
                        <div class="border rounded p-2 mb-2">
                            <div class="alert alert-primary" role="alert">
                                <div class="fw-bold">
                                    {{ $dl->designacione->empleado->nombres }}
                                    {{ $dl->designacione->empleado->apellidos }}
                                </div>
                                <div class="small text-muted">
                                    {{ $dl->observaciones ?? 'Sin observaciones' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center small">
                            Sin información
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@section('css')
    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-cell {
            min-height: 60px;
            border: 1px solid #dee2e6;
            text-align: center;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }

        .calendar-cell.warning {
            background-color: #ffc107;
        }
    </style>
@endsection
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
