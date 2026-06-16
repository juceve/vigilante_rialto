<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\ChklEjecucione;
use App\Models\ChklIncumplimiento;
use App\Models\ChklListaschequeo;
use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Inspeccion;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdescuento;
use App\Models\SupBoleta;
use App\Models\Tipoboleta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;



class EjecutarCuestionario extends Component
{
    public $cuestionario;
    public $inspeccionActiva, $empleados;
    public $respuestas = [], $procesando = false;


    public function mount($cuestionario_id, $inspeccion_id)
    {
        $this->cuestionario = ChklListaschequeo::find($cuestionario_id);
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
        $empleados =     $this->traeEmpleados($this->inspeccionActiva->cliente_id);
        $this->empleados = $empleados ?? [];

        foreach ($this->cuestionario->chklPreguntas as $pregunta) {
            $this->respuestas[$pregunta->id] = [
                'chkl_pregunta_id' => $pregunta->id,
                'ok' => null,
                'observacion' => null,
                'tipoboleta' => $pregunta->tipoboleta_id
                    ? Tipoboleta::where('id', $pregunta->tipoboleta_id)
                    ->select('id', 'nombre', 'monto_descuento','rrhhtipodescuento_id')
                    ->first()
                    ->toArray()
                    : [],
                'empleados' => [],
            ];
        }

        
    }

    protected $listeners = ['agregarEmpleado', 'registrarRespuestas'];

    public function render()
    {
        return view('livewire.supervisores.ejecutar-cuestionario')->extends('layouts.app');
    }

    public function traeEmpleados($cliente_id)
    {
        $hoy = Carbon::today();
        $diaSemanaNum = $hoy->dayOfWeek; // 0 = domingo, 6 = sábado
        $mapDia = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
        ];
        $diaColumna = $mapDia[$diaSemanaNum];

        $designaciones = Designacione::with(['empleado', 'designaciondias', 'turno'])
            ->where('estado', true)
            ->whereDate('fechaInicio', '<=', $hoy)
            ->whereDate('fechaFin', '>=', $hoy)
            ->whereHas('designaciondias', function ($q) use ($diaColumna) {
                $q->where($diaColumna, true);
            })
            ->whereHas('turno', function ($q) use ($cliente_id) {
                $q->where('cliente_id', $cliente_id);
            })
            ->whereDoesntHave('dialibres', function ($q) use ($hoy) {
                $q->whereDate('fecha', $hoy);
            })
            ->whereDoesntHave('empleado.rrhhpermisos', function ($q) use ($hoy) {
                $q->where('activo', true)
                    ->whereDate('fecha_inicio', '<=', $hoy)
                    ->whereDate('fecha_fin', '>=', $hoy);
            })
            ->get()
            ->map(function ($designacione) {
                return [
                    'id' => $designacione->empleado->id,
                    'nombre' => $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos,
                ];
            });
        return $designaciones;
    }

    public function agregarEmpleado($pregunta_id, $empleado_id)
    {


        if (in_array($empleado_id, $this->respuestas[$pregunta_id]['empleados'])) {
            // Si existe, eliminarlo
            $this->respuestas[$pregunta_id]['empleados'] = array_filter(
                $this->respuestas[$pregunta_id]['empleados'],
                fn($id) => $id != $empleado_id
            );
            $this->respuestas[$pregunta_id]['empleados'] = array_values($this->respuestas[$pregunta_id]['empleados']);
        } else {
            // Si no existe, agregarlo
            $this->respuestas[$pregunta_id]['empleados'][] = $empleado_id;
        }
    }

    public function toggleTodosEmpleados($pregunta_id)
    {

        $cantidadInicial = count($this->empleados);
        $cantidadSeleccionados = count($this->respuestas[$pregunta_id]['empleados']);
        $this->respuestas[$pregunta_id]['empleados'] = [];
        if ($cantidadInicial != $cantidadSeleccionados) {

            $this->respuestas[$pregunta_id]['empleados'][] = $this->empleados->pluck('id')->toArray();
            unset($this->respuestas[$pregunta_id]['empleados'][0]);
            $this->respuestas[$pregunta_id]['empleados'] = array_values($this->respuestas[$pregunta_id]['empleados']);
        }
    }


    public function actualizarObservacion($pregunta_id, $texto)
    {
        $this->respuestas[$pregunta_id]['observacion'] = $texto;
    }

    public function marcarCumplimiento($pregunta_id, $valor)
    {
        $this->respuestas[$pregunta_id]['ok'] = ($valor === 'SI') ? true : false;
        if ($valor) {
            $this->respuestas[$pregunta_id]['empleados'] = [];
            $this->respuestas[$pregunta_id]['observacion'] = null;
        }
    }

    public function validar()
    {
        $this->validate([
            'respuestas.*.ok' => 'required|boolean',
            'respuestas.*.observacion' => 'nullable|string',
            'respuestas.*.tipoboleta.id' => 'nullable|exists:tipoboletas,id',
            'respuestas.*.empleados' => 'array',
            'respuestas.*.empleados.*' => 'exists:empleados,id',
        ], [
            'respuestas.*.ok.required' => 'Debe seleccionar SI o NO para todas las preguntas.',
            'respuestas.*.ok.boolean' => 'El valor de cumplimiento debe ser verdadero o falso.',
            'respuestas.*.tipoboleta.id.exists' => 'La boleta seleccionada no es válida.',
            'respuestas.*.empleados.array' => 'Los empleados deben ser un arreglo.',
            'respuestas.*.empleados.*.exists' => 'Uno o más empleados seleccionados no son válidos.',
        ]);
        $this->emit('openConfirmacion');
    }


    public function registrarRespuestas($notas)
    {
        $this->procesando = true;
        DB::beginTransaction();

        try {
            $ejecucion = ChklEjecucione::create([
                'chkl_listaschequeo_id' => $this->cuestionario->id,
                // 'notas' => "",
                'inspector_id' => $this->inspeccionActiva->designacionsupervisor->empleado_id,
                'fecha' => Carbon::now(),
                'notas' => $notas,
            ]);

            foreach ($this->respuestas as $respuesta) {
                $chklrespuesta = $ejecucion->chklRespuestas()->create([
                    'chkl_pregunta_id' => $respuesta['chkl_pregunta_id'],
                    'ok' => $respuesta['ok'],
                    'observacion' => $respuesta['observacion'],

                ]);
                if ($respuesta['ok'] === false) {
                    // dd($respuesta['tipoboleta']);
                    foreach ($respuesta['empleados'] as $empleado) {
                        $incumplimiento = ChklIncumplimiento::create([
                            'chkl_respuesta_id' => $chklrespuesta->id,
                            'empleado_id' => $empleado,
                        ]);
                        if ($respuesta['tipoboleta'] && isset($respuesta['tipoboleta']['id'])) {
                            // $tipoboleta = Tipoboleta::find($respuesta['tipoboleta']['id']);
                            $empleadoSel = Empleado::find($empleado);
                            $boleta = SupBoleta::create([
                                'fechahora' => date('Y-m-d H:i:s'),
                                'cliente_id' => $this->inspeccionActiva->cliente_id,
                                'empleado_id' => $empleado,
                                'tipoboleta_id' => $respuesta['tipoboleta']['id']??NULL,
                                'supervisor_id' => $this->inspeccionActiva->designacionsupervisor->empleado_id,
                                'detalles' => 'Emisión por incumplimiento en Cuestionario de Control',
                                'descuento' => $respuesta['tipoboleta']['monto_descuento']??'0',
                            ]);
                            if ($respuesta['tipoboleta']['monto_descuento'] > 0) {
                                $contrato = traeContratoActivoEmpleadoId($empleadoSel->id);
                                $descuento = Rrhhdescuento::create([
                                    'rrhhcontrato_id' => $contrato->id,
                                    'fecha' => date('Y-m-d'),
                                    'rrhhtipodescuento_id' =>$respuesta['tipoboleta']['rrhhtipodescuento_id'],
                                    'empleado_id' => $empleadoSel->id,
                                    'cantidad' => 1,
                                    'monto' => $respuesta['tipoboleta']['monto_descuento']
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            $this->emit('guardado');
            return redirect()->route('supervisores.listadosupervisiones', $this->inspeccionActiva->id)->with('success','Cuestionario registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Ocurrió un error al registrar las respuestas. Por favor, intente nuevamente.');
            throw $th;
        }
    }
}
