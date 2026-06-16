<?php

namespace App\Http\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Designacione;
use Livewire\Component;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhsueldo;
use App\Models\Rrhhferiado;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Models\RrhhSueldoEmpleado;

class ProcesarSueldo extends Component
{
    public $rrhhsueldo;
    public $contratos = [];
    public $feriados = [];
    public $procesado = false;
    public $contratosSeleccionados = [];
    public $seleccionarTodos = false;

    protected $listeners = ['guardarSueldos'];

    public function mount($rrhhsueldo_id)
    {
        $this->rrhhsueldo = Rrhhsueldo::findOrFail($rrhhsueldo_id);

        $this->contratos = $this->mapContratos($this->getContratosVigentes());

        $this->feriados = $this->getFeriadosMes();
    }

    public function updatedSeleccionarTodos($value)
    {
        if ($value) {
            $this->contratosSeleccionados = collect($this->contratos)->pluck('id')->toArray();
        } else {
            $this->contratosSeleccionados = [];
        }
    }

    public function updatedContratosSeleccionados()
    {
        $this->seleccionarTodos = count($this->contratosSeleccionados) === count($this->contratos);
    }

    protected function mapContratos($contratos)
    {
        return $contratos->map(function ($contrato) {
            $dias_tipo = $contrato->rrhhtipocontrato->cantidad_dias ?? 30;

            return [
                'id' => $contrato->id,
                'empleado_id' => $contrato->empleado->id,
                'nombres' => $contrato->empleado->nombres,
                'apellidos' => $contrato->empleado->apellidos,
                'fecha_inicio' => $contrato->fecha_inicio,
                'fecha_fin' => $contrato->fecha_fin ?? 'Indefinido',
                'salario_basico' => $contrato->salario_basico,
                'tipo_contrato' => $contrato->rrhhtipocontrato->nombre ?? 'N/A',
                'sueldo' => 0,
                'bonos' => 0,
                'descuentos' => 0,
                'total' => 0,
                'total_ctrlasistencias' => 0,
                'total_permisos' => 0,
                'salario_mes' => 0,
                'liquido_pagable' => 0,
                'dias_procesables' => $dias_tipo,
            ];
        })->toArray();
    }

    public function getContratosVigentes()
    {
        $anio = $this->rrhhsueldo->gestion;
        $mes  = $this->rrhhsueldo->mes;

        $fechaInicioMes = now()->setDate($anio, $mes, 1)->startOfDay();
        $fechaFinMes    = now()->setDate($anio, $mes, 1)->endOfMonth()->endOfDay();

        return Rrhhcontrato::with(['rrhhtipocontrato', 'empleado'])
            ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                $q->whereNull('fecha_fin')
                    ->orWhereBetween('fecha_fin', [$fechaInicioMes, $fechaFinMes])
                    ->orWhere('fecha_fin', '>=', $fechaInicioMes);
            })
            ->where('fecha_inicio', '<=', $fechaFinMes)
            ->get();
    }

    public function getFeriadosMes()
    {
        $anio = $this->rrhhsueldo->gestion;
        $mes  = $this->rrhhsueldo->mes;

        $inicio = Carbon::create($anio, $mes, 1)->startOfDay();
        $fin    = $inicio->copy()->endOfMonth()->endOfDay();

        return Rrhhferiado::query()
            ->where('activo', true)
            ->where(function ($q) use ($inicio, $fin, $mes) {
                $q->whereBetween('fecha', [$inicio, $fin])
                    ->orWhere(function ($q2) use ($inicio, $fin) {
                        $q2->whereNotNull('fecha_inicio')
                            ->whereNotNull('fecha_fin')
                            ->where('fecha_inicio', '<=', $fin)
                            ->where('fecha_fin', '>=', $inicio);
                    })
                    ->orWhere(function ($q3) use ($mes) {
                        $q3->where('recurrente', true)
                            ->whereMonth('fecha', $mes);
                    });
            })
            ->orderByRaw('COALESCE(fecha, fecha_inicio) ASC')
            ->get();
    }

    public function procesar()
    {
        if (empty($this->contratosSeleccionados)) {
            $this->emit('error', 'Debe seleccionar al menos un contrato para procesar.');
            return;
        }
        $anio = $this->rrhhsueldo->gestion;
        $mes  = $this->rrhhsueldo->mes;

        $this->contratos = $this->getContratosVigentes()->map(function ($contrato) use ($anio, $mes) {
            // dd($contrato->id);
            if (in_array($contrato->id, $this->contratosSeleccionados)) {
                $dias_tipo = $contrato->rrhhtipocontrato->cantidad_dias ?? 30;
                $valor_dia = $contrato->salario_basico / $dias_tipo;

                $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfDay();
                $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();

                $fechaInicioEval = Carbon::parse($contrato->fecha_inicio)->greaterThan($fechaInicioMes)
                    ? Carbon::parse($contrato->fecha_inicio)
                    : $fechaInicioMes;

                $fechaFinEval = ($contrato->fecha_fin && $contrato->fecha_fin !== 'Indefinido')
                    ? (Carbon::parse($contrato->fecha_fin)->lessThan($fechaFinMes) ? Carbon::parse($contrato->fecha_fin) : $fechaFinMes)
                    : $fechaFinMes;

                $dias_procesables = $fechaInicioEval->diffInDays($fechaFinEval) + 1;
                $salario_prorrateado = round(min($dias_procesables * $valor_dia, $contrato->salario_basico), 2);

                // --- INICIO NUEVO BLOQUE ---
                // Traer designaciones activas para el contrato en el mes
                $designaciones = Designacione::where('empleado_id', $contrato->empleado->id)
                    ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                        $q->whereBetween('fechaInicio', [$fechaInicioMes, $fechaFinMes])
                            ->orWhereBetween('fechaFin', [$fechaInicioMes, $fechaFinMes])
                            ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
                                $q2->where('fechaInicio', '<=', $fechaInicioMes)
                                    ->where('fechaFin', '>=', $fechaFinMes);
                            });
                    })
                    ->get();

                // Si hay designaciones, toma la primera para analizar sus días
                $designaciondias = null;
                if ($designaciones->isNotEmpty()) {
                    $designacione = $designaciones->first();
                    $designaciondias = \App\Models\Designaciondia::where('designacione_id', $designacione->id)->first();

                    // Mapear los días laborales al formato [0=>domingo, 1=>lunes, ..., 6=>sabado]
                    $dias_php = [
                        0 => 'domingo',
                        1 => 'lunes',
                        2 => 'martes',
                        3 => 'miercoles',
                        4 => 'jueves',
                        5 => 'viernes',
                        6 => 'sabado',
                    ];
                    $dias_laborales = [];
                    foreach ($dias_php as $num => $nombre) {
                        $dias_laborales[$num] = $designaciondias ? (bool)$designaciondias->$nombre : false;
                    }
                }
                // --- FIN NUEVO BLOQUE ---

                // Generar calendario laboral para el empleado
                $calendario = $this->generarCalendarioLaboral($contrato, $anio, $mes);
                // Calcular ajustes y desglose (unificado: asistencias y permisos)
                $resultado = $this->calcularAjustesPorCalendario($calendario, $valor_dia, $contrato->empleado->id, $anio, $mes);

                // Calcular ajuste total solo por permisos (usando el detalle de días)
                $ajuste_permisos = 0;
                $detalle_dias = $resultado['detalle']['detalle_dias'] ?? [];
                foreach ($detalle_dias as $info) {
                    if (!empty($info['permiso'])) {
                        $ajuste_permisos += $info['ajuste'] ?? 0;
                    }
                }
                $ajuste_permisos = round($ajuste_permisos, 2);


                // Calcular adelantos aprobados del mes para el contrato
                $adelantos = -1 * \App\Models\Rrhhadelanto::where('rrhhcontrato_id', $contrato->id)
                    ->where('empleado_id', $contrato->empleado->id)
                    ->where('estado', 'APROBADO')
                    ->whereYear('fecha', $anio)
                    ->whereMonth('fecha', $mes)
                    ->sum('monto');

                // Calcular bonos y descuentos del mes para el contrato
                $bonos = \App\Models\Rrhhbono::where('rrhhcontrato_id', $contrato->id)
                    ->where('empleado_id', $contrato->empleado->id)
                    ->where('estado', true)
                    ->whereYear('fecha', $anio)
                    ->whereMonth('fecha', $mes)
                    ->get();
                $total_bonos = $bonos->sum(function ($bono) {
                    // Si el monto es negativo, es descuento; si es positivo, es bono
                    return ($bono->cantidad ?? 1) * ($bono->monto ?? 0);
                });

                // Limitar el ajuste negativo máximo al salario prorrateado
                $ajustes = $resultado['ajuste'];
                if ($ajustes < -$salario_prorrateado) {
                    $ajustes = -$salario_prorrateado;
                }
                // Sumar bonos/descuentos y restar adelantos al líquido pagable
                $liquido = $salario_prorrateado + $ajustes + $adelantos + $total_bonos;
                if ($liquido < 0) {
                    $liquido = 0;
                }

                return [
                    'id' => $contrato->id,
                    'empleado_id' => $contrato->empleado->id,
                    'nombres' => $contrato->empleado->nombres,
                    'apellidos' => $contrato->empleado->apellidos,
                    'fecha_inicio' => $contrato->fecha_inicio,
                    'fecha_fin' => $contrato->fecha_fin ?? 'Indefinido',
                    'salario_basico' => $contrato->salario_basico,
                    'tipo_contrato' => $contrato->rrhhtipocontrato->nombre ?? 'N/A',
                    'valor_dia' => $valor_dia,
                    'dias_procesables' => $dias_procesables,
                    'salario_mes' => $salario_prorrateado,
                    'total_ctrlasistencias' => $ajustes,
                    'total_permisos' => $ajuste_permisos,
                    'total_adelantos' => $adelantos,
                    'total_bonos' => $total_bonos,
                    'liquido_pagable' => $liquido,
                    'detalle_pago' => $resultado['detalle'],
                    'calendario_laboral' => $calendario,
                ];
            }
        })->toArray();

        $this->procesado = true;
    }

    /**
     * Calcula el ajuste total por permisos del empleado en el mes.
     * Suma (valor_dia * cantidad_dias * (factor - 1)) para cada permiso activo.
     */
    protected function calcularAjustePermisos($contrato, $anio, $mes, $valor_dia)
    {
        // Buscar permisos activos del empleado en el mes
        $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();
        $permisos = \App\Models\Rrhhpermiso::where('empleado_id', $contrato->empleado->id)
            ->where('activo', true)
            ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                $q->whereBetween('fecha_inicio', [$fechaInicioMes, $fechaFinMes])
                    ->orWhereBetween('fecha_fin', [$fechaInicioMes, $fechaFinMes])
                    ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
                        $q2->where('fecha_inicio', '<=', $fechaInicioMes)
                            ->where('fecha_fin', '>=', $fechaFinMes);
                    });
            })
            ->get();

        $ajuste = 0;
        foreach ($permisos as $permiso) {
            $factor = 1;
            if ($permiso->rrhhtipopermiso && $permiso->rrhhtipopermiso->factor !== null) {
                $factor = $permiso->rrhhtipopermiso->factor;
            }
            $inicio = Carbon::parse($permiso->fecha_inicio)->greaterThan($fechaInicioMes) ? Carbon::parse($permiso->fecha_inicio) : $fechaInicioMes;
            $fin = Carbon::parse($permiso->fecha_fin)->lessThan($fechaFinMes) ? Carbon::parse($permiso->fecha_fin) : $fechaFinMes;
            $dias = $inicio->diffInDays($fin) + 1;
            $ajuste += $valor_dia * $dias * ($factor - 1);
        }
        return round($ajuste, 2);
    }
    /**
     * Genera un calendario laboral por empleado para el mes dado.
     * Cada día tiene: fecha, tipo_dia (normal/feriado/fuera_contrato), designacion_activa, asistencia, factor_feriado, estado_asistencia
     */
    protected function generarCalendarioLaboral($contrato, $anio, $mes)
    {
        $feriados = collect($this->getFeriados($anio))->keyBy('fecha');
        $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();
        $fechaInicioContrato = Carbon::parse($contrato->fecha_inicio);
        $fechaFinContrato = ($contrato->fecha_fin && $contrato->fecha_fin !== 'Indefinido')
            ? Carbon::parse($contrato->fecha_fin)
            : $fechaFinMes;

        $designaciones = Designacione::where('empleado_id', $contrato->empleado->id)
            ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                $q->whereBetween('fechaInicio', [$fechaInicioMes, $fechaFinMes])
                    ->orWhereBetween('fechaFin', [$fechaInicioMes, $fechaFinMes])
                    ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
                        $q2->where('fechaInicio', '<=', $fechaInicioMes)
                            ->where('fechaFin', '>=', $fechaFinMes);
                    });
            })
            ->get();

        $asistencias = [];
        if ($designaciones->isNotEmpty()) {
            $asistencias = Asistencia::join('designaciones', 'designaciones.id', '=', 'asistencias.designacione_id')
                ->where('designaciones.empleado_id', $contrato->empleado->id)
                ->whereBetween('fecha', [$fechaInicioMes->format('Y-m-d'), $fechaFinMes->format('Y-m-d')])
                ->get()
                ->keyBy(fn($item) => Carbon::parse($item->fecha)->format('Y-m-d'));
        }

        $periodo = new DatePeriod(
            new DateTime($fechaInicioMes),
            new DateInterval('P1D'),
            (new DateTime($fechaFinMes))->modify('+1 day')
        );
        $calendario = [];
        foreach ($periodo as $date) {
            $fecha = $date->format('Y-m-d');
            $tipo_dia = 'normal';
            $factor_feriado = null;
            $es_feriado = false;
            $designacion_activa = false;
            $asistencia = null;
            $estado_asistencia = null;

            // Verificar si está dentro del contrato
            if (Carbon::parse($fecha)->lt($fechaInicioContrato) || Carbon::parse($fecha)->gt($fechaFinContrato)) {
                $tipo_dia = 'fuera_contrato';
            } else {
                // Verificar designación activa (considerar también designaciones finalizadas si la fecha de fin es >= al día)
                foreach ($designaciones as $desig) {
                    if (Carbon::parse($fecha)->between(Carbon::parse($desig->fechaInicio), Carbon::parse($desig->fechaFin))) {
                        $designacion_activa = true;
                        break;
                    }
                }
                // Verificar feriado
                if ($feriados->has($fecha)) {
                    $tipo_dia = 'feriado';
                    $es_feriado = true;
                    $factor_feriado = $feriados[$fecha]['factor'];
                }
                // Asistencia
                if ($asistencias && isset($asistencias[$fecha])) {
                    $asistencia = $asistencias[$fecha];
                    if ($asistencia->ingreso && $asistencia->salida) {
                        $estado_asistencia = 'completa';
                    } elseif ($asistencia->ingreso || $asistencia->salida) {
                        $estado_asistencia = 'media_jornada';
                    } else {
                        $estado_asistencia = 'sin_marca';
                    }
                }
            }
            $calendario[] = [
                'fecha' => $fecha,
                'tipo_dia' => $tipo_dia,
                'designacion_activa' => $designacion_activa,
                'asistencia' => $asistencia,
                'factor_feriado' => $factor_feriado,
                'estado_asistencia' => $estado_asistencia,
            ];
        }
        return $calendario;
    }

    /**
     * Calcula los ajustes y el desglose a partir del calendario laboral generado.
     */
    /**
     * Proceso consolidado: calcula ajustes por calendario laboral.
     * Antes de descontar por falta, verifica si existe permiso que cubra el día.
     * Si hay permiso, no se descuenta. Se anotan los conceptos en el detalle.
     */
    protected function calcularAjustesPorCalendario($calendario, $valor_dia, $empleado_id = null, $anio = null, $mes = null)
    {
        $total_ajustes = 0;
        $detalle = [
            'normales_pagados' => 0,
            'feriados_sin_marca' => 0,
            'feriados_con_marca' => 0,
            'descuentos' => 0,
            'media_jornada' => 0,
            'fuera_contrato' => 0,
            'sin_designacion' => 0,
            'feriados_detalle' => [],
            'detalle_dias' => [], // Para mostrar el desglose por día
        ];

        // Pre-cargar permisos del empleado para el mes
        $permisos = collect();
        if ($empleado_id && $anio && $mes) {
            $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
            $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();
            $permisos = \App\Models\Rrhhpermiso::with('rrhhtipopermiso')->where('empleado_id', $empleado_id)
                ->where('activo', true)
                ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                    $q->whereBetween('fecha_inicio', [$fechaInicioMes, $fechaFinMes])
                        ->orWhereBetween('fecha_fin', [$fechaInicioMes, $fechaFinMes])
                        ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
                            $q2->where('fecha_inicio', '<=', $fechaInicioMes)
                                ->where('fecha_fin', '>=', $fechaFinMes);
                        });
                })
                ->get();
        }

        foreach ($calendario as $dia) {
            $info = [
                'fecha' => $dia['fecha'],
                'tipo_dia' => $dia['tipo_dia'],
                'designacion_activa' => $dia['designacion_activa'],
                'estado_asistencia' => $dia['estado_asistencia'],
                'factor_feriado' => $dia['factor_feriado'],
                'ajuste' => 0,
                'concepto' => '',
                'permiso' => null,
                'tipo_permiso' => null,
                'factor_permiso' => null,
            ];

            // --- INICIO BLOQUE NUEVO ---
            // Verificar si el día es designación activa y si es día libre según la designación
            if ($dia['designacion_activa']) {
                // Obtener la designación activa para el día
                $designacione = \App\Models\Designacione::where('empleado_id', $empleado_id)
                    ->whereDate('fechaInicio', '<=', $dia['fecha'])
                    ->whereDate('fechaFin', '>=', $dia['fecha'])
                    ->first();

                if ($designacione) {
                    $designaciondias = \App\Models\Designaciondia::where('designacione_id', $designacione->id)->first();
                    $dias_php = [
                        0 => 'domingo',
                        1 => 'lunes',
                        2 => 'martes',
                        3 => 'miercoles',
                        4 => 'jueves',
                        5 => 'viernes',
                        6 => 'sabado',
                    ];
                    $carbon_fecha = \Carbon\Carbon::parse($dia['fecha']);
                    $dia_semana_num = $carbon_fecha->dayOfWeek; // 0=domingo, 1=lunes, ..., 6=sabado
                    $dia_semana_nombre = $dias_php[$dia_semana_num];
                    $dias_laborales = [];
                    foreach ($dias_php as $num => $nombre) {
                        $dias_laborales[$num] = $designaciondias ? (bool)$designaciondias->$nombre : false;
                    }

                    // Si el día no es laborable, marcar como Día Libre y continuar
                    if (isset($dias_laborales[$dia_semana_num]) && $dias_laborales[$dia_semana_num] === false) {
                        $info['concepto'] = 'Dia Libre';
                        $info['ajuste'] = 0;
                        $detalle['detalle_dias'][] = $info;
                        continue; // Saltar el resto de la lógica de ajuste
                    }
                }
            }
            // --- FIN BLOQUE NUEVO ---

            // Buscar si el día tiene permiso
            $permiso_dia = $permisos->first(function ($permiso) use ($dia) {
                return Carbon::parse($dia['fecha'])->between(
                    Carbon::parse($permiso->fecha_inicio),
                    Carbon::parse($permiso->fecha_fin)
                );
            });
            if ($permiso_dia) {
                $info['permiso'] = true;
                $info['tipo_permiso'] = $permiso_dia->rrhhtipopermiso->nombre ?? '-';
                $info['factor_permiso'] = $permiso_dia->rrhhtipopermiso->factor ?? 1;
            }

            if ($dia['tipo_dia'] === 'fuera_contrato') {
                $detalle['fuera_contrato']++;
                $info['concepto'] = 'Fuera de contrato';
            } elseif (!$dia['designacion_activa']) {
                $detalle['sin_designacion']++;
                $detalle['normales_pagados']++;
                $info['concepto'] = 'Pagado (sin designación)';
            } elseif ($dia['tipo_dia'] === 'feriado') {
                if ($dia['estado_asistencia'] === 'completa') {
                    $detalle['feriados_con_marca']++;
                    $total_ajustes += $valor_dia * ($dia['factor_feriado'] - 1);
                    $detalle['feriados_detalle'][] = [
                        'fecha' => $dia['fecha'],
                        'factor' => $dia['factor_feriado'],
                        'tipo' => 'con_marca',
                        'monto' => round($valor_dia * $dia['factor_feriado'], 2)
                    ];
                    $info['concepto'] = 'Feriado con marca';
                    $info['ajuste'] = $valor_dia * ($dia['factor_feriado'] - 1);
                } else {
                    $detalle['feriados_sin_marca']++;
                    $detalle['feriados_detalle'][] = [
                        'fecha' => $dia['fecha'],
                        'factor' => $dia['factor_feriado'],
                        'tipo' => 'sin_marca',
                        'monto' => round($valor_dia, 2)
                    ];
                    $info['concepto'] = 'Feriado sin marca';
                }
            } else {
                // Día normal con designación activa
                if ($dia['estado_asistencia'] === 'completa') {
                    $detalle['normales_pagados']++;
                    $info['concepto'] = 'Normal pagado';
                } elseif ($dia['estado_asistencia'] === 'media_jornada') {
                    // Si hay permiso, aplicar ajuste según factor del permiso
                    if ($permiso_dia) {
                        $factor_permiso = $permiso_dia->rrhhtipopermiso->factor ?? 1;
                        $ajuste_permiso = $valor_dia * ($factor_permiso - 1) * 0.5;
                        $info['concepto'] = 'Media jornada (con permiso)';
                        $info['ajuste'] = $ajuste_permiso;
                        $total_ajustes += $ajuste_permiso;
                    } else {
                        $total_ajustes -= $valor_dia * 0.5;
                        $detalle['media_jornada']++;
                        $info['concepto'] = 'Media jornada';
                        $info['ajuste'] = -$valor_dia * 0.5;
                    }
                } else {
                    // Si hay permiso, aplicar ajuste según factor del permiso
                    if ($permiso_dia) {
                        $factor_permiso = $permiso_dia->rrhhtipopermiso->factor ?? 1;
                        $ajuste_permiso = $valor_dia * ($factor_permiso - 1);
                        $info['concepto'] = 'Ausente (con permiso)';
                        $info['ajuste'] = $ajuste_permiso;
                        $total_ajustes += $ajuste_permiso;
                    } else {
                        $total_ajustes -= $valor_dia;
                        $detalle['descuentos']++;
                        $info['concepto'] = 'Descuento (ausente)';
                        $info['ajuste'] = -$valor_dia;
                    }
                }
            }
            $detalle['detalle_dias'][] = $info;
        }
        return [
            'ajuste' => round($total_ajustes, 2),
            'detalle' => $detalle
        ];
    }


    // function tieneDesignacionActiva($fecha)
    // {
    //     $fecha = Carbon::parse($fecha)->format('Y-m-d');

    //     return Designacione::where('estado', true)
    //         ->whereDate('fechaInicio', '<=', $fecha)
    //         ->whereDate('fechaFin', '>=', $fecha)
    //         ->exists();
    // }

    /**
     * Nuevo sistema de cálculo de sueldos:
     * - Cada día del mes solo puede ser normal o feriado, nunca ambos.
     * - Si es feriado:
     *     - Si el empleado marca: paga valor_dia * factor (ajuste = diferencia con valor_dia normal).
     *     - Si no marca: paga solo valor_dia normal.
     * - Si es día normal:
     *     - Si el empleado marca: paga valor_dia.
     *     - Si no marca: descuenta valor_dia.
     *     - Si solo marca ingreso o salida: descuenta valor_dia * 0.5.
     * - Los descuentos solo se aplican a días normales.
     * - El total de días pagados + descontados nunca supera los días del mes.
     */
    // protected function calcularSueldoPorAsistencia($contrato, $anio, $mes)
    // {
    //     $feriados = $this->getFeriados($anio);
    //     $feriados_map = collect($feriados)->keyBy('fecha');
    //     $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
    //     $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();
    //     $designaciones = Designacione::where('empleado_id', $contrato['empleado_id'])
    //         ->where('estado', true)
    //         ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
    //             $q->whereBetween('fechaInicio', [$fechaInicioMes, $fechaFinMes])
    //                 ->orWhereBetween('fechaFin', [$fechaInicioMes, $fechaFinMes])
    //                 ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
    //                     $q2->where('fechaInicio', '<=', $fechaInicioMes)
    //                         ->where('fechaFin', '>=', $fechaFinMes);
    //                 });
    //         })
    //         ->get();
    //     $asistencias = [];
    //     if ($designaciones->isNotEmpty()) {
    //         $asistencias = Asistencia::join('designaciones', 'designaciones.id', '=', 'asistencias.designacione_id')
    //             ->where('designaciones.empleado_id', $contrato['empleado_id'])
    //             ->whereBetween('fecha', [$fechaInicioMes->format('Y-m-d'), $fechaFinMes->format('Y-m-d')])
    //             ->get()
    //             ->keyBy(fn($item) => Carbon::parse($item->fecha)->format('Y-m-d'));
    //     }
    //     $periodo = new DatePeriod(
    //         new DateTime($fechaInicioMes),
    //         new DateInterval('P1D'),
    //         (new DateTime($fechaFinMes))->modify('+1 day')
    //     );
    //     $total_ajustes = 0;
    //     $detalle = [
    //         'normales_pagados' => 0,
    //         'feriados_sin_marca' => 0,
    //         'feriados_con_marca' => 0,
    //         'descuentos' => 0,
    //         'media_jornada' => 0,
    //         'feriados_detalle' => []
    //     ];
    //     foreach ($periodo as $date) {
    //         $fecha = $date->format('Y-m-d');
    //         $valor_dia = $contrato['valor_dia'];
    //         // Determinar si el día es feriado
    //         $feriado = $feriados_map[$fecha] ?? null;
    //         if ($designaciones->isEmpty()) {
    //             continue;
    //         }
    //         if ($feriado) {
    //             $factor_feriado = $feriado['factor'];
    //             if (isset($asistencias[$fecha])) {
    //                 $registro = $asistencias[$fecha];
    //                 if ($registro->ingreso && $registro->salida) {
    //                     // Feriado con marca: paga valor día * factor, ajuste es diferencia
    //                     $detalle['feriados_con_marca']++;
    //                     $total_ajustes += $valor_dia * ($factor_feriado - 1);
    //                     $detalle['feriados_detalle'][] = [
    //                         'fecha' => $fecha,
    //                         'factor' => $factor_feriado,
    //                         'tipo' => 'con_marca',
    //                         'monto' => round($valor_dia * $factor_feriado, 2)
    //                     ];
    //                 } else {
    //                     // Feriado sin marca: paga valor día normal
    //                     $detalle['feriados_sin_marca']++;
    //                     $detalle['feriados_detalle'][] = [
    //                         'fecha' => $fecha,
    //                         'factor' => $factor_feriado,
    //                         'tipo' => 'sin_marca',
    //                         'monto' => round($valor_dia, 2)
    //                     ];
    //                 }
    //             } else {
    //                 // Feriado sin marca: paga valor día normal
    //                 $detalle['feriados_sin_marca']++;
    //                 $detalle['feriados_detalle'][] = [
    //                     'fecha' => $fecha,
    //                     'factor' => $factor_feriado,
    //                     'tipo' => 'sin_marca',
    //                     'monto' => round($valor_dia, 2)
    //                 ];
    //             }
    //         } else {
    //             // Solo días normales pueden tener descuentos
    //             if (isset($asistencias[$fecha])) {
    //                 $registro = $asistencias[$fecha];
    //                 if ($registro->ingreso && $registro->salida) {
    //                     $detalle['normales_pagados']++;
    //                 } elseif ($registro->ingreso || $registro->salida) {
    //                     $total_ajustes -= $valor_dia * 0.5;
    //                     $detalle['media_jornada']++;
    //                 } else {
    //                     // Solo descontar si NO es feriado
    //                     $total_ajustes -= $valor_dia;
    //                     $detalle['descuentos']++;
    //                 }
    //             } else {
    //                 // Solo descontar si NO es feriado
    //                 $total_ajustes -= $valor_dia;
    //                 $detalle['descuentos']++;
    //             }
    //         }
    //     }
    //     return [
    //         'ajuste' => round($total_ajustes, 2),
    //         'detalle' => $detalle
    //     ];
    // }


    protected function getFeriados($anio)
    {
        $feriados = [];
        foreach ($this->feriados as $itemferiado) {
            $fecha = $itemferiado->fecha;
            $fecha_inicio = $itemferiado->fecha_inicio;
            $fecha_fin = $itemferiado->fecha_fin;

            if ($itemferiado->recurrente) {
                if (!is_null($itemferiado->fecha)) {
                    $dt = new DateTime($itemferiado->fecha);
                    $dt->setDate($anio, $dt->format('m'), $dt->format('d'));
                    $fecha = $dt->format('Y-m-d');
                }
                if (!is_null($itemferiado->fecha_inicio)) {
                    $dt = new DateTime($itemferiado->fecha_inicio);
                    $dt->setDate($anio, $dt->format('m'), $dt->format('d'));
                    $fecha_inicio = $dt->format('Y-m-d');
                }
                if (!is_null($itemferiado->fecha_fin)) {
                    $dt = new DateTime($itemferiado->fecha_fin);
                    $dt->setDate($anio, $dt->format('m'), $dt->format('d'));
                    $fecha_fin = $dt->format('Y-m-d');
                }
            }

            if ($fecha) {
                $feriados[] = ["fecha" => $fecha, "factor" => $itemferiado->factor];
            }
            if ($fecha_inicio) {
                $inicio = new DateTime($fecha_inicio);
                $fin = new DateTime($fecha_fin);
                $fin->modify('+1 day');
                $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fin);

                foreach ($periodo as $f) {
                    $feriados[] = ["fecha" => $f->format('Y-m-d'), "factor" => $itemferiado->factor];
                }
            }
        }

        return $feriados;
    }

    public function guardarSueldos()
    {
        DB::beginTransaction();
        try {
            foreach ($this->contratos as $contrato) {
                RrhhSueldoEmpleado::updateOrCreate(
                    [
                        'rrhhsueldo_id' => $this->rrhhsueldo->id,
                        'empleado_id' => $contrato['empleado_id'],
                        'rrhhcontrato_id' => $contrato['id'],
                    ],
                    [
                        'nombreempleado' => $contrato['nombres'] . ' ' . $contrato['apellidos'],
                        'total_permisos' => $contrato['total_permisos'] ?? 0,
                        'total_adelantos' => $contrato['total_adelantos'] ?? 0,
                        'total_bonosdescuentos' => $contrato['total_bonos'] ?? 0,
                        'total_ctrlasistencias' => $contrato['total_ctrlasistencias'] ?? 0,
                        'salario_mes' => $contrato['salario_mes'] ?? 0,
                        'liquido_pagable' => $contrato['liquido_pagable'] ?? 0,
                    ]
                );
            }
            $this->rrhhsueldo->estado = 'PROCESADO';
            $this->rrhhsueldo->save();
            DB::commit();
            return redirect()->route('admin.sueldos')->with('success', 'Resultados registrados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('error', 'Error al registrar resultados: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->emit('dataTableRender');
        return view('livewire.admin.procesar-sueldo', [
            'contratos' => $this->contratos,
            'gestion'   => $this->rrhhsueldo->gestion,
            'mes'       => $this->rrhhsueldo->mes,
            'feriados'  => $this->feriados,
        ])->extends('adminlte::page');
    }
}
