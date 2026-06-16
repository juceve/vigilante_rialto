<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Designacionsupervisor;
use App\Models\Inspeccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Inspecciones extends Component
{
    public $designaciones, $inspeccionActiva;
    public function mount($designacion_id)
    {
        $this->designaciones = Designacionsupervisor::find($designacion_id);
        $this->inspeccionActiva = Inspeccion::where('designacionsupervisor_id', $this->designaciones->id)
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->first();
    }
    public function render()
    {
        return view('livewire.supervisores.inspecciones');
    }

    protected $listeners = ['iniciarInspeccion' => 'iniciarInspeccion', 'finalizar' => 'finalizarInspeccionActiva'];

    public function iniciarInspeccion($cliente_id)
    {
        $inspeccion = Inspeccion::where('designacionsupervisor_id', $this->designaciones->id)
            ->where('status', 1)
            ->get();

        if ($inspeccion->count() > 0) {
            $this->emit('warning', 'Existen Inspeciones activas!');
            return;
        }
        DB::beginTransaction();
        try {
            $inspeccion = Inspeccion::create([
                'designacionsupervisor_id' => $this->designaciones->id,
                'cliente_id' => $cliente_id,
                'inicio' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            $this->inspeccionActiva = $inspeccion;
            Session::put('inspeccion_activa', $inspeccion);
            return redirect()->route('supervisores.panel',$this->inspeccionActiva->id);
            // $this->emit('success', 'Inspeccion creada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error');
        }
    }

    public function finalizarInspeccionActiva()
    {
        if ($this->inspeccionActiva) {

            $this->inspeccionActiva->fin = date('Y-m-d H:i:s');
            $this->inspeccionActiva->status = false;
            $this->inspeccionActiva->save();

            Session::put('inspeccion_activa', null);
            Session::forget('inspeccion_activa');

            return redirect()->route('home')->with('success', 'Inspección finalizada correctamente!');
        } else {
            $this->emit('warning', 'No existen inspecciones activas.');
        }
    }
}
