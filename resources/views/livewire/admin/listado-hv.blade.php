
<div>
    @section('title', 'Registros Hombre Vivo')
    <style>
        .hv-box{width:48px;height:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;border:1px solid #dee2e6;background:#ffffff;margin:0 auto}
        .hv-box .hv-text{font-weight:600;font-size:12px;color:#212529;line-height:1}
        .hv-box.hv-success{background:#d4edda;border-color:#c3e6cb}
        .hv-box.hv-warning{background:#fff3cd;border-color:#ffeeba}
        .hv-box.hv-secondary{background:#e2e3e5;border-color:#d6d8db;color:#383d41}
        .hv-box.hv-danger{background:#f8d7da;border-color:#f5c6cb}
        .hv-box.hv-neutral{background:#fff;border-color:#dee2e6}
        .hv-table td, .hv-table th{padding:4px 6px!important;border-collapse:collapse;vertical-align:middle;text-align:center}
        .hv-table th{padding:6px 4px!important}
        .hv-table td.day-cell{padding:0!important;width:48px;min-width:48px;max-width:48px}
        .hv-table td.employee-cell{padding-left:8px;padding-right:8px;text-align:left}
        @media (max-width:768px){.hv-box{width:44px;height:34px}.hv-box .hv-text{font-size:11px}.hv-table td.day-cell{width:44px;min-width:44px;max-width:44px}}
    </style>
    <div class="container-fluid">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Registro de Marcaciones Hombre Vivo</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cliente</label>
                            <select class="form-control" wire:model="cliente_id">
                                <option value="">-- Todos --</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Empleado</label>
                            <select class="form-control" wire:model="empleado_id" @if (!$cliente_id) disabled @endif>
                                <option value="">-- Todos --</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombres }} {{ $empleado->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" wire:model.defer="fecha_inicio" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" wire:model.defer="fecha_fin" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-primary btn-block" wire:click="generarReporte" wire:loading.attr="disabled">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="limpiar">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        @if ($mostrarResultados && count($resultados) > 0)
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @php
                            \Carbon\Carbon::setLocale('es');
                            $fechaI = \Carbon\Carbon::parse($fecha_inicio);
                            $fechaF = \Carbon\Carbon::parse($fecha_fin);
                            $dias = [];
                            $current = $fechaI->copy();
                            while ($current <= $fechaF) {
                                $dias[] = [
                                    'fecha' => $current->format('Y-m-d'),
                                    'dia' => $current->format('d'),
                                    'mes' => mb_substr($current->translatedFormat('M'), 0, 3),
                                    'diaNombre' => mb_substr($current->translatedFormat('l'), 0, 3),
                                ];
                                $current->addDay();
                            }
                        @endphp

                        <table class="table hv-table table-sm table-bordered table-striped table-hover mb-0">
                            <thead class="table-info">
                                <tr>
                                    <th class="align-middle">Empleado</th>
                                    <th class="text-center align-middle">CUMPLIMIENTO</th>
                                    @foreach ($dias as $dia)
                                        <th class="text-center align-middle" style="min-width:90px">
                                            <small>{{ ucfirst($dia['diaNombre']) }}</small><br>
                                            <strong>{{ $dia['dia'] }}</strong><br>
                                            <small>{{ ucfirst($dia['mes']) }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $resultado)
                                    <tr>
                                        <td class="employee-cell">
                                            <strong>{{ $resultado['empleado_nombre'] }}</strong>
                                            @if(!empty($resultado['intervalo_hv']))
                                                <div><small class="text-muted">Intervalo: {{ $resultado['intervalo_hv'] }} Hr.(s)</small></div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @php
                                                $c = floatval($resultado['cumplimiento'] ?? 0);
                                                // decidir estado según el valor real (no el entero redondeado)
                                                if ($c >= 100) {
                                                    $cClass = 'hv-box hv-success';
                                                } elseif ($c >= 50) {
                                                    $cClass = 'hv-box hv-warning';
                                                } elseif ($c > 0) {
                                                    // 0.1 - 49.99% -> secondary
                                                    $cClass = 'hv-box hv-secondary';
                                                } else {
                                                    // 0% exacto
                                                    $cClass = 'hv-box hv-danger';
                                                }
                                                $cDisplay = intval(round($c));
                                            @endphp
                                            <div class="{{ $cClass }}" title="Cumplimiento: {{ $cDisplay }}%">
                                                <span class="hv-text">{{ $cDisplay }}%</span>
                                            </div>
                                        </td>
                                        @foreach ($resultado['dias'] as $dia)
                                            @php
                                                $count = $dia['marcaciones_count'] ?? 0;
                                                $esp = $dia['esperadas'] ?? 0;
                                                $status = $dia['status'] ?? 'neutral';
                                                $boxClass = 'hv-box hv-' . $status;
                                            @endphp
                                            <td class="day-cell">
                                                @if($esp === 0)
                                                    <div class="hv-box hv-neutral" title="Sin designación">
                                                        <span class="hv-text">S/D</span>
                                                    </div>
                                                @else
                                                    <div class="{{ $boxClass }}" title="{{ $count }}/{{ $esp }}">
                                                        <span class="hv-text">{{ $count }}/{{ $esp }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif($mostrarResultados)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No se encontraron registros.
            </div>
        @endif

    </div>
</div>
