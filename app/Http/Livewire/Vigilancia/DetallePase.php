<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Flujopase;
use App\Models\Paseingreso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DetallePase extends Component
{
    public $designacione_id, $paseingreso, $procesando = false, $flujos, $anotaciones = '';

    public function mount($designacione_id, $pase_id)
    {
        $this->designacione_id = $designacione_id;
        $this->paseingreso = Paseingreso::find($pase_id);
        $this->flujos = Flujopase::where('paseingreso_id', $pase_id)->orderBy('id', 'desc')->first();
    }

    public function render()
    {
        return view('livewire.vigilancia.detalle-pase')->extends('layouts.app');
    }

    protected $listeners = ['marcar'];

    public function marcar($tipo)
    {
        if (!$this->procesando) {
            $this->procesando = true;
            DB::beginTransaction();
            try {
                $flujo = Flujopase::create([
                    "paseingreso_id" => $this->paseingreso->id,
                    "fecha" => date('Y-m-d'),
                    "tipo" => $tipo,
                    "hora" => date('H:i:s'),
                    "anotaciones" => $this->anotaciones,
                    "user_id" => Auth::user()->id,
                ]);
                if ($this->paseingreso->usounico == true && $tipo == 'SALIDA') {
                    $this->paseingreso->estado = false;
                    $this->paseingreso->save();
                }
                DB::commit();
                return redirect()->route('vigilancia.controlpases', $this->designacione_id)->with('success', 'Acceso registrado correctamente.');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->procesando = false;
                $this->emit('error', 'Ha ocurrido un error, no se registro el acceso.');
            }
        }
    }
}
