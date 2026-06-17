<?php

namespace App\Http\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Rrhhadelanto;
use App\Models\Rrhhbono;
use Livewire\Component;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdescuento;
use App\Models\Rrhhsueldo;
use App\Models\Rrhhferiado;
use App\Models\Rrhhpermiso;
use App\Models\Rrhhsueldoempleado;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

use function PHPUnit\Framework\isNull;

class ProcesarSueldo extends Component
{
    public $rrhhsueldo;
    public $contratos = [];
    public $feriados = [];
    public $procesado = false;
    public $contratosSeleccionados = [];
    public $seleccionarTodos = false;
    public $contratoSeleccionado = null;

    protected $listeners = ['guardarSueldos', 'setContratosSeleccionados'];



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

            return
                [
                    'id' => $contrato->id,
                    'empleado_id' => $contrato->empleado->id,
                    'nombres' => $contrato->empleado->nombres,
                    'apellidos' => $contrato->empleado->apellidos,
                    'fecha_inicio' => $contrato->fecha_inicio,
                    'fecha_fin' => $contrato->fecha_fin ?? 'Indefinido',
                    'salario_basico' => number_format($contrato->salario_basico, 2, '.', ''),
                    'tipo_contrato' => $contrato->rrhhtipocontrato->nombre ?? 'N/A',
                    'valor_dia' => '0',
                    'dias_procesables' => '0',
                    'salario_mes' => 0,
                    'total_ctrlasistencias' => 0,
                    'cant_inasistencias' => 0,
                    'total_marcaciones_incompletas' => 0,
                    'cant_marcaciones_incompletas' => 0,
                    'total_permisos' => 0,
                    'total_adelantos' => 0,
                    'total_bonos' => 0,
                    'ids_bonos' => [],
                    'total_descuentos' => 0,
                    'liquido_pagable' => 0,
                    'detalle_pago' => '0',
                    'calendario_laboral' => $dias_tipo,
                    'tipo_designacion' => '',
                    'bonos' => [],
                    'descuentos' => [],
                    'adelantos' => [],
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
            ->where('activo', true)
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

    public function verDetalles($contrato_id)
    {
        $this->contratoSeleccionado = collect($this->contratos)->firstWhere('id', $contrato_id);
        $calendario = $this->contratoSeleccionado['calendario_laboral'] ?? [];
        $this->emit('abrirModalDetalle');
    }

    public function procesarSueldos()
    {
        if (empty($this->contratosSeleccionados)) {
            $this->emit('error', 'Debe seleccionar al menos un contrato para procesar.');
            return;
        }
        $anio = $this->rrhhsueldo->gestion;
        $mes  = $this->rrhhsueldo->mes;
        $parametros = \App\Models\Sistemaparametro::first();

        // CREACION DEL ARRAY PARA LOS DIAS FERIADOS DEL MES, PARA FACILITAR LA VERIFICACION POSTERIOR EN EL PROCESO DE ASISTENCIAS
        $feriados = $this->getFeriadosMes();
        $dias_feriados = [];
        foreach ($feriados as $feriado) {
            $fecha_inicio = Carbon::parse($feriado->fecha_inicio ?? $feriado->fecha);
            $fecha_fin = Carbon::parse($feriado->fecha_fin ?? $feriado->fecha);

            // Agregar todos los días del feriado al array de días feriados
            $periodo = CarbonPeriod::create($fecha_inicio, $fecha_fin);
            foreach ($periodo as $dia) {
                $dias_feriados[] = $dia->format('Y-m-d');
            }
        }
        // FIN FERIADOS///////////////////////////////////////////////////////////////////////////////////////////////////////////

        $fechaInicioMes = Carbon::create($anio, $mes, 1)->startOfDay();
        $fechaFinMes = $fechaInicioMes->copy()->endOfMonth();

        // VERIFICAR ASISTENCIAS, PERMISOS Y FERIADOS PARA CADA CONTRATO
        $contratos = [];
        foreach ($this->contratosSeleccionados as $contrato_id) {

            $contrato = Rrhhcontrato::find($contrato_id);
            $tipo_desingacion = "";
            $liquido_pagable = $contrato->salario_basico;

            $valor_dia_laboral = number_format(($contrato->salario_basico / 30), 2);
            if ($contrato->fecha_inicio > $fechaInicioMes) {
                $fechaA = new DateTime($fechaInicioMes);
                $fechaB = new DateTime($contrato->fecha_inicio);
                $diferencia = $fechaA->diff($fechaB);
                $liquido_pagable -= ($diferencia->days * $valor_dia_laboral);
                $fechaInicioMes = $contrato->fecha_inicio;
            }
            // dd($liquido_pagable);
            $cant_permisos = 0;

            $cant_faltas_completas = 0;
            $cant_marcaciones_incompletas = 0;

            $total_inasistencias_completas = 0;
            $total_marcaciones_incompletas = 0;

            $designaciones = Designacione::where('empleado_id', $contrato->empleado->id)
                ->where('tipo', 'NORMAL')
                ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
                    $q->whereBetween('fechaInicio', [$fechaInicioMes, $fechaFinMes])
                        ->orWhereBetween('fechaFin', [$fechaInicioMes, $fechaFinMes])
                        ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
                            $q2->where('fechaInicio', '<=', $fechaInicioMes)
                                ->where('fechaFin', '>=', $fechaFinMes);
                        });
                })
                ->get();
            $ids_designaciones = $designaciones->pluck('id')->toArray();

            // Generar calendario laboral para el empleado
            $calendario = $this->generarCalendarioLaboral($contrato, $anio, $mes);
            // ASISTENCIAS
            $periodo = CarbonPeriod::create($fechaInicioMes, $fechaFinMes);
            foreach ($periodo as $fecha) {
                $asistencia = Asistencia::whereIn('designacione_id', $ids_designaciones)
                    ->whereDate('fecha', $fecha->format('Y-m-d'))
                    ->first();

                if (!$asistencia) {
                    // dd('no hay marcacion ' . $fecha, $asistencia);
                    $esFeriado = false;

                    // BUSCAR SI ES FERIADO
                    foreach ($dias_feriados as $dia_feriado) {
                        // dd('Buscando si ' . $fecha . ' es feriado');
                        if ($fecha->format('Y-m-d') === $dia_feriado) {
                            // dd('Es feriado');
                            // Es feriado, no se descuenta
                            $esFeriado = true; // Saltar al siguiente día del periodo
                        }
                    }


                    if ($esFeriado == false) {
                        // dd('No es feriado, buscando si ' . $fecha . ' es dia laborable segun designacion');
                        // si no es feriado, verificar si es día laborable según designación
                        // Dia de la semana (0=domingo, 1=lunes, ..., 6=sabado)
                        $dia_semana = $fecha->dayOfWeek;
                        // Verificar si el día es laborable según la designación
                        $esLaborable = true;

                        $fechaC = Carbon::parse($fecha);

                        $designacionesEnFecha = $designaciones->filter(function ($d) use ($fecha) {
                            return $fecha->between(
                                Carbon::parse($d->fechaInicio),
                                Carbon::parse($d->fechaFin)
                            );
                        })->first();


                        if ($designacionesEnFecha && $designacionesEnFecha->tipo_designacion != 'ADMIN') {
                            $designaciondia = $designacionesEnFecha->designaciondias->select('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado')->first()->toArray();
                            $tipo_desingacion = $designacionesEnFecha->tipo_designacion;
                        } else {

                            // CASO GENERICO SE CREA UN ARRAY
                            $designaciondia = [
                                'domingo' => 0,
                                'lunes' => 1,
                                'martes' => 1,
                                'miercoles' => 1,
                                'jueves' => 1,
                                'viernes' => 1,
                                'sabado' => 0
                            ];
                        }



                        $dias_php = [
                            0 => 'domingo',
                            1 => 'lunes',
                            2 => 'martes',
                            3 => 'miercoles',
                            4 => 'jueves',
                            5 => 'viernes',
                            6 => 'sabado',
                        ];

                        if ($designaciondia && isset($dias_php[$dia_semana]) && $designaciondia[$dias_php[$dia_semana]] == 0) {
                            // No es día laborable, no se descuenta
                            $esLaborable = false;
                        }


                        if ($esLaborable == true) {
                            $permiso = Rrhhpermiso::where('empleado_id', $contrato->empleado_id)
                                ->where('activo', true)
                                ->where('status', 'APROBADO')
                                ->where(function ($query) use ($fecha) {
                                    $query->whereDate('fecha_inicio', '<=', $fecha->format('Y-m-d'))
                                        ->whereDate('fecha_fin', '>=', $fecha->format('Y-m-d'));
                                })
                                ->first();

                            if (!$permiso) {
                                // No se encontraron razones para NO DESCONTAR, se procede a descontar como falta completa
                                if ($designacionesEnFecha && $designacionesEnFecha->tipo_designacion != 'ADMIN') {
                                    $total_inasistencias_completas += $parametros->falta_dia_completo; // Para mostrar en el detalle
                                    $cant_faltas_completas++;
                                }
                            }
                        }
                    }
                } else {

                    if ($asistencia->estado == 0) {
                        //   Existe ingreso y no salida - se aplicará las multas correspondientes
                        $total_marcaciones_incompletas += $parametros->asistencia_sin_salida; // Para mostrar en el detalle
                        $cant_marcaciones_incompletas++;
                    }
                }
            }

            // FIN ASISTENCIAS
            // PERMISOS
            // $permisos = Rrhhpermiso::where('rrhhcontrato_id', $contrato->id)
            //     ->where('activo', true)
            //     ->where('status', 'APROBADO')
            //     ->where(function ($q) use ($fechaInicioMes, $fechaFinMes) {
            //         $q->whereBetween('fecha_inicio', [$fechaInicioMes, $fechaFinMes])
            //             ->orWhereBetween('fecha_fin', [$fechaInicioMes, $fechaFinMes])
            //             ->orWhere(function ($q2) use ($fechaInicioMes, $fechaFinMes) {
            //                 $q2->where('fecha_inicio', '<=', $fechaInicioMes)
            //                     ->where('fecha_fin', '>=', $fechaFinMes);
            //             });
            //     })
            //     ->get();
            // foreach ($permisos as $permiso) {
            //     // contar los dias de permiso basado en la fecha de inicio y la fecha de fin
            //     $cant_permisos = Carbon::parse($permiso->fecha_inicio)->diffInDays(Carbon::parse($permiso->fecha_fin)) + 1;
            // }
            // FIN PERMISOS

            // BONOS
            $bonos = Rrhhbono::where('rrhhcontrato_id', $contrato->id)
                ->where('empleado_id', $contrato->empleado->id)
                ->where('estado', true)
                ->where('pagado', false)
                ->whereYear('fecha', $anio)
                ->whereMonth('fecha', $mes)
                ->get();
            $total_bonos = 0;
            $ids_bonos = [];
            foreach ($bonos as $bono) {
                $total_bonos += $bono->monto ?? 0;
                // $liquido_pagable += $bono->monto ?? 0; // Sumar el bono al líquido pagable
                $ids_bonos[] = $bono->id;
            }
            $total_bonos = round($total_bonos, 2);
            // FIN BONOS

            // DESCUENTOS
            $descuentos = Rrhhdescuento::where('rrhhcontrato_id', $contrato->id)
                ->where('empleado_id', $contrato->empleado->id)
                ->where('estado', true)
                ->whereYear('fecha', $anio)
                ->whereMonth('fecha', $mes)
                ->get();
            $total_descuentos = 0;
            foreach ($descuentos as $descuento) {
                $total_descuentos += $descuento->monto ?? 0;
                // $liquido_pagable -= $descuento->monto ?? 0; // Restar el descuento al líquido pagable
            }
            $total_descuentos = round($total_descuentos, 2);
            // FIN DESCUENTOS

            // ADELANTOS
            $total_adelantos = 0;
            $adelantos = Rrhhadelanto::where('rrhhcontrato_id', $contrato->id)
                ->where('empleado_id', $contrato->empleado->id)
                ->where('estado', 'APROBADO')
                ->whereYear('fecha', $anio)
                ->whereMonth('fecha', $mes)
                ->get();
            foreach ($adelantos as $adelanto) {
                $total_adelantos += $adelanto->monto ?? 0;
                // $liquido_pagable -= $adelanto->monto ?? 0; // Restar el adelanto al líquido pagable
            }
            // FIN ADELANTOS


            // AJUSTE LIQUIDO PAGABLE
            $liquido_ajustado = $liquido_pagable - $total_inasistencias_completas - $total_marcaciones_incompletas - $total_descuentos - $total_adelantos + $total_bonos;
            // FIN AJUSTE
            if ($liquido_ajustado < 0) {
                $liquido_ajustado = 0;
            }
            $contratos[] = [
                'id' => $contrato->id,
                'empleado_id' => $contrato->empleado->id,
                'nombres' => $contrato->empleado->nombres,
                'apellidos' => $contrato->empleado->apellidos,
                'fecha_inicio' => $contrato->fecha_inicio,
                'fecha_fin' => $contrato->fecha_fin ?? 'Indefinido',
                'salario_basico' => number_format($contrato->salario_basico, 2, '.', ''),
                'tipo_contrato' => $contrato->rrhhtipocontrato->nombre ?? 'N/A',
                'valor_dia' => '0',
                'dias_procesables' => '0',
                'salario_mes' => number_format($liquido_pagable, 2, '.', ''),
                'total_ctrlasistencias' => $total_inasistencias_completas,
                'cant_inasistencias' => $cant_faltas_completas,
                'total_marcaciones_incompletas' => $total_marcaciones_incompletas,
                'cant_marcaciones_incompletas' => $cant_marcaciones_incompletas,
                'total_permisos' => $cant_permisos,
                'total_adelantos' => $total_adelantos,
                'total_bonos' => $total_bonos,
                'ids_bonos' => $ids_bonos,
                'total_descuentos' => $total_descuentos,
                'liquido_pagable' => number_format($liquido_ajustado, 2, '.', ''),
                'detalle_pago' => '0',
                'calendario_laboral' => $calendario,
                'tipo_designacion' => $tipo_desingacion,
                'bonos' => $bonos,
                'descuentos' => $descuentos,
                'adelantos' => $adelantos,
            ];
        }
        $this->contratos = $contratos;
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

        $parametros = \App\Models\Sistemaparametro::first();

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
            $tipo_dia = 'NORMAL';
            $factor_feriado = null;
            $es_feriado = false;
            $designacion_activa = false;
            $asistencia = null;
            $estado_asistencia = null;
            $descuento = 0;
            $permiso = null;
            // Verificar si está dentro del contrato
            if (Carbon::parse($fecha)->lt($fechaInicioContrato) || Carbon::parse($fecha)->gt($fechaFinContrato)) {
                $tipo_dia = 'FUERA CONTRATO';
            } else {
                // Verificar designación activa (considerar también designaciones finalizadas si la fecha de fin es >= al día)
                foreach ($designaciones as $desig) {
                    if (Carbon::parse($fecha)->between(Carbon::parse($desig->fechaInicio), Carbon::parse($desig->fechaFin))) {
                        $designacion_activa = true;
                        break;
                    }
                }
                // Verificar Permiso
                $permiso = Rrhhpermiso::where('empleado_id', $contrato->empleado->id)
                    ->where('activo', true)
                    ->where('status', 'APROBADO')
                    ->where(function ($query) use ($fecha) {
                        $query->whereDate('fecha_inicio', '<=', $fecha)
                            ->whereDate('fecha_fin', '>=', $fecha);
                    })
                    ->first();
                if ($permiso) {
                    $tipo_dia = 'PERMISO';
                }

                // Verificar feriado
                if ($feriados->has($fecha)) {
                    $tipo_dia = 'FERIADO';
                    $es_feriado = true;
                    $factor_feriado = $feriados[$fecha]['factor'];
                }

                // Asistencia
                if ($tipo_dia !== 'PERMISO' && $tipo_dia !== 'FERIADO') {
                    if ($asistencias && isset($asistencias[$fecha])) {
                        $asistencia = $asistencias[$fecha];
                        if ($asistencia->ingreso && $asistencia->salida) {
                            $estado_asistencia = 'completa';
                        } elseif ($asistencia->ingreso || $asistencia->salida) {
                            $estado_asistencia = 'media_jornada';
                            $descuento = $parametros->asistencia_sin_salida; // Descuento fijo por falta media jornada
                        } else {
                            $estado_asistencia = 'sin_marca';
                            $descuento = $parametros->falta_dia_completo; // Descuento fijo por falta completa
                        }
                    } else {
                        $estado_asistencia = 'sin_marca';
                        $descuento = $parametros->falta_dia_completo; // Descuento fijo por falta completa
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
                'permiso' => $permiso,
                'descuento' => $descuento ?? null,
            ];
        }
        array_pop($calendario);

        return $calendario;
    }
    protected function generarCalendarioLaboral_ori($contrato, $anio, $mes)
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
            // dd($this->rrhhsueldo->rrhhsueldoempleados);
            $this->rrhhsueldo->rrhhsueldoempleados()->delete();
            foreach ($this->contratos as $contrato) {

                $sueldoEmpleado = Rrhhsueldoempleado::create(
                    [
                        'rrhhsueldo_id' => $this->rrhhsueldo->id,
                        'empleado_id' => $contrato['empleado_id'],
                        'rrhhcontrato_id' => $contrato['id'],

                        'nombreempleado' => $contrato['nombres'] . ' ' . $contrato['apellidos'],
                        'total_permisos' => $contrato['total_permisos'] ?? 0,
                        'total_adelantos' => $contrato['total_adelantos'] ?? 0,
                        'total_bonos' => $contrato['total_bonos'] ?? 0,
                        'total_descuentos' => $contrato['total_descuentos'] ?? 0,
                        'total_ctrlasistencias' => $contrato['total_ctrlasistencias'] ?? 0,
                        'total_marcaciones_incompletas' => $contrato['total_marcaciones_incompletas'] ?? 0,
                        'salario_mes' => $contrato['salario_mes'] ?? 0,
                        'liquido_pagable' => $contrato['liquido_pagable'] ?? 0,
                    ]
                );
                $ids_bonos_text = implode('|', $contrato['ids_bonos'] ?? []);
                $sueldoEmpleado->ids_bonos = $ids_bonos_text;
                $sueldoEmpleado->save();

                foreach ($contrato['ids_bonos'] ?? [] as $bono_id) {
                    $bono = \App\Models\Rrhhbono::find($bono_id);
                    if ($bono) {
                        $bono->pagado = true;
                        $bono->save();
                    }
                }
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
