{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\admin\clientestools.blade.php --}}
<div>
    <div class="row">
        <div class="col col-12 col-md-3">
            <div class="card">
                <div class="card-body table-responsive p-0" style="height: 420px;">
                    <table class="table table-striped" style="font-size: 13px;">
                        <thead class="table-primary">
                            <tr>
                                <th>EMPRESAS</th>
                                <th class="text-right">OFICINA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            @switch($cliente[4])
                                                @case(0)
                                                    {{-- Danger: No marcó y ya pasó la hora o hay pánicos --}}
                                                    <input class="custom-control-input custom-control-input-danger"
                                                @break
                                                @case(1)
                                                    {{-- Primary: Marcó correctamente (dentro de 15 min tolerancia) --}}
                                                    <input class="custom-control-input custom-control-input-primary"
                                                @break
                                                @case(2)
                                                    {{-- Secondary: No es hora aún o turno completo --}}
                                                    <input class="custom-control-input custom-control-input-secondary"
                                                @break
                                                @case(3)
                                                    {{-- Warning: Marcó con retraso mayor a 15 min --}}
                                                    <input class="custom-control-input custom-control-input-warning"
                                                @break
                                                @default
                                                    <input class="custom-control-input custom-control-input-secondary"
                                            @endswitch
                                                type="radio" id="{{ $cliente[0] }}" checked="">
                                                <label for="{{ $cliente[0] }}" class="custom-control-label">
                                                    <a href="javascript:void(0);" class="text-dark"
                                                        wire:click="cargarCliente({{ $cliente[0] }})">
                                                        {{ $cliente[1] }}
                                                    </a>
                                                </label>
                                        </div>
                                    </td>
                                    <td align="right">{{ $cliente[2] }}</td>
                                </tr>
                                @php
                                    $i = ($i == 5) ? 0 : $i + 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SOLO CAMBIO: Cambiar div de Leaflet por Google Maps --}}
        <div class="col col-12 col-md-9" wire:ignore>
            <div class="card">
                <div class="card-body">
                    <div id="google_map" style="width: 100%; height: 380px;"></div>
                </div>
            </div>
        </div>

        {{-- TODO LO DEMÁS IGUAL - Sin cambios --}}
        @if (!is_null($selCliente))
            <div class="col-12">
                <div class="card">
                    @switch($marque)
                        @case(0)
                            {{-- Danger: No marcó y ya pasó la hora o hay pánicos (CRÍTICO) --}}
                            <div class="card-header bg-danger text-white">
                        @break
                        @case(3)
                            {{-- Warning: Marcó con retraso mayor a 15 min (MODERADO) --}}
                            <div class="card-header bg-warning text-white">
                        @break
                        @case(1)
                            {{-- Primary: Marcó correctamente (NORMAL) --}}
                            <div class="card-header bg-primary text-white">
                        @break
                        @case(2)
                            {{-- Secondary: No es hora aún o turno completo (SIN ACCIÓN) --}}
                            <div class="card-header bg-secondary text-white">
                        @break
                        @default
                            <div class="card-header bg-secondary text-white">
                    @endswitch
                        <strong>{{ $selCliente->nombre }}</strong>
                        @if($marque == 0)
                            <span class="float-right">
                                <i class="fas fa-exclamation-triangle"></i> ALERTA CRÍTICA
                            </span>
                        @elseif($marque == 3)
                            <span class="float-right">
                                <i class="fas fa-exclamation-circle"></i> RETRASO
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped" style="font-size: 14px;">
                                        <thead>
                                            <tr class="bg-info text-white">
                                                <td colspan="2"><strong>Datos de la Empresa</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Dirección:</strong></td>
                                                <td>{{ strtoupper($selCliente->direccion) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contacto:</strong></td>
                                                <td>{{ strtoupper($selCliente->personacontacto) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Teléfono Contacto:</strong></td>
                                                <td>{{ strtoupper($selCliente->telefonocontacto) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fin de Contrato:</strong></td>
                                                <td>
                                                    @if($selCliente->fecha_fin)
                                                        @php
                                                            $fechaFin = \Carbon\Carbon::parse($selCliente->fecha_fin);
                                                            $hoy = \Carbon\Carbon::now();
                                                            $diasRestantes = $hoy->diffInDays($fechaFin, false);
                                                        @endphp

                                                        @if($diasRestantes < 0)
                                                            {{-- Contrato vencido --}}
                                                            <span class="badge badge-danger" title="Contrato vencido">
                                                                <i class="fas fa-exclamation-circle"></i>
                                                                {{ formatearFecha($selCliente->fecha_fin) }}
                                                            </span>
                                                            <br><small class="text-danger">Vencido hace {{ abs($diasRestantes) }} días</small>
                                                        @elseif($diasRestantes <= 30)
                                                            {{-- Contrato próximo a vencer (30 días o menos) --}}
                                                            <span class="badge badge-warning" title="Contrato próximo a vencer">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                {{ formatearFecha($selCliente->fecha_fin) }}
                                                            </span>
                                                            <br><small class="text-warning">Vence en {{ $diasRestantes }} días</small>
                                                        @else
                                                            {{-- Contrato vigente --}}
                                                            <span class="badge badge-success" title="Contrato vigente">
                                                                <i class="fas fa-check-circle"></i>
                                                                {{ formatearFecha($selCliente->fecha_fin) }}
                                                            </span>
                                                            <br><small class="text-muted">Vigente por {{ $diasRestantes }} días</small>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-infinity"></i> Indefinido
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped" style="font-size: 14px;">
                                        <thead>
                                            <tr class="bg-info text-white">
                                                <td colspan="5"><strong>Personal Asignado</strong></td>
                                            </tr>
                                            <tr class="table-info">
                                                <th>Nombre</th>
                                                <th>Turno</th>
                                                <th class="text-center">Asistencia</th>
                                                <th class="text-center">HV</th>
                                                <th class="text-center">Alertas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($designaciones as $item)
                                                <tr>
                                                    <td class="align-middle">{{ $item->empleado }}</td>
                                                    <td class="align-middle">
                                                        {{ strtoupper($item->turno) }} <br>
                                                        <small>({{ $item->datosturno->horainicio }} - {{ $item->datosturno->horafin }})</small>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{-- Evaluar estado de marca --}}
                                                        @php
                                                            $marcado = yaMarque2($item->id);
                                                            $estadoMarca = $marcado[0];
                                                            $ingreso = $marcado[1];
                                                            $salida = $marcado[2];

                                                            // Pre-calcular todas las variables necesarias
                                                            $minutosRetraso = 0;
                                                            $horaIngresoFormateada = null;
                                                            $horaSalidaFormateada = null;
                                                            $conRetraso = false;

                                                            if ($ingreso) {
                                                                $horaInicio = \Carbon\Carbon::parse($item->datosturno->horainicio);
                                                                $horaIngreso = \Carbon\Carbon::parse($ingreso);

                                                                if ($horaIngreso->gt($horaInicio)) {
                                                                    $minutosRetraso = $horaIngreso->diffInMinutes($horaInicio);
                                                                    $conRetraso = $minutosRetraso > $tolerancia;
                                                                }

                                                                $horaIngresoFormateada = $horaIngreso->format('H:i');
                                                            }

                                                            if ($salida) {
                                                                $horaSalidaFormateada = \Carbon\Carbon::parse($salida)->format('H:i');
                                                            }
                                                        @endphp

                                                        @switch($estadoMarca)
                                                            @case(0)
                                                                {{-- Estado 0: No marcó y ya pasó la hora (CRÍTICO) --}}
                                                                <span class="badge badge-pill badge-danger">Sin marcar</span>
                                                                <br><small class="text-danger">Debió marcar</small>
                                                            @break

                                                            @case(1)
                                                                {{-- Estado 1: Marcó ingreso, activo --}}
                                                                <span class="badge badge-pill badge-success">Activo</span>
                                                                @if($conRetraso)
                                                                    <br><span class="badge badge-pill badge-warning" title="Marcado con retraso: {{ $minutosRetraso }} min">
                                                                        {{ $horaIngresoFormateada }}
                                                                    </span>
                                                                @else
                                                                    <br><span class="badge badge-pill badge-success" title="Marcado puntual">
                                                                        {{ $horaIngresoFormateada }}
                                                                    </span>
                                                                @endif
                                                            @break

                                                            @case(2)
                                                                {{-- Estado 2: Turno completo --}}
                                                                <span class="badge badge-pill badge-info">Completo</span>
                                                                <br><small>{{ $horaIngresoFormateada }} - {{ $horaSalidaFormateada }}</small>
                                                            @break

                                                            @case(3)
                                                                {{-- Estado 3: Aún no es hora (EN DESCANSO) --}}
                                                                <span class="badge badge-pill badge-secondary">En descanso</span>
                                                                <br><small class="text-muted">Fuera de horario</small>
                                                            @break

                                                            @default
                                                                <span class="badge badge-pill badge-secondary">N/A</span>
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @php
                                                            $hoy = \Carbon\Carbon::today();

                                                            // Lógica compacta igual a ListadoHv::generarReporte
                                                            $turnoInicio = $item->datosturno->horainicio ?? null;
                                                            $turnoFin = $item->datosturno->horafin ?? null;

                                                            // obtener ids de intervalos asignados a la designación
                                                            $intervalosIds = $item->intervalos ? $item->intervalos->pluck('id')->toArray() : [];
                                                            $intervalos = count($intervalosIds);

                                                            if (!$turnoInicio || !$turnoFin) {
                                                                $hvs = \App\Models\Hombrevivo::whereIn('intervalo_id', $intervalosIds)
                                                                    ->whereDate('fecha', $hoy->format('Y-m-d'))
                                                                    ->where('status', true)
                                                                    ->count();
                                                            } else {
                                                                $horainicio = \Carbon\Carbon::parse($turnoInicio)->subMinutes(15)->format('H:i:s');
                                                                $horafin = \Carbon\Carbon::parse($turnoFin)->addMinutes(30)->format('H:i:s');

                                                                if (\Carbon\Carbon::parse($turnoInicio)->gt(\Carbon\Carbon::parse($turnoFin))) {
                                                                    // nocturno: registros del dia actual >= horainicio OR del dia siguiente <= horafin
                                                                    $fechaBase = $hoy->copy();
                                                                    $hvs = \App\Models\Hombrevivo::whereIn('intervalo_id', $intervalosIds)
                                                                        ->where(function ($q) use ($hoy, $horainicio, $horafin, $fechaBase) {
                                                                            $q->where(function ($q1) use ($hoy, $horainicio) {
                                                                                $q1->whereDate('fecha', $hoy->format('Y-m-d'))
                                                                                    ->where('hora', '>=', $horainicio);
                                                                            })
                                                                            ->orWhere(function ($q2) use ($fechaBase, $horafin) {
                                                                                $q2->whereDate('fecha', $fechaBase->copy()->addDay()->format('Y-m-d'))
                                                                                    ->where('hora', '<=', $horafin);
                                                                            });
                                                                        })->where('status', true)->count();
                                                                } else {
                                                                    // diurno: misma fecha entre rangos
                                                                    $hvs = \App\Models\Hombrevivo::whereDate('fecha', $hoy->format('Y-m-d'))
                                                                        ->whereIn('intervalo_id', $intervalosIds)
                                                                        ->whereBetween('hora', [$horainicio, $horafin])
                                                                        ->where('status', true)
                                                                        ->count();
                                                                }
                                                            }
                                                        @endphp


                                                        @if ($intervalos > 0)
                                                            @php
                                                                // Calcular porcentaje
                                                                $porcentaje = round(($hvs / $intervalos) * 100);

                                                                // Determinar color del badge según la leyenda:
                                                                // 100% -> success
                                                                // 50-99% -> warning
                                                                // 1-49% -> secondary
                                                                // 0% -> danger
                                                                if ($porcentaje == 100) {
                                                                    $badgeColor = 'success';
                                                                } elseif ($porcentaje >= 50) {
                                                                    $badgeColor = 'warning';
                                                                } elseif ($porcentaje > 0) {
                                                                    $badgeColor = 'secondary';
                                                                } else {
                                                                    $badgeColor = 'danger';
                                                                }
                                                            @endphp

                                                            <span class="badge badge-pill badge-{{ $badgeColor }}"
                                                                  title="Hombres vivos reportados: {{ $porcentaje }}%">
                                                                {{ $hvs }} / {{ $intervalos }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-pill badge-secondary" title="Sin intervalos configurados">
                                                                N/A
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{-- Contar alertas de pánico --}}
                                                        @php
                                                            $panicos = tengoPanicos($item->datosempleado->user_id, $selCliente->id);
                                                        @endphp
                                                        @if ($panicos > 0)
                                                            <a href="{{ route('admin.regactividad', $selCliente->id) }}"
                                                               class="badge badge-pill badge-danger"
                                                               title="¡Alertas de pánico!">
                                                                <i class="fas fa-exclamation-triangle"></i> {{ $panicos }}
                                                            </a>
                                                        @else
                                                            <span class="badge badge-pill badge-secondary">0</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- SOLO CAMBIO: JavaScript de Leaflet por Google Maps --}}
@section('js')
<script>
    let map;
    let markers = [];

    function initMap() {
        // Mismas coordenadas que tenías en Leaflet
        const defaultCenter = { lat: -17.7817999, lng: -63.1825485 };

        map = new google.maps.Map(document.getElementById("google_map"), {
            zoom: 12,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapId: "DEMO_MAP_ID" // Para usar AdvancedMarkerElement en el futuro
        });

        loadMarkers();
    }

    function loadMarkers() {
        clearMarkers();

        var arr1 = "{{ $pts }}";
        if (!arr1) return;

        arr1 = arr1.split('$');

        for (let i = 0; i < arr1.length; i++) {
            if (!arr1[i]) continue;

            const pt = arr1[i].split("|");
            const position = { lat: parseFloat(pt[1]), lng: parseFloat(pt[2]) };

            let iconUrl;
            switch (pt[8]) {
                case '0':
                    iconUrl = "{{ asset('images/img-maps/marker_red.png') }}";
                    break;
                case '1':
                    iconUrl = "{{ asset('images/img-maps/marker_blue.png') }}";
                    break;
                case '2':
                    iconUrl = "{{ asset('images/img-maps/marker_grey.png') }}";
                    break;
                default:
                    iconUrl = "{{ asset('images/img-maps/marker_blue.png') }}";
            }

            // Usar google.maps.Marker (funciona perfectamente, solo es una advertencia)
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: pt[0],
                icon: {
                    url: iconUrl,
                    scaledSize: new google.maps.Size(20, 35),
                    anchor: new google.maps.Point(10, 35)
                }
            });

            let infoContent = '';
            if (pt[8] === '0') {
                infoContent = `<h6>${pt[0]}</h6><small>${pt[3]}</small><p><a href="javascript:void(0);" onclick="cargarCliente(${pt[6]})" style="color: red">Ver alertas!</a><br><br><a href="./admin/clientes/${pt[6]}">Mas Información</a></p>`;
            } else {
                infoContent = `<h6>${pt[0]}</h6><small>${pt[3]}</small><p><a href="./admin/clientes/${pt[6]}">Mas Información</a></p>`;
            }

            const infoWindow = new google.maps.InfoWindow({ content: infoContent });
            marker.addListener('click', () => infoWindow.open(map, marker));
            markers.push(marker);
        }
    }

    function clearMarkers() {
        markers.forEach(marker => marker.setMap(null));
        markers = [];
    }

    function cargarCliente(id) {
        @this.cargarCliente(id);
    }

    document.addEventListener('livewire:update', () => {
        setTimeout(() => { if (map) loadMarkers(); }, 100);
    });
</script>

{{-- Carga corregida con loading=async --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.api_key') }}&callback=initMap&loading=async">
</script>
@endsection
