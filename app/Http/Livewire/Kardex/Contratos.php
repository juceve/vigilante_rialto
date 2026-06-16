<?php

namespace App\Http\Livewire\Kardex;

use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Rrhhcargo;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdocscontrato;
use App\Models\Rrhhtipocontrato;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpParser\Node\Expr\FuncCall;

class Contratos extends Component
{

    public $empleado, $procesando = false, $edit = false, $show = false, $selContrato;
    public $rrhhtipocontratoid = "", $fecha_inicio = "", $fecha_fin = "", $salario_basico = "", $rrhhcargo_id = "", $moneda = "", $motivo_fin = "", $activo = '', $gestora = '0', $caja_seguro = 0;
    public $referencia = "";
    public $cantidad_dias = 0;
    public $designacione;

    public function mount($empleado_id)
    {
        $this->empleado = Empleado::find($empleado_id);

        $hoy = Carbon::now()->toDateString();
    }
    public function render()
    {

        $tipocontratos = Rrhhtipocontrato::where('activo', 1)->get();
        $cargos = Rrhhcargo::all();
        $contratos = $this->empleado->contratos()->orderBy('id', 'desc')->get();
        return view('livewire.kardex.contratos', compact('contratos', 'tipocontratos', 'cargos'));
    }

    protected $listeners = ['registrarDoc', 'eliminarContrato'];

    protected $rules = [
        'rrhhtipocontratoid' => 'required',
        'fecha_inicio' => 'required',
        'salario_basico' => 'required',
        'rrhhcargo_id' => 'required',
        'moneda' => 'required',
        'gestora' => 'required',
        'caja_seguro' => 'required',
    ];

    public function subirArchivo()
    {
        $this->emit('subir', $this->selContrato->id);
    }

    public function registrarDoc($url, $referencia)
    {
        DB::beginTransaction();
        try {
            $referencia = str_replace(' ', '_', $referencia);
            $documento = Rrhhdocscontrato::create([
                'rrhhcontrato_id' => $this->selContrato->id,
                'referencia' => $referencia,
                'url' => $url,
            ]);

            DB::commit();
            $this->selContrato->refresh();
            $this->emit('toast-success', 'Documento registrado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();

            // Convertir a ruta relativa para Storage
            $rutaRelativa = str_replace('public/storage/', '', $url);

            Storage::disk('public')->delete($rutaRelativa);
            Log::info($th->getMessage());
        }
    }

    public function verInfo($contrato_id)
    {
        $this->designacione = traerDesignacionContrato($contrato_id);

        Session::put('designacione_data', $this->designacione);
        $this->show = true;
        $this->editContrato($contrato_id);
    }

    public function editContrato($contrato_id)
    {
        $this->designacione = traerDesignacionContrato($contrato_id);
        $this->selContrato = Rrhhcontrato::find($contrato_id);
        Session::put('contrato_data', $this->selContrato);

        $this->rrhhtipocontratoid = $this->selContrato->rrhhtipocontrato_id;
        $this->fecha_inicio = $this->selContrato->fecha_inicio;
        $this->fecha_fin = $this->selContrato->fecha_fin;
        $this->salario_basico = $this->selContrato->salario_basico;
        $this->rrhhcargo_id = $this->selContrato->rrhhcargo_id;
        $this->moneda = $this->selContrato->moneda;
        $this->gestora = $this->selContrato->gestora;
        $this->caja_seguro = $this->selContrato->caja_seguro;
        $this->motivo_fin = $this->selContrato->motivo_fin;
        $this->activo = $this->selContrato->activo;
        $this->edit = true;
    }

    public function limpiar()
    {
        $this->reset('rrhhtipocontratoid', 'fecha_inicio', 'fecha_fin', 'salario_basico', 'rrhhcargo_id', 'moneda', 'motivo_fin', 'procesando', 'activo', 'selContrato', 'edit', 'show');
    }

    public function updateContrato()
    {
        if ($this->procesando) {
            return;
        } else {
            $this->validate();

            $this->procesando = true;
            DB::beginTransaction();
            try {
            $this->selContrato->empleado_id = $this->empleado->id;
            $this->selContrato->rrhhtipocontrato_id = $this->rrhhtipocontratoid;
            $this->selContrato->fecha_inicio = $this->fecha_inicio;
            $this->selContrato->fecha_fin = $this->fecha_fin ? $this->fecha_fin : NULL;
            $this->selContrato->salario_basico = $this->salario_basico;
            $this->selContrato->rrhhcargo_id = $this->rrhhcargo_id;
            $this->selContrato->moneda = $this->moneda;
            $this->selContrato->gestora = $this->gestora;
            $this->selContrato->caja_seguro = $this->caja_seguro;
            $this->selContrato->activo = $this->activo;
            $this->selContrato->motivo_fin = $this->motivo_fin ? $this->motivo_fin : NULL;
            $this->selContrato->save();



            if ($this->activo == 0) {
                $designacionesActivas = traerDesignacionContrato($this->selContrato->id);
                if ($designacionesActivas) {
                    foreach ($designacionesActivas as $designacion) {
                        $designacion->estado = false;
                        $designacion->save();
                    }
                }


                if ($this->selContrato->empleado->user) {
                    $this->selContrato->empleado->user->update(['status' => 0]);
                }
            }

            DB::commit();
            $this->limpiar();
            $this->empleado->refresh();
            $this->emit('success', 'Contrato actualizado correctamente.');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->procesando = false;
                $this->emit('error', $th->getMessage());
            }
        }
    }
    public function registrarContrato()
    {

        if ($this->procesando) {
            return;
        } else {
            $this->validate();

            if ($this->cantidad_dias < 30) {
                $fechaInicio = Carbon::parse($this->fecha_inicio);

                // Cantidad de días a sumar
                $cantidadDias = $this->cantidad_dias;

                // Fecha final
                $fechaFin = $fechaInicio->copy()->addDays($cantidadDias - 1);

                $this->fecha_fin = $fechaFin->toDateString(); // Ej: 2025-08-14
            }
            $this->procesando = true;
            DB::beginTransaction();
            try {
                $contrato = Rrhhcontrato::create([
                    "empleado_id" => $this->empleado->id,
                    "rrhhtipocontrato_id" => $this->rrhhtipocontratoid,
                    "fecha_inicio" => $this->fecha_inicio,
                    "fecha_fin" => $this->fecha_fin ? $this->fecha_fin : NULL,
                    "salario_basico" => $this->salario_basico,
                    "rrhhcargo_id" => $this->rrhhcargo_id,
                    "moneda" => $this->moneda,
                    "gestora" => $this->gestora,
                    "caja_seguro" => $this->caja_seguro,
                ]);
                DB::commit();
                $this->limpiar();
                $this->empleado->refresh();
                $this->emit('cerrarModalReg');
                $this->emit('success', 'Contrato registrado correctamente.');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->procesando = false;
                $this->emit('error', $th->getMessage());
            }
        }
    }

    public function updatedRrhhtipocontratoid()
    {
        $tipocontrato = Rrhhtipocontrato::find($this->rrhhtipocontratoid);
        $this->salario_basico = $tipocontrato->sueldo_referencial;
        $this->cantidad_dias = $tipocontrato->cantidad_dias;
    }

    public function eliminarContrato($contrato_id)
    {

        DB::beginTransaction();
        try {
            $contrato = Rrhhcontrato::find($contrato_id);

            if ($contrato) {
                $contrato->delete();
            }

            DB::commit();
            $this->emit('success', 'Contrato eliminado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }
}
