<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Cajachica as ModelsCajachica;
use App\Models\Designacionsupervisor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class Cajachica extends Component
{
    use WithFileUploads;

    public $designacion, $cajachica;
    public $filtroGestion;
    public $filtroMes = '';

    public function mount()
    {
        $designacion_id = Session::get("designacion-super");
        $this->designacion = Designacionsupervisor::find($designacion_id);
        $this->cajachica = ModelsCajachica::where("empleado_id", $this->designacion->empleado_id)
            ->where("estado", "ACTIVA")
            ->where("gestion", date('Y'))
            ->orderBy("id", "asc")
            ->first();

        $this->filtroGestion = now()->year;

    }
    public function render()
    {
        if ($this->cajachica) {

        $anioActual = Carbon::now()->year;

        $aniosBD = ModelsCajachica::selectRaw('gestion as anio')
            ->distinct()
            ->pluck('anio')
            ->toArray();

        $aniosExtra = [$anioActual];

        $gestiones = collect(array_merge($aniosBD, $aniosExtra))
            ->unique()
            ->sortDesc()
            ->values();

        $movimientos = $this->cajachica
            ->movimientocajas()
            ->whereYear('fecha', $this->filtroGestion)
            ->when($this->filtroMes, function ($q) {
                $q->whereMonth('fecha', $this->filtroMes);
            })
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();

        return view('livewire.supervisores.cajachica', compact('movimientos', 'gestiones'))->extends('layouts.app');
        } else {
            return view('livewire.supervisores.cajachica')->extends('layouts.app');
        }

    }




    public $monto, $concepto, $categoria;
    public $comprobante, $procesando = false;

    protected $rules = [
        'monto' => 'required|numeric|min:0.01',
        'concepto' => 'required|string',
        'comprobante' => 'nullable|file|max:512', // KB
    ];

    protected $listeners = ['guardarEgreso'];

    public function guardarEgreso()
    {
        if ($this->procesando) {
            return;
        }

        if ($this->monto > $this->cajachica->saldo_actual) {
            $this->emit('error', 'El monto del egreso no puede ser mayor al saldo actual');
            return;
        }

        $this->procesando = true;

        $this->validate([
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'comprobante' => 'nullable|file|max:512', // KB
        ]);

        DB::beginTransaction();
        try {
            $ruta = null;

            if ($this->comprobante) {
                $ruta = $this->comprobante->store('comprobantes', 'public');
            }

            \App\Models\Movimientocaja::create([
                'cajachica_id' => $this->cajachica->id,
                'fecha' => now()->toDateString(),
                'tipo' => 'EGRESO',
                'monto' => $this->monto,
                'concepto' => $this->concepto,
                'comprobante' => $ruta,
            ]);
            DB::commit();
            $this->reset(['monto', 'concepto', 'comprobante']);

            $this->emit('closeModal');
            $this->emit('success', 'Egreso registrado exitosamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', 'Ha ocurrido un error.');
        }


    }


}
