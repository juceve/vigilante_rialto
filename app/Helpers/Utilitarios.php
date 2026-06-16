<?php

use App\Http\Livewire\Vigilancia\HombreVivo;
use App\Models\Asistencia;
use App\Models\Citecobro;
use App\Models\Citecotizacion;
use App\Models\Citeinforme;
use App\Models\Citememorandum;
use App\Models\Citerecibo;
use App\Models\ConversionNumeros;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Designacionsupervisor;
use App\Models\Dialibre;
use App\Models\Hombrevivo as ModelsHombrevivo;
use App\Models\Inspeccion;
use App\Models\Intervalo;
use App\Models\Marcacione;
use App\Models\Regronda;
use App\Models\Rondaejecutada;
use App\Models\Rrhhcontrato;
use App\Models\Tarea;
use App\Models\Usercliente;
use App\Models\Vwnovedade;
use App\Models\Vwpanico;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

function encriptar($data)
{
    $encryptData = Crypt::encrypt($data);
    return $encryptData;
}

function numDecimal($cantidad)
{
    $formateado = number_format($cantidad, 2, '.');
    return $formateado;
}

function tablaRondas($designacione_id)
{
    $designacione = Designacione::find($designacione_id);
    $diaslaborables = Designaciondia::where('designacione_id', $designacione_id)
        ->select('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo')
        ->first();
    $diaslaborables = $diaslaborables->toArray();
    $ctrlpuntos = $designacione->turno->ctrlpuntos;
    $diasL = array(
        "",
        $diaslaborables['lunes'],
        $diaslaborables['martes'],
        $diaslaborables['miercoles'],
        $diaslaborables['jueves'],
        $diaslaborables['viernes'],
        $diaslaborables['sabado'],
        $diaslaborables['domingo']
    );
    $rondas = [];
    $actual = new DateTime($designacione->fechaInicio);
    $final = new DateTime($designacione->fechaFin);

    while ($actual <= $final) {
        $numeral = date('N', strtotime($actual->format('Y-m-d')));
        if ($diasL[$numeral]) {
            $fecha = $actual->format('Y-m-d');
            if (!esDiaLibre2($designacione_id, $fecha)) {
                $rondaA = [];
                $rondaA[] = array($fecha, 0);
                foreach ($ctrlpuntos as $punto) {
                    $ronda = Regronda::where([
                        ['designacione_id', $designacione_id],
                        ['fecha', $fecha],
                        ['ctrlpunto_id', $punto->id]
                    ])->first();

                    if ($ronda) {
                        if (hayRetraso($ronda->hora, $punto->hora)) {
                            $rondaA[] = array($ronda->hora, 2, $ronda->id);
                        } else {
                            $rondaA[] = array($ronda->hora, 0, $ronda->id);
                        }
                    } else {
                        if ($fecha <= date('Y-m-d')) {
                            $rondaA[] = array('X', 1, "");
                        } else {
                            $rondaA[] = array('--', 0, "");
                        }
                    }
                }
                $rondas[] = $rondaA;
            }
        }
        $actual->modify('+1 day');
    }
    return $rondas;
}

function tablaMarcaciones($designacione_id)
{
    $designacione = Designacione::find($designacione_id);
    $diaslaborables = Designaciondia::where('designacione_id', $designacione_id)
        ->select('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo')
        ->first();
    $diaslaborables = $diaslaborables->toArray();
    $horainicio = $designacione->turno->horainicio;
    $horafin = $designacione->turno->horafin;


    $diasL = array(
        "",
        $diaslaborables['lunes'],
        $diaslaborables['martes'],
        $diaslaborables['miercoles'],
        $diaslaborables['jueves'],
        $diaslaborables['viernes'],
        $diaslaborables['sabado'],
        $diaslaborables['domingo']
    );
    $marcaciones = [];
    $actual = new DateTime($designacione->fechaInicio);
    $final = new DateTime($designacione->fechaFin);

    while ($actual <= $final) {
        $numeral = date('N', strtotime($actual->format('Y-m-d')));
        // dd($diasL[$numeral]);
        if ($diasL[$numeral]) {
            $fecha = $actual->format('Y-m-d');
            if (!esDiaLibre2($designacione_id, $fecha)) {
                $marcado = [];
                $marcadoA = array($fecha, 0, 0, 0, 0);
                $marcacionDia = Marcacione::where([
                    ['fecha', $fecha],
                    ['designacione_id', $designacione_id]
                ])->get()->toArray();
                if (count($marcacionDia) > 0) {
                    $marcadoA[1] = $marcacionDia[0]['hora'];
                    $marcadoA[3] = $marcacionDia[0]['id'];
                    if (count($marcacionDia) > 1) {
                        $marcadoA[2] = $marcacionDia[1]['hora'];
                        $marcadoA[4] = $marcacionDia[1]['id'];
                    }
                } else {
                    $marcadoA[1] = 1;
                    $marcadoA[2] = 1;
                }
                $marcaciones[] = $marcadoA;
            }
        }

        $actual->modify('+1 day');
    }

    return $marcaciones;
}

function hayRetraso($hora_marcado, $hora_programada)
{
    $horaInicio = $hora_programada;
    $horaLlegada = $hora_marcado;

    $inicio = new DateTime($horaInicio);
    $llegada = new DateTime($horaLlegada);
    // $inicio->modify('-5 minutes');
    $interval = $inicio->diff($llegada);
    $retrasoMinutos = $interval->format('%i');

    if ($llegada > $inicio) {
        return true;
    } else {
        return false;
    }
}

function esDiaLibre($designacione_id)
{
    // Optimización: usar exists() en lugar de get() y count()
    return Dialibre::where([
        ['fecha', date('Y-m-d')],
        ['designacione_id', $designacione_id]
    ])->exists();
}


function esDiaLibre2($designacione_id, $fecha)
{
    // Optimización: usar exists() en lugar de get() y count()
    return Dialibre::where([
        ['fecha', $fecha],
        ['designacione_id', $designacione_id]
    ])->exists();
}

function traeDesignacionActiva($empleado_id)
{
    $hoy = Carbon::today()->toDateString();

    $designacione = Designacione::where('empleado_id', $empleado_id)
        ->where('estado', 1)
        ->whereDate('fechaInicio', '<=', $hoy)
        ->where(function ($q) use ($hoy) {
            $q->whereNull('fechaFin')
                ->orWhereDate('fechaFin', '>=', $hoy);
        })
        ->orderBy('id', 'DESC')
        ->first();

    return $designacione;
}
function traeTodasDesignaciones($empleado_id, $inicio, $fin)
{
    return Designacione::where('empleado_id', $empleado_id)
        ->where(function ($q) use ($inicio, $fin) {
            $q->where('fechaInicio', '<=', $fin)
                ->where('fechaFin', '>=', $inicio);
        })
        ->get();
}

function yaMarque($designacione_id)
{
    try {
        $designacione = Designacione::find($designacione_id);

        // Validar que existe la designación y el turno
        if (!$designacione || !$designacione->turno) {
            return 2; // Por seguridad, asumir que ya marcó todo
        }

        $hoy = date('Y-m-d');
        $horaingreso = new DateTime($hoy . " " . $designacione->turno->horainicio);
        $horaingreso = $horaingreso->modify('-1 hours');
        $horaingreso = $horaingreso->format('H:i');
        $horaactual = date('H:i');

        if ($designacione->turno->horainicio < $designacione->turno->horafin) {
            // DIURNO
            $marcacion = Asistencia::where([
                ['designacione_id', $designacione_id],
                ['fecha', $hoy],
            ])->first();

            if ($marcacion) {
                if ($marcacion->ingreso && $marcacion->salida) {
                    return 2; // Ya marcó ingreso y salida
                } else {
                    return 1; // Solo marcó ingreso
                }
            } else {
                if ($horaactual >= $horaingreso) {
                    return 0; // Puede marcar ingreso
                } else {
                    return 2; // Fuera de horario
                }
            }
        } else {
            // NOCTURNO
            $ayer = new DateTime($hoy);
            $ayer = $ayer->modify('-1 days');
            $ayer = $ayer->format('Y-m-d');

            $marcacion = null;
            if ($horaactual > $horaingreso) {
                $marcacion = Asistencia::where('designacione_id', $designacione_id)
                    ->where('fecha', $hoy)
                    ->first();
            } else {
                $marcacion = Asistencia::where('designacione_id', $designacione_id)
                    ->where('fecha', $ayer)
                    ->first();
            }

            if ($marcacion) {
                if ($marcacion->ingreso && $marcacion->salida) {
                    return 2; // Ya marcó ingreso y salida
                } else {
                    return 1; // Solo marcó ingreso
                }
            } else {
                if ($horaactual >= $horaingreso) {
                    return 0; // Puede marcar ingreso
                } else {
                    return 2; // Fuera de horario
                }
            }
        }
    } catch (Exception $e) {
        // En caso de error, log y retornar valor seguro
        Log::error('Error en yaMarque: ' . $e->getMessage());
        return 2; // Por seguridad, asumir que ya marcó todo
    }
}

function yaMarque3($designacione_id)
{
    try {
        $designacione = Designacionsupervisor::find($designacione_id);
        $turno = $designacione->turnoguardia;

        var_dump($designacione);

        // Validar que existe la designación y el turno
        if (!$designacione || !$turno) {
            return 2; // Por seguridad, asumir que ya marcó todo
        }

        $hoy = date('Y-m-d');
        $horaingreso = new DateTime($hoy . " " . $designacione->turno->horainicio);
        $horaingreso = $horaingreso->modify('-1 hours');
        $horaingreso = $horaingreso->format('H:i');
        $horaactual = date('H:i');

        if ($designacione->turno->horainicio < $designacione->turno->horafin) {
            // DIURNO
            $marcacion = Asistencia::where([
                ['designacione_id', $designacione_id],
                ['fecha', $hoy],
            ])->first();

            if ($marcacion) {
                if ($marcacion->ingreso && $marcacion->salida) {
                    return 2; // Ya marcó ingreso y salida
                } else {
                    return 1; // Solo marcó ingreso
                }
            } else {
                if ($horaactual >= $horaingreso) {
                    return 0; // Puede marcar ingreso
                } else {
                    return 2; // Fuera de horario
                }
            }
        } else {
            // NOCTURNO
            $ayer = new DateTime($hoy);
            $ayer = $ayer->modify('-1 days');
            $ayer = $ayer->format('Y-m-d');

            $marcacion = null;
            if ($horaactual > $horaingreso) {
                $marcacion = Asistencia::where('designacione_id', $designacione_id)
                    ->where('fecha', $hoy)
                    ->first();
            } else {
                $marcacion = Asistencia::where('designacione_id', $designacione_id)
                    ->where('fecha', $ayer)
                    ->first();
            }

            if ($marcacion) {
                if ($marcacion->ingreso && $marcacion->salida) {
                    return 2; // Ya marcó ingreso y salida
                } else {
                    return 1; // Solo marcó ingreso
                }
            } else {
                if ($horaactual >= $horaingreso) {
                    return 0; // Puede marcar ingreso
                } else {
                    return 2; // Fuera de horario
                }
            }
        }
    } catch (Exception $e) {
        // En caso de error, log y retornar valor seguro
        Log::error('Error en yaMarque: ' . $e->getMessage());
        return 2; // Por seguridad, asumir que ya marcó todo
    }
}

// function yaMarqueGeneral($empleado_id)
// {

//     try {
//         $designacione = traeDesignacionActiva($empleado_id);
//         if (!$designacione || !$designacione->designacionturno->turnoguardia) {
//             return [4, null, null]; // No hay designación activa
//         }

//         $turno = $designacione->designacionturno->turnoguardia;

//         // Validar que existe la designación y el turno
//         $ahora = Carbon::now();
//         $horaInicio = $turno->horainicio;
//         $horaFin = $turno->horafin;

//         // Determinar si es turno diurno o nocturno
//         $esTurnoDiurno = $horaInicio < $horaFin;
// // dd($esTurnoDiurno);
//         // Crear objetos Carbon para comparación correcta
//         $horaActualCarbon = Carbon::parse($ahora->format('Y-m-d') . ' ' . $ahora->format('H:i:s'));
//         $horaInicioCarbon = Carbon::parse($ahora->format('Y-m-d') . ' ' . $horaInicio)->subHour();
// dd($horaActualCarbon, $horaInicioCarbon);
//         // Determinar la fecha de búsqueda
//         if ($esTurnoDiurno) {
//             $fechaBusqueda = $ahora->format('Y-m-d');
//         } else {




//             // NOCTURNO: Si la hora actual es mayor o igual a la hora de inicio, buscar hoy, sino ayer
//             $fechaBusqueda = $horaActualCarbon->gte($horaInicioCarbon->copy()->subHour())
//                 ? $ahora->format('Y-m-d')
//                 : $ahora->copy()->subDay()->format('Y-m-d');

//         }

//         // Buscar marcación
//         $marcacion = Asistencia::select('ingreso', 'salida')
//             ->where('designacione_id', $designacione->id)
//             ->where('fecha', $fechaBusqueda)
//             ->first();

//         // Si encontró marcación
//         if ($marcacion) {
//             if ($marcacion->ingreso && $marcacion->salida) {
//                 // Estado 2: Turno completo
//                 return [2, $marcacion->ingreso, $marcacion->salida];
//             }
//             // Estado 1: Marcó ingreso, pendiente salida
//             return [1, $marcacion->ingreso, null];
//         }

//         // No hay marcación - Verificar si ya pasó la hora de inicio
//         if ($horaActualCarbon->gte($horaInicioCarbon)) {
//             // Estado 0: Ya es hora de marcar y no marcó (CRÍTICO)
//             return [0, null, null];
//         } else {
//             // Estado 3: Aún no es hora de marcar (EN DESCANSO)
//             return [3, null, null];
//         }
//     } catch (Exception $e) {

//         Log::error('Error en yaMarque2: ' . $e->getMessage(), [
//             'designacione_id' => $designacione->id,
//             'trace' => $e->getTraceAsString()
//         ]);
//         return [2, null, null];
//     }
// }

function yaMarqueGeneral($empleado_id)
{
    try {
        $designacione = traeDesignacionActiva($empleado_id);

        if (!$designacione || !$designacione->designacionturno->turnoguardia) {
            return [4, null, null]; // No hay designación activa
        }

        $turno = $designacione->designacionturno->turnoguardia;

        $ahora = Carbon::now();

        $horaInicio = $turno->horainicio;
        $horaFin = $turno->horafin;

        // Detectar si es turno diurno o nocturno
        $esTurnoDiurno = $horaInicio < $horaFin;

        // Construir horas base
        $horaInicioCarbon = Carbon::parse($ahora->format('Y-m-d') . ' ' . $horaInicio);
        $horaActualCarbon = Carbon::parse($ahora->format('Y-m-d H:i:s'));

        // ⏱️ Ventana de anticipación
        $ventanaAnticipacion = 2 * 60; // minutos
        $horaPermitidaIngreso = $horaInicioCarbon->copy()->subMinutes($ventanaAnticipacion);

        // 📅 Determinar fecha correcta
        if ($esTurnoDiurno) {
            $fechaBusqueda = $ahora->format('Y-m-d');
        } else {
            $fechaBusqueda = $horaActualCarbon->gte($horaPermitidaIngreso)
                ? $ahora->format('Y-m-d')
                : $ahora->copy()->subDay()->format('Y-m-d');
        }

        // 🔎 Buscar marcación
        $marcacion = Asistencia::select('ingreso', 'salida')
            ->where('designacione_id', $designacione->id)
            ->where('fecha', $fechaBusqueda)
            ->first();

        if ($marcacion) {
            if ($marcacion->ingreso && $marcacion->salida) {
                return [2, $marcacion->ingreso, $marcacion->salida];
            }
            return [1, $marcacion->ingreso, null];
        }

        // 🧠 Lógica corregida

        $puedeMarcar = $horaActualCarbon->gte($horaPermitidaIngreso);

        if (!$puedeMarcar) {
            return [3, null, null]; // Aún no puede marcar
        }

        // 👉 Aquí devolvemos 0 SOLO cuando realmente ya puede marcar
        return [0, null, null];
    } catch (Exception $e) {

        Log::error('Error en yaMarqueGeneral: ' . $e->getMessage(), [
            'empleado_id' => $empleado_id,
            'trace' => $e->getTraceAsString()
        ]);

        return [2, null, null];
    }
}


function yaMarque2($designacione_id)
{
    try {
        $designacione = Designacione::select('id', 'turno_id')
            ->with(['turno:id,horainicio,horafin'])
            ->find($designacione_id);

        if (!$designacione || !$designacione->turno) {
            return [2, null, null]; // Estado 2: Sin información
        }

        $turno = $designacione->turno;
        $ahora = Carbon::now();
        $horaInicio = $turno->horainicio;
        $horaFin = $turno->horafin;

        // Determinar si es turno diurno o nocturno
        $esTurnoDiurno = $horaInicio < $horaFin;

        // Crear objetos Carbon para comparación correcta
        $horaActualCarbon = Carbon::parse($ahora->format('Y-m-d') . ' ' . $ahora->format('H:i:s'));
        $horaInicioCarbon = Carbon::parse($ahora->format('Y-m-d') . ' ' . $horaInicio);

        // Determinar la fecha de búsqueda
        if ($esTurnoDiurno) {
            $fechaBusqueda = $ahora->format('Y-m-d');
        } else {
            // NOCTURNO: Si la hora actual es mayor o igual a la hora de inicio, buscar hoy, sino ayer
            $fechaBusqueda = $horaActualCarbon->gte($horaInicioCarbon->copy()->subHour())
                ? $ahora->format('Y-m-d')
                : $ahora->copy()->subDay()->format('Y-m-d');
        }

        // Buscar marcación
        $marcacion = Asistencia::select('ingreso', 'salida')
            ->where('designacione_id', $designacione_id)
            ->where('fecha', $fechaBusqueda)
            ->first();

        // Si encontró marcación
        if ($marcacion) {
            if ($marcacion->ingreso && $marcacion->salida) {
                // Estado 2: Turno completo
                return [2, $marcacion->ingreso, $marcacion->salida];
            }
            // Estado 1: Marcó ingreso, pendiente salida
            return [1, $marcacion->ingreso, null];
        }

        // No hay marcación - Verificar si ya pasó la hora de inicio
        if ($horaActualCarbon->gte($horaInicioCarbon)) {
            // Estado 0: Ya es hora de marcar y no marcó (CRÍTICO)
            return [0, null, null];
        } else {
            // Estado 3: Aún no es hora de marcar (EN DESCANSO)
            return [3, null, null];
        }
    } catch (Exception $e) {
        Log::error('Error en yaMarque2: ' . $e->getMessage(), [
            'designacione_id' => $designacione_id,
            'trace' => $e->getTraceAsString()
        ]);
        return [2, null, null];
    }
}

function tengoPanicos($user_id, $cliente_id)
{
    $hoy = date('Y-m-d');
    $panicos = Vwpanico::where([
        ['cliente_id', $cliente_id],
        ['user_id', $user_id],
        ['fecha', $hoy],
        ['visto', 0],
    ])->get();

    return $panicos->count();
}

function crearIntervalo($horaI, $horaF, $intervalo)
{
    $intervalos = [];

    // Validación básica
    if (empty($horaI) || empty($horaF) || $intervalo <= 0) {
        return $intervalos;
    }

    // Fecha base (hoy)
    $hoy = date('Y-m-d');

    // Crear DateTime para inicio y final en la misma fecha
    $inicio = new DateTime($hoy . ' ' . $horaI);
    $final = new DateTime($hoy . ' ' . $horaF);

    // Si el final es menor o igual al inicio, asumimos que termina al día siguiente
    if ($final <= $inicio) {
        $final->modify('+1 day');
    }

    // Generar intervalos incluyendo la hora inicial y avanzando por unidades de $intervalo horas
    $cursor = clone $inicio;
    $safety = 0;
    $maxLoops = 1000; // prevención contra bucles infinitos

    while ($cursor < $final && $safety < $maxLoops) {
        $intervalos[] = $cursor->format('H:i');
        $cursor->modify("+" . $intervalo . " hour");
        $safety++;
    }

    return $intervalos;
}

function verificaHV($designacione_id)
{
    try {
        $designacione = Designacione::find($designacione_id);

        if (!$designacione) {
            return false;
        }

        $hora = date('H:') . '00';
        $intervalo = Intervalo::where([
            ['designacione_id', $designacione->id],
            ['hora', $hora],
        ])->first();

        if ($intervalo) {
            // Optimización: usar exists() en lugar de first()
            $yaReportado = ModelsHombrevivo::where([
                ['intervalo_id', $intervalo->id],
                ['fecha', date('Y-m-d')]
            ])->exists();

            return $yaReportado ? false : $intervalo;
        }

        return false;
    } catch (Exception $e) {
        Log::error('Error en verificaHV: ' . $e->getMessage());
        return false;
    }
}

function verificaTareas($designacione_id)
{
    try {
        $designacione = Designacione::find($designacione_id);

        if (!$designacione || !$designacione->turno) {
            return false;
        }

        // Optimización: usar exists() en lugar de get() y count()
        return Tarea::where([
            ["cliente_id", $designacione->turno->cliente_id],
            ["empleado_id", $designacione->empleado_id],
            ["fecha", date('Y-m-d')],
            ["estado", 1],
        ])->exists();
    } catch (Exception $e) {
        Log::error('Error en verificaTareas: ' . $e->getMessage());
        return false;
    }
}

function registrosHV($designacione_id)
{
    $designacione = Designacione::find($designacione_id);
    $diaslaborables = Designaciondia::where('designacione_id', $designacione_id)
        ->select('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo')
        ->first();
    $diaslaborables = $diaslaborables->toArray();
    $intervalos = $designacione->intervalos;
    $diasL = array(
        "",
        $diaslaborables['lunes'],
        $diaslaborables['martes'],
        $diaslaborables['miercoles'],
        $diaslaborables['jueves'],
        $diaslaborables['viernes'],
        $diaslaborables['sabado'],
        $diaslaborables['domingo']
    );
    $rondas = [];
    $actual = new DateTime($designacione->fechaInicio);
    $final = new DateTime($designacione->fechaFin);

    while ($actual <= $final) {
        $numeral = date('N', strtotime($actual->format('Y-m-d')));
        if ($diasL[$numeral]) {
            $fecha = $actual->format('Y-m-d');
            if (!esDiaLibre2($designacione_id, $fecha)) {
                $rondaA = [];
                $rondaA[] = array($fecha, 0);
                foreach ($intervalos as $intervalo) {
                    $hv = ModelsHombrevivo::where([
                        ['intervalo_id', $intervalo->id],
                        ['fecha', $fecha]
                    ])->first();

                    if ($hv) {

                        $rondaA[] = array($hv->hora, 0, $hv->id);
                    } else {
                        if ($fecha <= date('Y-m-d')) {
                            $rondaA[] = array('X', 1, "");
                        } else {
                            $rondaA[] = array('--', 2, "");
                        }
                    }
                }
                $rondas[] = $rondaA;
            }
        }
        $actual->modify('+1 day');
    }
    return $rondas;
}

function fechaEs($fecha)
{
    $fmt = new IntlDateFormatter(
        'es_VE',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'America/Caracas',
        IntlDateFormatter::GREGORIAN,
        "dd 'de' MMMM 'de' yyyy"
    );
    return ucfirst($fmt->format(new DateTime($fecha)));
}

function ultDiaMes($fecha)
{
    $L = new DateTime($fecha);
    $literal = $L->format('Y-m-t');
    $literal = strtotime($literal);
    setlocale(LC_TIME, 'es_VE.UTF-8', 'esp');
    $literal = strftime('%e de %B de %Y', $literal);

    return $literal;
}

function traeCiteInforme($cite_id)
{
    $citeinforme = Citeinforme::find($cite_id);
    if (Auth::user()->template == "CLIENTE") {
        $usuariocliente = Usercliente::where('user_id', Auth::user()->id)->first();
        if ($citeinforme->cliente_id == $usuariocliente->cliente_id) {
            return $citeinforme->toArray();
        } else {
            return "";
        }
    } else {
        return $citeinforme->toArray();
    }
}
function traecitememo($cite_id)
{
    $citememo = Citememorandum::find($cite_id);

    return $citememo->toArray();
}

function traeCitecobro($cobro_id)
{
    $citecobro = Citecobro::find($cobro_id);
    if (Auth::user()->template == "CLIENTE") {
        $usuariocliente = Usercliente::where('user_id', Auth::user()->id)->first();
        if ($citecobro->cliente_id == $usuariocliente->cliente_id) {
            return $citecobro->toArray();
        } else {
            return "";
        }
    } else {
        return $citecobro->toArray();
    }
}
function traeCiterecibo($recibo_id)
{
    $citerecibo = Citerecibo::find($recibo_id);
    if (Auth::user()->template == "CLIENTE") {
        $usuariocliente = Usercliente::where('user_id', Auth::user()->id)->first();
        if ($citerecibo->cliente_id == $usuariocliente->cliente_id) {
            return $citerecibo->toArray();
        } else {
            return "";
        }
    } else {
        return $citerecibo->toArray();
    }
}
function traeCitecotizacion($cotizacion_id)
{
    $citecotizacion = Citecotizacion::find($cotizacion_id);

    return $citecotizacion->toArray();
}

function traeDetallesCotizacion($cotizacion_id)
{
    $citecotizacion = Citecotizacion::find($cotizacion_id);

    return $citecotizacion->detalles->toArray();
}

function numLiteral($monto)
{
    $conversiones = new ConversionNumeros();
    $literal = $conversiones->toInvoice($monto, 2, 'bolivianos');

    return $literal;
}

function codGet($myString)
{
    $myString = str_replace("/", "^&10&^", $myString);
    return $myString;
}
function decodGet($myString)
{
    $myString = str_replace("^&10&^", "/", $myString);
    return $myString;
}

function cerosIzq($num)
{
    $num = str_pad($num, 5, '0', STR_PAD_LEFT);
    return $num;
}
function cerosIzq2($num)
{
    $num = str_pad($num, 4, '0', STR_PAD_LEFT);
    return $num;
}

function verifAirbnb($fechaFutura)
{
    // Obtener la fecha y hora actual
    $ahora = Carbon::now();

    // Calcular la diferencia entre la fecha actual y la fecha futura
    $diferencia = $ahora->diff($fechaFutura);

    // Convertir la diferencia en horas
    $diferenciaEnHoras = ($diferencia->days * 24) + $diferencia->h + ($diferencia->i / 60);

    // Si la fecha futura ya pasó, marcar como 'danger'
    if ($fechaFutura <= $ahora) {
        return 'danger text-danger';  // Si la fecha futura ya ha pasado
    } elseif ($diferenciaEnHoras <= 1) {
        return 'danger';  // Menos de 1 hora
    } elseif ($diferenciaEnHoras <= 24) {
        return 'warning';  // Mayor a 1 hora y menor o igual a 1 día
    } else {
        return 'success';  // Mayor a 1 día
    }
}

function diffDays($fecha1)
{
    $fecha1 = Carbon::parse($fecha1);
    $fecha2 = Carbon::parse(now());
    $dias = $fecha1->diffInDays($fecha2);
    return $dias;
}
function diffHours($fecha1)
{
    $fecha1 = Carbon::parse($fecha1);
    $fecha2 = Carbon::parse(now());
    $horas = $fecha1->diffInHours($fecha2);
    return $horas;
}
function diffMinutes($fecha1)
{
    $fecha1 = Carbon::parse($fecha1);
    $fecha2 = Carbon::parse(now());
    $minutos = $fecha1->diffInMinutes($fecha2);
    return $minutos;
}

function formatearFecha($fecha)
{
    return Carbon::parse($fecha)->format('d/m/Y');
}

function tengoRondaIniciada($user_id, $cliente_id)
{
    $ronda = Rondaejecutada::where([
        ['cliente_id', $cliente_id],
        ['user_id', $user_id],
        ['status', 'EN_PROGRESO'],
    ])->first();

    return $ronda ? $ronda->id : 0;
}

function tieneDesignacionesCliente($cliente_id)
{
    $count = Designacione::where('estado', 1)

        ->whereHas('turno', function ($query) use ($cliente_id) {
            $query->where('cliente_id', $cliente_id);
        })->count();

    return $count > 0;
}

function traerDesignacionContrato($rrhhcontrato_id)
{
    try {
        $contrato = Rrhhcontrato::find($rrhhcontrato_id);

        // Si no hay contrato o no tiene empleado, devolver null
        if (!$contrato || empty($contrato->empleado_id)) {
            return null;
        }

        // Fecha de inicio obligatoria en el contrato
        if (empty($contrato->fecha_inicio)) {
            return null;
        }

        $empleadoId = $contrato->empleado_id;
        $inicioContrato = Carbon::parse($contrato->fecha_inicio)->startOfDay();
        $finContrato = $contrato->fecha_fin ? Carbon::parse($contrato->fecha_fin)->endOfDay() : null;

        // Query: mismas condiciones básicas
        $query = Designacione::where('empleado_id', $empleadoId)
            ->where('estado', 1);

        // Reglas de inclusión estricta (designacion DENTRO del contrato)
        // Si contrato tiene fecha_fin, exigir:
        //   D.fechaInicio >= C.fecha_inicio AND D.fechaFin <= C.fecha_fin
        if ($finContrato) {
            $query->whereDate('fechaInicio', '>=', $inicioContrato->toDateString())
                ->whereDate('fechaFin', '<=', $finContrato->toDateString());
        } else {
            // Contrato indefinido: exigir que la designacion comience a partir de la fecha_inicio del contrato
            $query->whereDate('fechaInicio', '>=', $inicioContrato->toDateString());
        }

        // Ordenar por la más reciente (por fechaInicio / id) y devolver la primera coincidencia
        return $query->orderBy('fechaInicio', 'ASC')->orderBy('id', 'ASC')->first();
    } catch (Exception $e) {
        Log::error('Error en traerDesignacionContrato: ' . $e->getMessage(), [
            'rrhhcontrato_id' => $rrhhcontrato_id,
            'trace' => $e->getTraceAsString()
        ]);
        return null;
    }
}
function traeContratoActivoEmpleadoId($empleado_id)
{
    $contrato = Rrhhcontrato::where('empleado_id', $empleado_id)
        ->where('activo', true)
        ->whereDate('fecha_inicio', '<=', now())
        ->where(function ($q) {
            $q->whereNull('fecha_fin')
                ->orWhereDate('fecha_fin', '>=', now());
        })
        ->first();

    return $contrato;
}

function numeroALetras($numero, $convertirDecimalALetras = false)
{
    // Separar la parte entera y decimal
    $numero = number_format(floatval($numero), 2, '.', '');
    $partes = explode('.', $numero);
    $entero = intval($partes[0]);
    $decimal = isset($partes[1]) ? intval($partes[1]) : 0;

    // Función recursiva para convertir números a letras
    $convertir = function ($num) use (&$convertir) {
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $especiales = [
            11 => 'ONCE',
            12 => 'DOCE',
            13 => 'TRECE',
            14 => 'CATORCE',
            15 => 'QUINCE',
            16 => 'DIECISÉIS',
            17 => 'DIECISIETE',
            18 => 'DIECIOCHO',
            19 => 'DIECINUEVE'
        ];
        $centenas = [
            '',
            'CIEN',
            'DOSCIENTOS',
            'TRESCIENTOS',
            'CUATROCIENTOS',
            'QUINIENTOS',
            'SEISCIENTOS',
            'SETECIENTOS',
            'OCHOCIENTOS',
            'NOVECIENTOS'
        ];

        if ($num == 0)
            return 'CERO';
        if ($num < 10)
            return $unidades[$num];
        if ($num >= 11 && $num <= 19)
            return $especiales[$num];
        if ($num < 100) {
            $d = intval($num / 10);
            $r = $num % 10;
            if ($r > 0) {
                if ($d == 2)
                    return 'VEINTI' . $unidades[$r];
                else
                    return $decenas[$d] . ' Y ' . $unidades[$r];
            } else
                return $decenas[$d];
        }
        if ($num < 1000) {
            $c = intval($num / 100);
            $r = $num % 100;
            if ($c == 1 && $r == 0)
                return 'CIEN';
            return $centenas[$c] . ($r > 0 ? ' ' . $convertir($r) : '');
        }
        if ($num < 1000000) {
            $m = intval($num / 1000);
            $r = $num % 1000;
            $mTexto = $m == 1 ? 'MIL' : $convertir($m) . ' MIL';
            return $mTexto . ($r > 0 ? ' ' . $convertir($r) : '');
        }
        if ($num < 1000000000) { // hasta miles de millones
            $millones = intval($num / 1000000);
            $resto = $num % 1000000;
            $mTexto = $millones == 1 ? 'UN MILLÓN' : $convertir($millones) . ' MILLONES';
            return $mTexto . ($resto > 0 ? ' ' . $convertir($resto) : '');
        }
        if ($num < 1000000000000) { // hasta billones
            $milesMillones = intval($num / 1000000000);
            $resto = $num % 1000000000;
            $texto = $milesMillones == 1 ? 'MIL MILLONES' : $convertir($milesMillones) . ' MIL MILLONES';
            return $texto . ($resto > 0 ? ' ' . $convertir($resto) : '');
        }

        // números extremadamente grandes
        $trillones = intval($num / 1000000000000);
        $resto = $num % 1000000000000;
        $texto = $trillones == 1 ? 'UN BILLÓN' : $convertir($trillones) . ' BILLONES';
        return $texto . ($resto > 0 ? ' ' . $convertir($resto) : '');
    };

    $enteroTexto = $convertir($entero);

    // Convertir decimal a literal opcional
    if ($convertirDecimalALetras) {
        $decimalTexto = $decimal > 0 ? ' CON ' . $convertir($decimal) : '';
        $decimalTexto .= '/100';
    } else {
        $decimalTexto = ' CON ' . str_pad($decimal, 2, '0', STR_PAD_LEFT) . '/100';
    }

    return $enteroTexto . $decimalTexto;
}

function ultInspeccion($cliente_id)
{
    $inspeccion = Inspeccion::where('cliente_id', $cliente_id)
        ->orderBy('id', 'desc')
        ->first();

    return $inspeccion;
}

function traeDesignacionSupervisorActiva($empleado_id)
{
    $hoy = date('Y-m-d');
    return Designacionsupervisor::where('empleado_id', $empleado_id)
        ->where('estado', 1)
        ->where(function ($q) use ($hoy) {
            $q->whereDate('fechaInicio', '<=', $hoy)
                ->whereDate('fechaFin', '>=', $hoy);
        })
        ->first();
}

function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
