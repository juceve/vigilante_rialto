<?php

namespace App\Http\Livewire;

use App\Models\Designacione;
use App\Models\Rrhhasistencia;
use App\Models\Rrhhestado;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;
use Termwind\Components\Dd;

class CtrlAsisitencias extends Component
{
    public $fechaInicio = "", $fechaFin = "", $resultados = [];
    public $selEmpleadoId = "", $idTD = "";

    public function mount()
    {
        $this->fechaInicio = date('Y-m-d');
        $this->fechaFin = date('Y-m-d');
    }
    public function render()
    {
        $estados = Rrhhestado::all();
        return view('livewire.ctrl-asisitencias', compact('estados'))->extends('adminlte::page');
    }

    protected $listeners = ['cargaCabeceras', 'exeDt', 'cargarDatos', 'registrarAsistencia'];

    public function registrarAsistencia($estado_id)
    {
        if ($this->selEmpleadoId != "" && $estado_id != "") {

            DB::beginTransaction();
            try {
                $rrhhasistencia = Rrhhasistencia::create([
                    "empleado_id" => $this->selEmpleadoId,
                    "rrhhestado_id" => $estado_id,
                    "fecha" => date('Y-m-d'),
                ]);
                DB::commit();
                $data =$this->idTD."~".$rrhhasistencia->rrhhestado->nombre_corto."~".$rrhhasistencia->rrhhestado->color;
                $this->emit('actTd',$data);
                $this->emit('toast-success', 'Registro exitoso!');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('toast-error', $th->getMessage());
            }
        } else {
            $this->emit('toast-error', 'Seleccione un estado');
        }
    }

    public function cargarDatos($parametros, $idTD)
    {
        $this->selEmpleadoId = $parametros;
        $this->idTD = $idTD;        
    }

    public function generarTabla()
    {
        $this->emit('remdt');

        $this->generaCabeceras();
        $this->traeResultados();
    }

    public function exeDt()
    {
        $this->emit('dt');
    }

    function traeResultados()
    {
        $fechas = [];
        $start = new DateTime($this->fechaInicio);
        $end = new DateTime($this->fechaFin);
        for ($dt = clone $start; $dt <= $end; $dt->modify('+1 day')) {
            $fechas[] = $dt->format('Y-m-d');
        }
        // 1. Obtener empleados con su rango de designación y nombre
        $empleados = DB::table('vwdesignaciones')
            ->select('empleado_id', 'empleado', 'fechaInicio', 'fechaFin')
            ->where('fechaInicio', '<=', $this->fechaFin)
            ->where('fechaFin', '>=', $this->fechaInicio)
            ->distinct('empleado_id')
            ->get();

        $empleadoIds = $empleados->pluck('empleado_id');
        $asistencias = DB::table('vwasistencias')
            ->whereIn('empleado_id', $empleadoIds)
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
            ->get()
            ->groupBy(function ($item) {
                return $item->empleado_id . '_' . $item->fecha;
            });

        // 4. Construir matriz resultados con nombre incluido en la primera columna
        $resultados = [];
        $i = 0;
        foreach ($empleados as $empleado) {
            $id = $empleado->empleado_id;
            $nombre = $empleado->empleado;  // Aquí el nombre literal
            $inicioDesign = $empleado->fechaInicio;
            $finDesign = $empleado->fechaFin;
            $idTd = "td" . $id;

            // La fila comienza con el nombre
            $resultados[$id]['empleado'] = "<td>$nombre</td>";

            foreach ($fechas as $fecha) {
                $idTd .= ++$i;
                $flagEsHoy = false;
                $hoy = date('Y-m-d');
                $fecha1 = Carbon::parse($fecha)->format('Y-m-d');
                $fecha2 = Carbon::parse($hoy)->format('Y-m-d');
                if ($fecha1 === $fecha2) {
                    $flagEsHoy = true;
                }

                $registro = \App\Models\Rrhhasistencia::where('empleado_id', $id)
                    ->where('fecha', $fecha)
                    ->first();
                                

                /////////////////////////////////////

                if ($fecha < $inicioDesign || $fecha > $finDesign) {
                    $resultados[$id][$fecha] = '<td id="' . $idTd . '">S/D</td>'; // Fuera de rango de designación
                } else {
                    $boton = "";
                    $texto = "";
                    $color = "";
                    if ($registro) {
                        //Si existen marcaciones rrhhasistencias
                        $texto = $registro->rrhhestado->nombre_corto;
                        $color = ' style="background-color: ' . $registro->rrhhestado->color . '"';
                    } else {
                        $key = $id . '_' . $fecha;
                        if (isset($asistencias[$key])) {
                            // si tiene asistencia web
                            $data = explode(" ", $asistencias[$key]->first()->ingreso);
                            $texto = $data[1];
                        } else {
                            // si no tiene asistencia web
                            $texto = "S/M";
                        }
                        if ($flagEsHoy) {
                            $boton = ' - <button  onclick="cargarDatos(' . $id . ',' ."'". $idTd ."'". ')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalRegistro" title="Registrar"><i class="fas fa-edit"></i></button>';
                        }
                    }

                    $td = "<td id='" . $idTd . "'" . $color . "><span>" . $texto . "</span>" . $boton . "</td>";
                    $resultados[$id][$fecha] = $td;
                }
            }
        }
        // return $resultados;
        $this->dispatchBrowserEvent('cargaBody', ['resultados' => $resultados]);
    }

    function generaCabeceras()
    {
        $fechas = [];
        $fechaActual = new DateTime($this->fechaInicio);
        $fechaFin = new DateTime($this->fechaFin);

        // Incluir la fecha final también
        $fechas[] = 'EMPLEADOS';
        while ($fechaActual <= $fechaFin) {
            $fechas[] = $this->formatFecha($fechaActual->format('Y-m-d'));
            $fechaActual->modify('+1 day');
        }
        $this->dispatchBrowserEvent('cargaCabeceras', ['fechas' => $fechas]);
    }

    function formatFecha($fecha)
    {
        // Establecer la zona horaria correcta según tu país (Bolivia, por ejemplo)
        date_default_timezone_set('America/La_Paz');

        $dias = ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'];
        $meses = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC'
        ];

        // Forzar el parseo en el formato correcto
        $dt = DateTime::createFromFormat('Y-m-d', $fecha);

        if (!$dt) {
            return 'Fecha inválida';
        }

        // Debug opcional
        // echo "DEBUG -> Fecha: {$fecha} | Día numérico (w): " . $dt->format('w') . PHP_EOL;

        $diaSemana = $dias[(int)$dt->format('w')]; // 0 = DOM
        $diaMes = $dt->format('d');
        $anio = $dt->format('Y');
        $anio = substr($anio, -2);
        $mes = $meses[(int)$dt->format('n')];

        return "{$diaSemana} {$diaMes} {$mes} {$anio}";
    }
}
